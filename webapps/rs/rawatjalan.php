<?php
session_start();
require_once('../conf/conf.php');
require_once('check_session.php');

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

$queryDataTabel = mysqli_query($konektor, "SELECT
            pemeriksaan_ralan.nip as nip,
            pegawai.nama as nama_pegawai,
            pemeriksaan_ralan.tgl_perawatan as tanggal_rawat,
            pemeriksaan_ralan.jam_rawat as jam_rawat,
            pasien.nm_pasien as nama_pasien,
            reg_periksa.no_rawat as no_rawat,
            pemeriksaan_ralan.keluhan as subjek,
            pemeriksaan_ralan.pemeriksaan as objek,
            pemeriksaan_ralan.rtl as plan,
            pemeriksaan_ralan.penilaian as asesmen,
            pemeriksaan_ralan.instruksi as instruksi,
            pemeriksaan_ralan.evaluasi as evaluasi
            FROM
            pemeriksaan_ralan
            INNER JOIN
            pegawai ON pemeriksaan_ralan.nip = pegawai.nik
            INNER JOIN
            reg_periksa ON pemeriksaan_ralan.no_rawat = reg_periksa.no_rawat
            INNER JOIN
            pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis WHERE reg_periksa.stts = 'Sudah' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '2024-05-02' AND '2024-05-02'");
?>


<!DOCTYPE html>
<html lang="en">
    <?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif;color: black;">

<!-- include navbar -->
    <//?php include 'include/navbar.php'; ?>

    <div class="container-xxl">
        <div class="row mb-3">
            <h2>Data SOAPIE Pasien</h2>
        </div>

        <!-- <div class="col-lg-12 mb-3">
                <form action="" method="post" class="form-inline">
                    <div class="form-group">
                        <label for="tanggal_pertama">Tanggal Pertama:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_pertama" name="tanggal_pertama"
                            value="<//?php echo $tanggalPertama; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kedua">Tanggal Kedua:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_kedua" name="tanggal_kedua"
                            value="<//?php echo $tanggalKedua; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="search">Cari</button>
                </form>
            </div>        -->
        <br>
        <div class="row mt-3">
            <div class="col-lg-12">
                <table class="table table-light table-hover text-black-50 table-responsive" id="chuakss">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">NIP</th>
                            <th scope="col">NAMA USER</th>
                            <th scope="col">TANGGAL RAWAT</th>
                            <th scope="col">JAM</th>
                            <th scope="col">NAMA PASIEN</th>
                            <th scope="col">NO RAWAT</th>
                            <th scope="col">SUBJEK</th>
                            <th scope="col">OBJEK</th>
                            <th scope="col">ASESMEN</th>
                            <th scope="col">PLAN</th>
                            <th scope="col">INTRUKSI</th>
                            <th scope="col">EVALUASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($queryDataTabel)){
                                $nip = $row['nip'];
                                $namaPegawai = $row['nama_pegawai'];
                                $tanggalRawat = $row['tanggal_rawat'];
                                $jamRawat = $row['jam_rawat'];
                                $namaPasien = $row['nama_pasien'];
                                $norawat = $row['no_rawat'];
                                $subjek = $row['subjek'];
                                $objek = $row['objek'];
                                $plan = $row['plan'];
                                $asesmen = $row['asesmen'];
                                $instruksi = $row['instruksi'];
                                $evaluasi = $row['evaluasi'];
                        ?>
                                <tr style="font-size:10px">
                                    <td scope="row"><?php echo $nip; ?></td>
                                    <td><?php echo $namaPegawai; ?></td>
                                    <td><?php echo $tanggalRawat; ?></td>
                                    <td><?php echo $jamRawat; ?></td>
                                    <td><?php echo $namaPasien; ?></td>
                                    <td><?php echo $norawat; ?></td>
                                    <td><?php echo $subjek; ?></td>
                                    <td><?php echo $objek; ?></td>
                                    <td><?php echo $plan; ?></td>
                                    <td><?php echo $asesmen; ?></td>
                                    <td><?php echo $instruksi; ?></td>
                                    <td><?php echo $evaluasi; ?></td>
                                </tr>
                        <?php
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