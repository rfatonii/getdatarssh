
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
                        <div class="col-4">
                            <p class="text-center" style="padding: 14px 0 0 0">NAMA DOKTER</p>
                        </div>
                        <div class="col-4 total-bed">
                            <p class="text-center" style="padding: 14px 0 0 0;">POLIKLINIK</p>
                        </div>
                        <div class="col-2 blank-bed">
                            <p class="text-center" style="padding: 14px 0 0 0;">JAM MULAI</p>
                        </div>
                        <div class="col-2 filled-bed">
                            <p class="text-center" style="padding: 14px 0 0 0;">JAM SELESAI</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php  
            $hari=getOne("select DAYNAME(current_date())");
            $namahari="";
            if($hari=="Sunday"){
                $namahari="AKHAD";
            }else if($hari=="Monday"){
                $namahari="SENIN";
            }else if($hari=="Tuesday"){
                $namahari="SELASA";
            }else if($hari=="Wednesday"){
                $namahari="RABU";
            }else if($hari=="Thursday"){
                $namahari="KAMIS";
            }else if($hari=="Friday"){
                $namahari="JUMAT";
            }else if($hari=="Saturday"){
                $namahari="SABTU";
            }
            $_sql="Select dokter.nm_dokter,poliklinik.nm_poli,jadwal.jam_mulai,jadwal.jam_selesai 
                    from jadwal inner join dokter inner join poliklinik on dokter.kd_dokter=jadwal.kd_dokter 
                    and jadwal.kd_poli=poliklinik.kd_poli where jadwal.hari_kerja='$namahari'and poliklinik.kd_poli not in('u0023','u0049','u0059','U0063','U0057','U0050','U0059','u0023','U0037','u0049','U0053','RO','FIS','LAB','ADM','U0062','U0054','U0056','SHM','U00TE','-','SKS','U0014','RPD','U0048') order by poliklinik.nm_poli;" ;  
            $hasil=mysqli_query($konektor,$_sql);

            while ($data = mysqli_fetch_array ($hasil)){
                echo '<div class="col-12">';
                echo '<div class="card room shadow mb-2">';
                echo '<div class="row">';
                echo '<div class="col-4 room-name">';
                echo '<p class="text-start" style="padding: 14px 0 0 30px">'.$data['nm_dokter'].'</p>';
                echo '</div>';
                echo '<div class="col-4 total-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0;">'.$data['nm_poli'].'</p>';
                echo '</div>';
                echo '<div class="col-2 blank-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0;">'.$data['jam_mulai'].'</p>';
                echo '</div>';
                echo '<div class="col-2 filled-bed">';
                echo '<p class="text-center" style="padding: 14px 0 0 0;">'.$data['jam_selesai'].'</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
	    ?>


            <!-- <div class="col-12">
                <div class="card shadow mb-2">
                    <div class="row">
                        <div class="col-4 room-name">
                            <p class="text-start" style="padding: 14px 0 0 30px">REHABILITASI MEDIK</p>
                        </div>
                        <div class="col-4 total-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">MOERDJAJATI</p>
                        </div>
                        <div class="col-2 blank-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">17.50</p>
                        </div>
                        <div class="col-2 filled-bed">
                            <p class="text-center" style="padding: 14px 0 0 0">19.50</p>
                        </div>
                    </div>
                </div>
            </div> -->

       </div>
    </div>


    <?php include 'include/script.php'; ?>
</body>

</html>