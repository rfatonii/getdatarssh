<?php
session_start();
require_once('../../conf/conf.php');

$konektor = bukakoneksi();
if ($konektor) {

}

if ($konektor->connect_error) {
    die("Koneksi gagal: " . $konektor->connect_error);
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

$dataQuery = "SELECT
reg_periksa.no_rawat as no_rawat,
pasien.no_rkm_medis as no_rekam_medis,
pasien.nm_pasien as nama_pasien,
reg_periksa.umurdaftar as umur_daftar,
reg_periksa.sttsumur as status_umur,
pasien.jk as jenis_kelamin,
pasien.tgl_lahir as tanggal_lahir,
catatan_keperawatan_ranap.tanggal as tanggal,
catatan_keperawatan_ranap.jam as jam,
catatan_keperawatan_ranap.uraian as uraian,
catatan_keperawatan_ranap.nip as nip,
petugas.nama as nama_petugas
from catatan_keperawatan_ranap inner join reg_periksa 
on catatan_keperawatan_ranap.no_rawat=reg_periksa.no_rawat 
inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
inner join petugas on catatan_keperawatan_ranap.nip=petugas.nip where 
catatan_keperawatan_ranap.tanggal between '$start_date' and '$end_date' order by catatan_keperawatan_ranap.tanggal,catatan_keperawatan_ranap.jam";


// Eksekusi query
$queryDataTabel = mysqli_query($konektor, $dataQuery);

if (!$queryDataTabel) {
    // Query gagal, cetak pesan kesalahan
    die("Error: " . mysqli_error($konektor));
}
?>




<!DOCTYPE html>
<html lang="en">

    <?php include '../include/header.php'; ?>

    <body style="font-family: 'Poppins', sans-serif;color: black; background-image: url('../images/rsshlogin.jpg');background-size:cover;">
        <div class="container" style="margin-top:11em;">
            <div class="row justify-content-center">
                <div class="col-4 bg-white p-5 rounded-start rounded-4">
                    <div class="card border-0 p-3">
                        <form action="">
                            <div class="mb-4">
                                <label for="exampleInputEmail1" class="form-label">ID User</label>
                                <input type="email" class="form-control rounded-pill" id="exampleInputEmail1" aria-describedby="emailHelp">        
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Password</label>
                                <input type="email" class="form-control rounded-pill" id="exampleInputEmail1" aria-describedby="emailHelp">
                                <div id="emailHelp" class="form-text">Jika lupa ID/pass, Silahkan konfirmasi ke Unit IT.</div>
                            </div>
                            <input type="submit" class="btn btn-login border-success border-3 text-center rounded-pill" value="Login" style="width:70%;color:#17D777;">
                        </form>
                    </div>
                </div>
                <div class="col-4 rounded-end rounded-4" style="background-color:#17D777;"><img src="../images/cyberlogin1.png" class="img-fluid" style="width:30em;" alt=""></div>
            </div>
        </div>
        <?php include '../include/script.php'; ?>
    </body>
</html>