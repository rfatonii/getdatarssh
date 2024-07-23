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

    $queryReguler = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_poli) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj NOT IN ('U0024','U0025','U0032','U0034','U0036','U0038','U0041','U0051') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowReguler = mysqli_fetch_assoc($queryReguler);
    $totalReguler = $rowReguler['Asad'];

    $queryEkse = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_poli) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj IN ('U0024','U0025','U0032','U0034','U0036','U0038','U0041','U0051') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowEkse = mysqli_fetch_assoc($queryEkse);
    $totalEkse = $rowEkse['Asad'];

    $queryUmum = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj IN ('A09','-','DKM','OB1') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowUmum = mysqli_fetch_assoc($queryUmum);
    $totalUmum = $rowUmum['Asad'];

    $queryBPJS = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj = 'BPJ' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowBPJS = mysqli_fetch_assoc($queryBPJS);
    $totalBPJS = $rowBPJS['Asad'];

    $queryKaryawan = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj IN ('RS','A77','FM','C22') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowKaryawan = mysqli_fetch_assoc($queryKaryawan);
    $totalKaryawan = $rowKaryawan['Asad'];

    $queryAsuransi = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.kd_pj NOT IN ('A09','BPJ','DKM','RS','A77') AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowAsuransi = mysqli_fetch_assoc($queryAsuransi);
    $totalAsuransi = $rowAsuransi['Asad'];

    $querySudahbayar = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.status_bayar = 'Sudah Bayar' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowSudahbayar = mysqli_fetch_assoc($querySudahbayar);
    $totalSudahbayar = $rowSudahbayar['Asad'];

    $queryBelumbayar = mysqli_query($konektor, "SELECT COUNT(reg_periksa.kd_pj) as Asad FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.status_bayar = 'Belum Bayar' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowBelumbayar = mysqli_fetch_assoc($queryBelumbayar);
    $totalBelumbayar = $rowBelumbayar['Asad'];

    // Mendapatkan data pemasukan per bulan
    $queryPemasukanPerBulan = mysqli_query($konektor, "SELECT DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') AS bulan, SUM(detail_nota_jalan.besar_bayar) AS total_pemasukan_per_bulan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_jalan ON detail_nota_jalan.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND '$tanggalKedua' GROUP BY DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') ORDER BY reg_periksa.tgl_registrasi");
    $dataPemasukanPerBulan = [];
    $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    foreach ($bulan as $namaBulan) {
        $dataPemasukanPerBulan[$namaBulan] = 0;
    }
    while ($rowPemasukanPerBulan = mysqli_fetch_assoc($queryPemasukanPerBulan)) {
        $namaBulan = $rowPemasukanPerBulan['bulan'];
        $totalPemasukanPerBulan = $rowPemasukanPerBulan['total_pemasukan_per_bulan'];
        $dataPemasukanPerBulan[$namaBulan] = $totalPemasukanPerBulan;
    }

    // Mendapatkan total pemasukan
    $queryPemasukan = mysqli_query($konektor, "SELECT SUM(detail_nota_jalan.besar_bayar) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_jalan ON detail_nota_jalan.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan = mysqli_fetch_assoc($queryPemasukan);
    $totalPemasukan = $rowPemasukan['total_pemasukan'] ?? 0;


    mysqli_close($konektor);
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>

<body style="font-family: 'Poppins', sans-serif;color: black;">

    <!-- include navbar -->
    <?php include 'include/navbar.php'; ?>

    <!-- container -->
    <div class="container">
        <div class="row mb-3">
            <h2>Dashboard Rawat Jalan</h2>
        </div>

        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <div class="card shadow">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block rounded-sm p-1">
                            <i class="ti-wheelchair text-primary border-success text-success"></i>
                        </div>
                        <div class="stat-content d-inline-block p-1">
                            <div class="stat-text">Pasien Eksekutif</div>
                            <div class="stat-digit" id="totalEkse">
                                <?php echo "<p>$totalEkse</p>"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6">
                <div class="card shadow">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block p-1">
                            <i class="ti-wheelchair text-primary border-primary"></i>
                        </div>
                        <div class="stat-content d-inline-block p-1">
                            <div class="stat-text">Pasien Reguler</div>
                            <div class="stat-digit" id="totalReguler">
                                <?php echo "<p>$totalReguler</p>"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Form Pencarian -->
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

            <!-- Diagram-Bar -->
            <!-- <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">Bar-Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="barChart2"></canvas>
                    </div>
                </div>
            </div> -->
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="stat-widget-two card-body">
                                    <div class="stat-content">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="stat-text">
                                                    <h3>Pemasukan Rawat Jalan</h3>
                                                </div>    
                                            </div>
                                        </div>

                                        <!-- Tarik data tabel dari database -->
                                        <div class="stat-digit fw-bold">
                                            <div class="row">
                                                <div class="col-lg-3">Rp</div>
                                                <div class="col-lg-9 text-start" style="font-size:3vw">
                                                    <?php
                                                        function rupiah($totalPemasukan){
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
                            </div> 
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="stat-widget-two card-body">
                                    <h5 class="card-title">Grafik Total Pemasukan per Bulan</h5>
                                        <div id="chartContainer" style="height: 250px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>                                     
                    </div>
                </div>
            <!-- Pasien Umum, BPJS, Eksekutif -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <br>
                        <div class="stat-content d-inline-block text-center">
                            <div class="stat-text">Total Pasien Registrasi</div>
                        </div>                                     
                    <div class="card-body">
                        <!-- Baris Jenis Pasien -->
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="card bg-facebook">
                                    <div class="stat-widget-two card-body">
                                        <div class="stat-content ">
                                            <div class="row">
                                                <div class="col-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="40" fill="currentColor" class="bi bi-clipboard2-pulse-fill text-light mb-2" viewBox="0 0 16 16">
                                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM9.98 5.356 11.372 10h.128a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.479-.356l-.94-3.135-1.092 5.096a.5.5 0 0 1-.968.039L6.383 8.85l-.936 1.873A.5.5 0 0 1 5 11h-.5a.5.5 0 0 1 0-1h.191l1.362-2.724a.5.5 0 0 1 .926.08l.94 3.135 1.092-5.096a.5.5 0 0 1 .968-.039Z"/>
                                                    </svg>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <div class="stat-text text-white ml-2 mt-2">Umum</div>
                                                </div>
                                            </div>
                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                                <?php
                                                echo "<p>$totalUmum</p>";
                                                ?>
                                            </div>
                                        </div>
                                       
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-warning w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="card bg-success">
                                    <div class="stat-widget-two card-body">
                                        <div class="stat-content">
                                            <div class="row">
                                                <div class="col-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="40" fill="currentColor" class="bi bi-clipboard2-pulse-fill text-light mb-2" viewBox="0 0 16 16">
                                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM9.98 5.356 11.372 10h.128a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.479-.356l-.94-3.135-1.092 5.096a.5.5 0 0 1-.968.039L6.383 8.85l-.936 1.873A.5.5 0 0 1 5 11h-.5a.5.5 0 0 1 0-1h.191l1.362-2.724a.5.5 0 0 1 .926.08l.94 3.135 1.092-5.096a.5.5 0 0 1 .968-.039Z"/>
                                                    </svg>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <div class="stat-text text-white ml-2 mt-2">BPJS</div>
                                                </div>
                                            </div>
                                            <div class="stat-digit text-white">

                                            <!-- Tarik data tabel dari database -->
                                            <?php
                                                echo "<p>$totalBPJS</p>";
                                                ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-facebook w-75" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="card bg-warning">
                                    <div class="stat-widget-two card-body">
                                        <div class="stat-content">
                                            <div class="row">
                                                <div class="col-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="40" fill="currentColor" class="bi bi-clipboard2-pulse-fill text-light mb-2" viewBox="0 0 16 16">
                                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM9.98 5.356 11.372 10h.128a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.479-.356l-.94-3.135-1.092 5.096a.5.5 0 0 1-.968.039L6.383 8.85l-.936 1.873A.5.5 0 0 1 5 11h-.5a.5.5 0 0 1 0-1h.191l1.362-2.724a.5.5 0 0 1 .926.08l.94 3.135 1.092-5.096a.5.5 0 0 1 .968-.039Z"/>
                                                    </svg>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <div class="stat-text text-white ml-2 mt-2">Karyawan</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                                <?php
                                                echo "<p>$totalKaryawan</p>";
                                                ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-danger w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <div class="card bg-danger">
                                    <div class="stat-widget-two card-body">
                                        <div class="stat-content">
                                            <div class="row">
                                                <div class="col-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="40" fill="currentColor" class="bi bi-clipboard2-pulse-fill text-light mb-2" viewBox="0 0 16 16">
                                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM9.98 5.356 11.372 10h.128a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.479-.356l-.94-3.135-1.092 5.096a.5.5 0 0 1-.968.039L6.383 8.85l-.936 1.873A.5.5 0 0 1 5 11h-.5a.5.5 0 0 1 0-1h.191l1.362-2.724a.5.5 0 0 1 .926.08l.94 3.135 1.092-5.096a.5.5 0 0 1 .968-.039Z"/>
                                                    </svg>
                                                </div>
                                                <div class="col-9 text-left">
                                                    <div class="stat-text text-white ml-2 mt-2">Asuransi</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                            echo "<p>$totalAsuransi</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-sm-6 p-0">
                <div class="card rounded-0">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-stats-up text-success border-success mt-2"></i>
                        </div>
                        <div class="stat-content d-inline-block text-center">
                            <div class="stat-text">Pasien Sudah Bayar</div>
                            <div class="stat-digit">
                                <?php
                                    echo "<p>$totalSudahbayar</p>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6 p-0">
                <div class="card rounded-0">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="ti-stats-up text-success border-success mt-2"></i>
                        </div>
                        <div class="stat-content d-inline-block text-center">
                            <div class="stat-text">Pasien Belum Bayar</div>
                            <div class="stat-digit">
                                <?php
                                    echo "<p>$totalBelumbayar</p>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
        
    <div class="footer">
        <!-- <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
            <p>Distributed by <a href="https://themewagon.com/" target="_blank">Themewagon</a></p> 
        </div> -->
    </div>

    <!-- include script -->
    <?php include 'include/script.php'; ?>


    <!-- include JS -->
    <?php include 'include/js.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function() {
            var dataPoints = [
                <?php
                foreach ($dataPemasukanPerBulan as $namaBulan => $total) {
                    echo "{ label: '$namaBulan', y: $total },";
                }
                ?>
            ];

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: ""
                },
                axisY: {
                    title: ""
                },
                data: [{
                    type: "column",
                    yValueFormatString: "Rp #,###",
                    dataPoints: dataPoints
                }]
            });
            chart.render();
        }
    </script>
</body>

</html>