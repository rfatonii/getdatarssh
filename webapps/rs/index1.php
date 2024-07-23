<?php
session_start();
require_once('../conf/conf.php');

$konektor = bukakoneksi();

$_sql = "Select dokter.nm_dokter,poliklinik.nm_poli,jadwal.jam_mulai,jadwal.jam_selesai 
        from jadwal inner join dokter inner join poliklinik on dokter.kd_dokter=jadwal.kd_dokter 
        and jadwal.kd_poli=poliklinik.kd_poli where jadwal.hari_kerja=''and poliklinik.kd_poli not in('u0023','u0049','u0059','U0063','U0057','U0050','U0059','u0023','U0037','u0049','U0053','RO','FIS','LAB','ADM','U0062','U0054','U0056','SHM','U00TE','-','SKS','U0014','RPD','U0048') order by poliklinik.nm_poli;";
$hasil = mysqli_query($konektor, $_sql);

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>

<body style="font-family: 'Poppins', sans-serif; color: black;">

    <?//php include 'include/navbar.php'; ?>
    <div class="container">
        <h1  class="mt-3">GET DATA RSSH</h1>
        <div class="row">
            <div class="list-group">
                <a href="detailradiologi.php" class="list-group-item list-group-item-action list-group-item-success">DETAIL TINDAKAN RADIOLOGI</a>
                <a href="detaillab.php" class="list-group-item list-group-item-action list-group-item-success">DETAIL TINDAKAN LABORATORIUM</a>
                <!-- <a href="#" class="list-group-item list-group-item-action list-group-item-success">S.O.A.P.I.E</a> -->
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Coming Soon.....</a>
            </div>
        </div>
    </div>
    

    <?php include 'include/script.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>