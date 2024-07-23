<?php
session_start();
require_once('../conf/conf.php');

if (isset($_POST['search'])) {
    $tanggalPertama = $_POST['tanggal_pertama'];
    $tanggalKedua = $_POST['tanggal_kedua'];
} else {
    $tanggalPertama = date('Y-m-d');
    $tanggalKedua = date('Y-m-d');
}

if (isset($_POST['search1'])) {
    $tanggalPertama1 = $_POST['tanggal_pertama1'];
    $tanggalKedua1 = $_POST['tanggal_kedua1'];
} else {
    $tanggalPertama1 = date('Y-m-d');
    $tanggalKedua1 = date('Y-m-d');
}

$konektor = bukakoneksi();
if ($konektor) {

    // Mendapatkan total rawat jalan
    $queryPemasukan = mysqli_query($konektor, "SELECT SUM(detail_nota_jalan.besar_bayar) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_jalan ON detail_nota_jalan.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan = mysqli_fetch_assoc($queryPemasukan);
    $totalPemasukan = $rowPemasukan['total_pemasukan'] ?? 0;

    // Mendapatkan total rawat inap
    $queryPemasukan1 = mysqli_query($konektor, "SELECT SUM(detail_nota_inap.besar_bayar) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_inap ON detail_nota_inap.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan1 = mysqli_fetch_assoc($queryPemasukan1);
    $totalPemasukan1 = $rowPemasukan1['total_pemasukan'] ?? 0;

    // Mendapatkan total deposit
    $queryPemasukan3 = mysqli_query($konektor, "SELECT SUM(deposit.besar_deposit) - COALESCE(SUM(pengembalian_deposit.besar_pengembalian), 0) AS saldo_deposit FROM reg_periksa INNER JOIN deposit ON reg_periksa.no_rawat = deposit.no_rawat LEFT JOIN pengembalian_deposit ON reg_periksa.no_rawat = pengembalian_deposit.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan3 = mysqli_fetch_assoc($queryPemasukan3);
    $totalPemasukan3 = $rowPemasukan3['saldo_deposit'] ?? 0;

    // Mendapatkan total piutang pasien
    $queryPemasukan2 = mysqli_query($konektor, "SELECT SUM(detail_piutang_pasien.totalpiutang) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_piutang_pasien ON detail_piutang_pasien.no_rawat = reg_periksa.no_rawat WHERE reg_periksa.kd_pj IN ('A09','RS','A77','FM','PRS','C22','DKM') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan2 = mysqli_fetch_assoc($queryPemasukan2);
    $totalPemasukan2 = $rowPemasukan2['total_pemasukan'] ?? 0;

    // Mendapatkan piutang bpjs
    $queryPemasukan4 = mysqli_query($konektor, "SELECT SUM(detail_piutang_pasien.totalpiutang) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_piutang_pasien ON detail_piutang_pasien.no_rawat = reg_periksa.no_rawat WHERE reg_periksa.kd_pj = 'BPJ' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan4 = mysqli_fetch_assoc($queryPemasukan4);
    $totalPemasukan4 = $rowPemasukan4['total_pemasukan'] ?? 0;

    // Mendapatkan piutang asuransi
    $queryPemasukan5 = mysqli_query($konektor, "SELECT SUM(detail_piutang_pasien.totalpiutang) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_piutang_pasien ON detail_piutang_pasien.no_rawat = reg_periksa.no_rawat WHERE reg_periksa.kd_pj NOT IN ('BPJ','A09','RS','A77','FM','PRS','C22','DKM') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan5 = mysqli_fetch_assoc($queryPemasukan5);
    $totalPemasukan5 = $rowPemasukan5['total_pemasukan'] ?? 0;


}

function rupiah($totalPemasukan)
{
    $hasil_rupiah = " " . number_format($totalPemasukan, 0, ',', '.');
    return $hasil_rupiah;
}

function totalPendapatan($totalPemasukan, $totalPemasukan1, $totalPemasukan2, $totalPemasukan3, $totalPemasukan4, $totalPemasukan5)
{
    $total = $totalPemasukan + $totalPemasukan1 + $totalPemasukan2 + $totalPemasukan3 + $totalPemasukan4 + $totalPemasukan5;
    return $total;
}

$totalPendapatan = totalPendapatan($totalPemasukan, $totalPemasukan1, $totalPemasukan2, $totalPemasukan3, $totalPemasukan4, $totalPemasukan5);

$queryDataTabel = mysqli_query($konektor, "SELECT reg_periksa.no_rawat as rawat, pasien.nm_pasien as pasien, penjab.png_jawab AS menggunakan, reg_periksa.stts AS Pemeriksaan_Dokter, reg_periksa.status_bayar AS Keterangan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj WHERE reg_periksa.status_bayar = 'Belum Bayar' AND reg_periksa.stts = 'Sudah' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama1' AND '$tanggalKedua1'");


?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif;color: black;">

<!-- include navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">
        <div class="row mb-3">
            <h2>Pendapatan & Piutang</h2>
        </div>
            <!-- Pencarian -->
            <div class="col-lg-12 mb-3">
                <form action="" method="post" class="form-inline justify-content-center">
                    <div class="form-group">
                        <label for="tanggal_pertama">Tanggal Pertama:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_pertama" name="tanggal_pertama"
                        value="<?php echo $tanggalPertama; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kedua">Tanggal Kedua:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_kedua" name="tanggal_kedua"
                        value="<?php echo $tanggalKedua; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="search">Cari</button>
                </form>
            </div>
            <div class="row justify-content-center">
                <!-- Pendapatan Ralan -->
                <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-up text-success border-success mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Keuntungan <br> Rawat Jalan</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah4($totalPemasukan){
                                        $hasil_rupiah = " " . number_format($totalPemasukan,0,',','.');
                                        return $hasil_rupiah;    
                                        }     
                                        echo rupiah($totalPemasukan);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendapatan Ranap -->
                <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-up text-success border-success mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Keuntungan <br> Rawat Inap</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah1($totalPemasukan1){
                                        $hasil_rupiah = " " . number_format($totalPemasukan1,0,',','.');
                                        return $hasil_rupiah;        
                                        }
                                        echo rupiah1($totalPemasukan1);
                                     ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendapatan Deposit -->
                <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-up text-success border-success mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Pemasukan <br> Deposit</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah3($totalPemasukan3){
                                        $hasil_rupiah = " " . number_format($totalPemasukan3,0,',','.');
                                        return $hasil_rupiah;        
                                        }
                                        echo rupiah3($totalPemasukan3);
                                     ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <!-- Pendapatan Piutang Pasien-->
                <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-down text-danger border-danger mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Piutang <br> Pasien</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah2($totalPemasukan2){
                                        $hasil_rupiah = " " . number_format($totalPemasukan2,0,',','.');
                                        return $hasil_rupiah;     
                                        }    
                                        echo rupiah2($totalPemasukan2);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendapatan Piutang BPJS-->
                <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-down text-danger border-danger mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Piutang <br> BPJS</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah5($totalPemasukan4){
                                        $hasil_rupiah = " " . number_format($totalPemasukan4,0,',','.');
                                        return $hasil_rupiah;     
                                        }    
                                        echo rupiah5($totalPemasukan4);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pendapatan Piutang -->
                    <div class="col-lg-4 col-sm-6 p-0">
                    <div class="card rounded-0">
                        <div class="stat-widget-one card-body">
                            <div class="stat-icon d-inline-block">
                                <i class="ti-stats-down text-danger border-danger mt-2"></i>
                            </div>
                            <div class="stat-content d-inline-block text-center">
                                <div class="stat-text">Piutang <br> Asuransi</div>
                                <div class="stat-digit">
                                    <?php
                                        function rupiah6($totalPemasukan5){
                                        $hasil_rupiah = " " . number_format($totalPemasukan5,0,',','.');
                                        return $hasil_rupiah;     
                                        }    
                                        echo rupiah6($totalPemasukan5);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                        
        <div class="row mt-3">
            <div class="col-lg-12 text-center">
                <div class="card rounded-0">
                    <div class="stat-widget-two card-body">
                        <h4 class="card-title stat-digit fw-bold">Total Pendapatan</h4>
                        <h2 class="card-text">Rp<?php echo rupiah($totalPendapatan); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <form action="" method="post" class="form-inline justify-content-center">
                    <div class="form-group">
                        <label for="tanggal_pertama1">Pencarian Belum Bayar:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_pertama1" name="tanggal_pertama1"
                            value="<?php echo $tanggalPertama1; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kedua1">s/d</label>
                        <input type="date" class="form-control mx-2" id="tanggal_kedua1" name="tanggal_kedua1"
                            value="<?php echo $tanggalKedua1; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="search1">Cari</button>
                    <!-- Tambahkan tombol untuk ekspor ke Excel -->
                    <!-- <a href="export.php" class="btn btn-secondary">Export to Excel</a>  -->
                    <!-- Tambahkan tombol untuk ekspor ke PDF -->
                    <!-- <a href="pdf.php" class="btn btn-danger">Export to PDF</a>  -->
                    
        </form>
        
        <br>
        <div class="row mt-3"> 
            <div class="col-lg-12">
                <table class="table table-light table-hover text-black-50" id="mauexport">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">No Rawat</th>
                            <th scope="col">Pasien</th>
                            <th scope="col">Menggunakan</th>
                            <th scope="col">Pemeriksaan Dokter</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $totalBelumBayar = 0; // Menyimpan total belum bayar
                            while ($row = mysqli_fetch_assoc($queryDataTabel)) 
                            {
                                $rawat = $row['rawat'];
                                $pasien = $row['pasien'];
                                $menggunakan = $row['menggunakan'];
                                $Pemeriksaan_Dokter = $row['Pemeriksaan_Dokter'];
                                $keterangan = $row['Keterangan'];
                                // Menambahkan nilai belum bayar ke totalBelumBayar
                                //$totalBelumBayar += $belumbayar;
                                ?>
                                    <tr>
                                    <td scope="row"><?php echo $no; ?></td>
                                    <td><?php echo $rawat; ?></td>
                                    <td><?php echo $pasien; ?></td>
                                    <td><?php echo $menggunakan; ?></td>
                                    <td><?php echo $Pemeriksaan_Dokter; ?></td>
                                    <td><?php echo $keterangan; ?></td>
                                    </tr>
                                <?php
                                $no++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <?php include 'include/script.php'; ?>
</body>

<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel','pdf'
        ]
    } );
} );

</script>

</html>