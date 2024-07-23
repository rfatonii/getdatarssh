
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

$query = "SELECT * FROM bangsal WHERE status='1' AND kd_bangsal IN (SELECT kd_bangsal FROM kamar)";
$result = mysqli_query($konektor, $query);

?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif; color: black;">

    <?php include 'include/navbar.php'; ?>

    <div class="container">
       <div class="row">
            <div class="col-12">
                <div class="card mb-3 bg-grey">
                    <div class="row fw-bold">
                        <div class="col-6">
                            <p class="text-center" style="padding: 14px 0 0 0">KAMAR</p>
                        </div>
                        <div class="col-2 total-bed">
                            <p class="text-center" style="padding: 14px 0 0 0; color:#00CC66;">TOTAL KAMAR</p>
                        </div>
                        <div class="col-2 blank-bed">
                            <p class="text-center" style="padding: 14px 0 0 0; color:#FF0000;">KAMAR TERISI</p>
                        </div>
                        <div class="col-2 filled-bed">
                            <p class="text-center" style="padding: 14px 0 0 0; color:#0066CC;">KAMAR KOSONG</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
           while ($row = mysqli_fetch_assoc($result)) {
            $ruangan = $row['nm_bangsal'];

            // Ketersediaan Total Bed
            $queryTotalBed = "SELECT COUNT(kd_bangsal) as total_bed FROM kamar WHERE kd_bangsal='".$row['kd_bangsal']."' ";
            $resultTotalBed = mysqli_query($konektor, $queryTotalBed);
            $dataTotalBed = mysqli_fetch_assoc($resultTotalBed);
            $totalBed = $dataTotalBed['total_bed'];

            // Bed Terisi
            $queryTotalBedTerisi = "SELECT COUNT(kd_bangsal) as total_bed_isi FROM kamar WHERE kd_bangsal='".$row['kd_bangsal']."' AND status='ISI' ";
            $resultTotalBedTerisi = mysqli_query($konektor, $queryTotalBedTerisi);
            $dataTotalBedTerisi = mysqli_fetch_assoc($resultTotalBedTerisi);
            $totalBedTerisi = $dataTotalBedTerisi['total_bed_isi'];

            // Sisa Bed
            $queryTotalBedKosong = "SELECT COUNT(kd_bangsal) as total_bed_kosong FROM kamar WHERE kd_bangsal='".$row['kd_bangsal']."' AND status='KOSONG' ";
            $resultTotalBedKosong = mysqli_query($konektor, $queryTotalBedKosong);
            $dataTotalBedKosong = mysqli_fetch_assoc($resultTotalBedKosong);
            $totalBedKosong = $dataTotalBedKosong['total_bed_kosong'];

                echo '<div class="col-12">';
                echo '<div class="card room shadow mb-2">';
                echo '<div class="row">';
                echo '<div class="col-6 room-name">';
                echo '<p class="text-start" style="padding: 14px 0 0 30px">'.$ruangan.'</p>';
                echo '</div>';
                echo '<div class="col-2 total-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0; color:#00CC66;">'.$totalBed.'</p>';
                echo '</div>';
                echo '<div class="col-2 blank-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0; color:#FF0000;">'.$totalBedTerisi.'</p>';
                echo '</div>';
                echo '<div class="col-2 filled-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0; color:#0066CC;">'.$totalBedKosong.'</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

            }
            ?>


            <!-- <div class="col-12">
                <div class="card shadow mb-2">
                    <div class="row">
                        <div class="col-6 room-name">
                            <p class="text-start" style="padding: 14px 0 0 30px">Hijir Ismail</p>
                        </div>
                        <div class="col-2 total-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">50</p>
                        </div>
                        <div class="col-2 blank-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">50</p>
                        </div>
                        <div class="col-2 filled-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">50</p>
                        </div>
                    </div>
                </div>
            </div> -->

       </div>
    </div>


    <?php include 'include/script.php'; ?>
</body>

</html>