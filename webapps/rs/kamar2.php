
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

$query = "SELECT * FROM bangsal WHERE status='1' AND kd_bangsal IN (SELECT kd_bangsal FROM kamar) order by nm_bangsal asc";
$result = mysqli_query($konektor, $query);

?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif; color: black;">

    <?php include 'include/navbar.php'; ?>

    <div class="container">
       <div class="row m-auto">
        <h3 class='text-center mb-3'>Informasi Ketersediaan Tempat Tidur <br> RS. Syarif Hidayatullah</h3>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $ruangan = $row['nm_bangsal'];

                // Ketersediaan Total Bed
                $queryTotalBed = "SELECT COUNT(kd_bangsal) as total_bed FROM kamar WHERE kamar.statusdata='1' and kd_bangsal='".$row['kd_bangsal']."' ";
                $resultTotalBed = mysqli_query($konektor, $queryTotalBed);
                $dataTotalBed = mysqli_fetch_assoc($resultTotalBed);
                $totalBed = $dataTotalBed['total_bed'];
    
                // Bed Terisi
                $queryTotalBedTerisi = "SELECT COUNT(kd_bangsal) as total_bed_isi FROM kamar WHERE kamar.statusdata='1' and kd_bangsal='".$row['kd_bangsal']."' AND status='ISI' ";
                $resultTotalBedTerisi = mysqli_query($konektor, $queryTotalBedTerisi);
                $dataTotalBedTerisi = mysqli_fetch_assoc($resultTotalBedTerisi);
                $totalBedTerisi = $dataTotalBedTerisi['total_bed_isi'];

                // Sisa Bed
                $queryTotalBedKosong = "SELECT COUNT(kd_bangsal) as total_bed_kosong FROM kamar WHERE kamar.statusdata='1' and kd_bangsal='".$row['kd_bangsal']."' AND status='KOSONG' ";
                $resultTotalBedKosong = mysqli_query($konektor, $queryTotalBedKosong);
                $dataTotalBedKosong = mysqli_fetch_assoc($resultTotalBedKosong);
                $totalBedKosong = $dataTotalBedKosong['total_bed_kosong'];

                echo '<div class="col-md-4 col-sm-12">
                        <div class="card shadow mx-auto" style="width: 20rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center fw-bold">'.$ruangan.'</h5>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <p>Total</p>
                                        <span>'.$totalBed.'</span>
                                    </div>
                                    <div class="col-4" style="color:#FF0000">
                                        <p>Terisi</p>
                                        <span>'.$totalBedTerisi.'</span>
                                    </div>
                                    <div class="col-4" style="color:#00CC66;">
                                        <p>Tersedia</p>
                                        <span>'.$totalBedKosong.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            ?>

       </div>          
    </div>


    <?php include 'include/script.php'; ?>
</body>

</html>

