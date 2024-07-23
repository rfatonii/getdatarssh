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
    $queryMina = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal = 'MINA'");
    $rowMina = mysqli_fetch_assoc($queryMina);
    $totalMina = $rowMina['Rabbani'];

    $queryHijirismail = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal = 'HIJIR ISMAIL'");
    $rowHijirismail = mysqli_fetch_assoc($queryHijirismail);
    $totalHijirismail = $rowHijirismail['Rabbani'];

    $queryMuzdalifah = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal = 'MUZDALIFAH'");
    $rowMuzdalifah = mysqli_fetch_assoc($queryMuzdalifah);
    $totalMuzdalifah = $rowMuzdalifah['Rabbani'];

    $queryMultazam = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal = 'MULTAZAM'");
    $rowMultazam = mysqli_fetch_assoc($queryMultazam);
    $totalMultazam = $rowMultazam['Rabbani'];

    $queryCempaka = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal = 'CEMPAKA PLUS'");
    $rowCempaka = mysqli_fetch_assoc($queryCempaka);
    $totalCempaka = $rowCempaka['Rabbani'];

    $queryIsolasi = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal IN ('ISOLASI RAWAT INAP','ISOLASI UGD')");
    $rowIsolasi = mysqli_fetch_assoc($queryIsolasi);
    $totalIsolasi = $rowIsolasi['Rabbani'];

    $queryBayi = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal ='Ruang Bayi'");
    $rowBayi = mysqli_fetch_assoc($queryBayi);
    $totalBayi = $rowBayi['Rabbani'];

    $queryIcu = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) AS Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat INNER JOIN kamar ON kamar_inap.kd_kamar = kamar.kd_kamar INNER JOIN bangsal ON kamar.kd_bangsal = bangsal.kd_bangsal WHERE kamar_inap.stts_pulang IN ('-') AND bangsal.nm_bangsal IN ('RUANG ICU','RUANG NICU')");
    $rowIcu = mysqli_fetch_assoc($queryIcu);
    $totalIcu = $rowIcu['Rabbani'];

    $queryBelumpulang = mysqli_query($konektor, "SELECT COUNT(reg_periksa.no_rawat) as Rabbani FROM reg_periksa INNER JOIN kamar_inap ON reg_periksa.no_rawat = kamar_inap.no_rawat WHERE kamar_inap.stts_pulang = '-'");
    $rowBelumpulang = mysqli_fetch_assoc($queryBelumpulang);
    $totalBelumpulang = $rowBelumpulang['Rabbani'];

    // Mendapatkan data pemasukan per bulan
    $queryPemasukanPerBulan = mysqli_query($konektor, "SELECT DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') AS bulan, SUM(detail_nota_inap.besar_bayar) AS total_pemasukan_per_bulan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_inap ON detail_nota_inap.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND '$tanggalKedua' GROUP BY DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') ORDER BY reg_periksa.tgl_registrasi");
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

    // Mendapatkan data deposit per bulan
    $queryDepositPerBulan = mysqli_query($konektor, "SELECT DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') AS bulan, SUM(deposit.besar_deposit) - COALESCE(SUM(pengembalian_deposit.besar_pengembalian), 0) AS total_deposit_per_bulan FROM reg_periksa INNER JOIN deposit ON reg_periksa.no_rawat = deposit.no_rawat LEFT JOIN pengembalian_deposit ON reg_periksa.no_rawat = pengembalian_deposit.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND '$tanggalKedua' GROUP BY DATE_FORMAT(reg_periksa.tgl_registrasi, '%b') ORDER BY reg_periksa.tgl_registrasi");
    $dataDepositPerBulan = [];
    foreach ($bulan as $namaBulan) {
        $dataDepositPerBulan[$namaBulan] = 0;
    }
    while ($rowDepositPerBulan = mysqli_fetch_assoc($queryDepositPerBulan)) {
        $namaBulan = $rowDepositPerBulan['bulan'];
        $totalDepositPerBulan = $rowDepositPerBulan['total_deposit_per_bulan'];
        $dataDepositPerBulan[$namaBulan] = $totalDepositPerBulan;
    }

    // Mendapatkan total rawat inap
    $queryPemasukan1 = mysqli_query($konektor, "SELECT SUM(detail_nota_inap.besar_bayar) as total_pemasukan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN detail_nota_inap ON detail_nota_inap.no_rawat = reg_periksa.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan1 = mysqli_fetch_assoc($queryPemasukan1);
    $totalPemasukan1 = $rowPemasukan1['total_pemasukan'] ?? 0;

    // Mendapatkan total deposit
    $queryPemasukan3 = mysqli_query($konektor, "SELECT SUM(deposit.besar_deposit) - COALESCE(SUM(pengembalian_deposit.besar_pengembalian), 0) AS saldo_deposit FROM reg_periksa INNER JOIN deposit ON reg_periksa.no_rawat = deposit.no_rawat LEFT JOIN pengembalian_deposit ON reg_periksa.no_rawat = pengembalian_deposit.no_rawat WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama' AND '$tanggalKedua'");
    $rowPemasukan3 = mysqli_fetch_assoc($queryPemasukan3);
    $totalPemasukan3 = $rowPemasukan3['saldo_deposit'] ?? 0;
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
            <h2>Dashboard Rawat Inap</h2>
        </div>
        <!-- Jumlah Pasien -->
        <div class="row">
            <div class="col-lg-12 col-sm-6">
                <div class="card shadow">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block rounded-sm p-1">
                            <i class="ti-wheelchair text-primary border-success text-success"></i>
                        </div>
                        <div class="stat-content d-inline-block p-1">
                            <div class="stat-text">Jumlah Pasien Rawat Inap</div>
                            <div class="stat-digit" id="totalBelumpulang">
                                <?php echo "<p>$totalBelumpulang</p>"; ?>
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
                <!-- Pemasukan -->
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="stat-widget-two card-body">
                                    <div class="stat-content">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="stat-text">
                                                    <h3>Pemasukan Rawat Inap</h3>
                                                </div>    
                                            </div>
                                        </div>

                                        <!-- Tarik data tabel dari database -->
                                        <div class="stat-digit fw-bold">
                                            <div class="row">
                                                <div class="col-lg-3">Rp</div>
                                                <div class="col-lg-9 text-start" style="font-size:3vw">
                                                    <?php
                                                        function rupiah($totalPemasukan1){
                                                        $hasil_rupiah = " " . number_format($totalPemasukan1,0,',','.');
                                                        return $hasil_rupiah;
                                                        }
                                                        echo rupiah($totalPemasukan1);
                                                    ?>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div> 
                        </div>              
                    </div>
                </div>
                <!-- Deposito -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="stat-text">
                                            <h3>Deposito Rawat Inap</h3>
                                        </div>    
                                    </div>
                                </div>
                                <div class="stat-digit fw-bold">
                                    <div class="row">
                                        <div class="col-lg-3">Rp</div>
                                        <div class="col-lg-9 text-start" style="font-size:3vw">
                                            <?php
                                                function rupiah5($totalPemasukan3){
                                                $hasil_rupiah = " " . number_format($totalPemasukan3,0,',','.');
                                                return $hasil_rupiah;
                                                }
                                                echo rupiah5($totalPemasukan3);
                                            ?>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="stat-widget-two card-body">
                                    <h5 class="card-title">Grafik Total Pemasukan per Bulan</h5>
                                    <div id="chartContainer1" style="height: 250px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                                <h5 class="card-title">Grafik Total Deposito per Bulan</h5>
                                <div id="chartContainer2" style="height: 250px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- Pasien Mina, Hijirismail, DLL -->
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-body">
                            <!-- Baris Jenis Pasien -->
                                <div class="row">
                                    <!--Kamar 1-->
                                    <div class="col-lg-4 col-md-12 col-sm-6">
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
                                                                <div class="stat-text text-white ml-2 mt-2">Mina</div>
                                                            </div>
                                                    </div>
                                                    <!-- Tarik data tabel dari database -->
                                                    <div class="stat-digit text-white">
                                                        <?php
                                                            echo "<p>$totalMina</p>";
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-warning w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Kamar 2-->
                                    <div class="col-lg-4 col-md-12 col-sm-6">
                                        <div class="card bg-facebook">
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
                                                            <div class="stat-text text-white ml-2 mt-2">Hijir Ismail</div>
                                                        </div>
                                                    </div>
                                                    <div class="stat-digit text-white">
                                                    <!-- Tarik data tabel dari database -->
                                                        <?php
                                                            echo "<p>$totalHijirismail</p>";
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-warning w-75" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            <div class="col-lg-4 col-md-12 col-sm-6">
                                <div class="card bg-facebook">
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
                                                    <div class="stat-text text-white ml-2 mt-2">Muzdalifah</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                                <?php
                                                echo "<p>$totalMuzdalifah</p>";
                                                ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-warning w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-6">
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
                                                    <div class="stat-text text-white ml-2 mt-2">Multazam</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                            echo "<p>$totalMultazam</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-facebook w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-12 col-sm-6">
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
                                                    <div class="stat-text text-white ml-2 mt-2">Cempaka Plus</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                            echo "<p>$totalCempaka</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-facebook w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-6">
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
                                                    <div class="stat-text text-white ml-2 mt-2">Isolasi</div>
                                                </div>
                                            </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                            echo "<p>$totalIsolasi</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-facebook w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-6">
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
                                                     <div class="stat-text text-white ml-2 mt-2">Ruang Bayi</div>
                                                    </div>
                                                </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                                echo "<p>$totalBayi</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-danger w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-6 rounded-pill">
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
                                                     <div class="stat-text text-white ml-2 mt-2">ICU / NICU</div>
                                                    </div>
                                                </div>

                                            <!-- Tarik data tabel dari database -->
                                            <div class="stat-digit text-white">
                                            <?php
                                                echo "<p>$totalIcu</p>";
                                            ?>
                                            </div>

                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-danger w-25" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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


    <?php include 'include/js.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
    window.onload = function() {
                    var dataPoints1 = [
                        <?php
                        foreach ($dataPemasukanPerBulan as $namaBulan => $total) {
                            echo "{ label: '$namaBulan', y: $total },";
                        }
                        ?>
                    ];

                    var dataPoints2 = [
                        <?php
                        foreach ($dataDepositPerBulan as $namaBulan1 => $total1) {
                            echo "{ label: '$namaBulan1', y: $total1 },";
                        }
                        ?>
                    ];

                    var chart1 = new CanvasJS.Chart("chartContainer1", {
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
                            dataPoints: dataPoints1
                        }]
                    });
                    chart1.render();

                    var chart2 = new CanvasJS.Chart("chartContainer2", {
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
                            dataPoints: dataPoints2
                        }]
                    });
                    chart2.render();
                }
            </script>
</body>

</html>