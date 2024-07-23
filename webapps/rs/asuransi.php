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

$konektor = bukakoneksi();
if ($konektor) {

}

$queryDataTabel = mysqli_query($konektor, "SELECT reg_periksa.no_rawat as rawat, pasien.nm_pasien as pasien, reg_periksa.stts AS Pemeriksaan_Dokter, reg_periksa.status_bayar AS Keterangan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.status_bayar = 'Belum Bayar' AND reg_periksa.stts = 'Sudah' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");

?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif;color: black;">

<!-- include navbar -->
    <?php include 'include/navbar.php'; ?>

    <div class="container">
        <div class="row mb-3">
            <h2>Data Pasien Perasuransi</h2>
        </div>

        <div class="col-lg-12 mb-3">
                <form action="" method="post" class="form-inline">
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
        <br>
        <div class="row mt-3">
            <div class="col-lg-12">
                <table class="table table-light table-hover text-black-50" id="chuakss">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">No Rawat</th>
                            <th scope="col">Pasien</th>
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
                                $Pemeriksaan_Dokter = $row['Pemeriksaan_Dokter'];
                                $keterangan = $row['Keterangan'];
                                // Menambahkan nilai belum bayar ke totalBelumBayar
                                //$totalBelumBayar += $belumbayar;
                                ?>
                                    <tr>
                                    <th scope="row"><?php echo $no; ?></th>
                                    <td><?php echo $rawat; ?></td>
                                    <td><?php echo $pasien; ?></td>
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
    $('#chuakss').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel','pdf'
        ]
    } );
} );

</script>

</html>