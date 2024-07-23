<?php
session_start();
require_once('../conf/conf.php');
require_once('check_session.php');

$konektor = bukakoneksi();
if ($konektor) {

}

if ($konektor->connect_error) {
    die("Koneksi gagal: " . $konektor->connect_error);
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

// $dataQuery = "SELECT
// periksa_radiologi.no_rawat as no_rawat,
// reg_periksa.no_rkm_medis as no_rekam_medis,
// pasien.nm_pasien as nama_pasien, 
// periksa_radiologi.kd_jenis_prw as kode_jenis_rawat,
// jns_perawatan_radiologi.nm_perawatan as nama_perawatan, 
// periksa_radiologi.kd_dokter as kode_dokter,
// dokter.nm_dokter as nama_dokter,
// periksa_radiologi.nip as nip,
// petugas.nama as nama_petugas,
// periksa_radiologi.dokter_perujuk as dokter_perujuk,
// perujuk.nm_dokter as dokter_rujuk,
// periksa_radiologi.tgl_periksa as tanggal_periksa,
// periksa_radiologi.jam as jam,
// penjab.png_jawab as penanggung_jawab,
// periksa_radiologi.bagian_rs as bagian_rs,
// periksa_radiologi.bhp as bhp,
// periksa_radiologi.tarif_perujuk as tarif_perujuk,
// periksa_radiologi.tarif_tindakan_dokter as tindakan_dokter,
// periksa_radiologi.tarif_tindakan_petugas as tindakan_petugas,
// periksa_radiologi.kso as kso,
// periksa_radiologi.menejemen as manejemen,
// periksa_radiologi.biaya as total,
// if(periksa_radiologi.status='Ralan',(select nm_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
// (select bangsal.nm_bangsal from kamar_inap 
// inner join kamar 
// inner join bangsal on kamar_inap.kd_kamar=kamar.kd_kamar 
// and kamar.kd_bangsal=bangsal.kd_bangsal where kamar_inap.no_rawat=periksa_radiologi.no_rawat limit 1 )) as ruangan 
// from periksa_radiologi inner join reg_periksa on periksa_radiologi.no_rawat=reg_periksa.no_rawat 
// inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
// inner join dokter on periksa_radiologi.kd_dokter=dokter.kd_dokter 
// inner join dokter as perujuk on periksa_radiologi.dokter_perujuk=perujuk.kd_dokter 
// inner join petugas on periksa_radiologi.nip=petugas.nip 
// inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
// inner join jns_perawatan_radiologi on periksa_radiologi.kd_jenis_prw=jns_perawatan_radiologi.kd_jenis_prw
// where periksa_radiologi.tgl_periksa BETWEEN '$start_date' AND '$end_date' ORDER BY periksa_radiologi.tgl_periksa asc, periksa_radiologi.jam asc";
$dataQuery="SELECT 
periksa_lab.no_rawat as no_rawat,
reg_periksa.no_rkm_medis as no_rekam_medis,
pasien.nm_pasien as nama_pasien, 
periksa_lab.kd_jenis_prw kode_jenis_rawat,
jns_perawatan_lab.nm_perawatan as nama_perawatan, 
periksa_lab.kd_dokter as kode_dokter,
dokter.nm_dokter as nama_dokter,
periksa_lab.nip as nip,
petugas.nama as nama_petugas,
periksa_lab.dokter_perujuk as dokter_perujuk,
perujuk.nm_dokter as dokter_rujuk,
periksa_lab.tgl_periksa tanggal_periksa,
periksa_lab.jam,penjab.png_jawab as penanggung_jawab,
periksa_lab.bagian_rs as bagian_rs,
periksa_lab.bhp as bhp,
periksa_lab.tarif_perujuk as tarif_perujuk,
periksa_lab.tarif_tindakan_dokter tindakan_dokter,
periksa_lab.tarif_tindakan_petugas as tindakan_petugas,
periksa_lab.kso as kso,
periksa_lab.menejemen as manejemen,
periksa_lab.biaya as total,
if(periksa_lab.status='Ralan',(select nm_poli from poliklinik where poliklinik.kd_poli=reg_periksa.kd_poli),
(select bangsal.nm_bangsal from kamar_inap inner join kamar inner join bangsal on kamar_inap.kd_kamar=kamar.kd_kamar 
and kamar.kd_bangsal=bangsal.kd_bangsal where kamar_inap.no_rawat=periksa_lab.no_rawat limit 1 )) as ruangan 
from periksa_lab inner join reg_periksa on periksa_lab.no_rawat=reg_periksa.no_rawat 
inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
inner join dokter on periksa_lab.kd_dokter=dokter.kd_dokter 
inner join dokter as perujuk on periksa_lab.dokter_perujuk=perujuk.kd_dokter 
inner join petugas on periksa_lab.nip=petugas.nip 
inner join penjab on reg_periksa.kd_pj=penjab.kd_pj 
inner join jns_perawatan_lab on periksa_lab.kd_jenis_prw=jns_perawatan_lab.kd_jenis_prw 
where periksa_lab.tgl_periksa BETWEEN '$start_date' and '$end_date' order by periksa_lab.tgl_periksa";

// Eksekusi query
$queryDataTabel = mysqli_query($konektor, $dataQuery);

if (!$queryDataTabel) {
    // Query gagal, cetak pesan kesalahan
    die("Error: " . mysqli_error($konektor));
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'include/header.php'; ?>

<nav class="navbar navbar-expand-lg navbar-fixed-top navbar-light bg-light p-2 mb-4 shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="images/logoRSSH1.png" width="45" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse ml-4" id="navbarNav">
            </div>
        </div>
    </nav>

<body style="font-family: 'Poppins', sans-serif;color: black;">

    <div class="container-xl">
        <div class="row">
            <h2 class="mt-3">DETAIL TINDAKAN <span class="text-danger">LABORATORIUM</span></h2>
        </div>
        <br>
        <div class="row mt-1">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-8 p-4">
                        <form id="filter-form" class="form-inline" method="POST">
                            <label for="start_date" class="mr-2">Tanggal Mulai:</label> <br>
                            <input type="date" id="start_date" class="form-control mr-2 shadow" name="start_date" value="<?php echo $start_date; ?>">
                            <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                            <input type="date" id="end_date" class="form-control mr-2 shadow" name="end_date" value="<?php echo $end_date; ?>">
                            <button type="submit" class="btn btn-info shadow text-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                            </svg></button>
                        </form>             
                    </div>
<!-- 
                    <div class="col-3 text-end">
                        <h5 class="text-start text-dark">Total Keseluruhan : 
                                <//?php
                                // Hitung total
                                $totalMasuk = 0;
                                while ($row = mysqli_fetch_assoc($queryDataTabel)) {
                                    $totalMasuk += $row['total'];
                                }
                                mysqli_data_seek($queryDataTabel, 0); // Kembali ke awal hasil query
                                ?>
                                <div class="input-group mt-2">
                                  <span class="fst-italic fw-light me-2" style="font-size:12px">Rp: </span>  <input id="myCopy" class="form-control disabled shadow" aria-disabled="true" value="<?php echo $totalMasuk; ?>">
                                    <button class="btn btn-outline-info shadow" onclick="myFunction()" type="button" id="button-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                                        </svg>
                                    </button>
                                </div>
                        </h5>
                    </div> -->

                </div>

                <table class="table table-hover table-striped text-black-50 table-responsive" id="chuakss">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" class="bg-info text-light">NO</th>
                            <th scope="col" class="bg-info text-light">NO RAWAT</th>
                            <th scope="col" class="bg-info text-light">NO R.M</th>
                            <th scope="col" class="bg-info text-light">NAMA PASIEN</th>
                            <th scope="col" class="bg-info text-light">KODE PRAKTEK</th>
                            <th scope="col" class="bg-info text-light">PEMERIKSAAN</th>
                            <th scope="col" class="bg-info text-light">KODE PJ</th>
                            <th scope="col" class="bg-info text-light">DOKTER PJ LAB</th>
                            <th scope="col" class="bg-info text-light">NIP</th>
                            <th scope="col" class="bg-info text-light">PETUGAS LAB</th>
                            <th scope="col" class="bg-info text-light">KODE PERUJUK</th>
                            <th scope="col" class="bg-info text-light">DR. PERUJUK</th>
                            <th scope="col" class="bg-info text-light">TANGGGAL</th>
                            <th scope="col" class="bg-info text-light">JAM</th>
                            <th scope="col" class="bg-info text-light">CARA BAYAR</th>
                            <th scope="col" class="bg-info text-light">RUANGAN</th>
                            <th scope="col" class="bg-info text-light">JS. SARANA</th>
                            <th scope="col" class="bg-info text-light">PKT. BHP</th>
                            <th scope="col" class="bg-info text-light">JM.PJ.LAB</th>
                            <th scope="col" class="bg-info text-light">JM.PETUGAS</th>
                            <th scope="col" class="bg-info text-light">JM. PERUJUK</th>
                            <th scope="col" class="bg-info text-light">KSO</th>
                            <th scope="col" class="bg-info text-light">MANAJEMEN</th>
                            <th scope="col" class="bg-info text-light">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($queryDataTabel)){
                            $noRawat = $row['no_rawat'];
                            $noRekamMedis = $row['no_rekam_medis'];
                            $namaPasien = $row['nama_pasien'];
                            $kodeJenisRawat = $row['kode_jenis_rawat'];
                            $namaPerawatan = $row['nama_perawatan'];
                            $kodeDokter = $row['kode_dokter'];
                            $namaDokter = $row['nama_dokter'];
                            $nip = $row['nip'];
                            $namaPetugas = $row['nama_petugas'];
                            $dokterPerujuk = $row['dokter_perujuk'];
                            $namaDokterRujuk = $row['dokter_rujuk'];
                            $tanggalPeriksa = $row['tanggal_periksa'];
                            $jam = $row['jam'];
                            $penanggungJawab = $row['penanggung_jawab'];
                            $ruangan = $row['ruangan'];
                            $bagianrs = $row['bagian_rs'];
                            $bhp = $row['bhp'];
                            $tarifPerujuk = $row['tarif_perujuk'];
                            $tarifTindakanDokter = $row['tindakan_dokter'];
                            $tarifTindakanPetugas = $row['tindakan_petugas'];
                            $kso = $row['kso'];
                            $manejemen = $row['manejemen'];
                            $total = $row['total'];
                        ?>
                                <tr style="font-size:10px">
                                    <td scope="row"><?php echo $no; ?></td>
                                    <td><?php echo $noRawat; ?></td>
                                    <td><?php echo $noRekamMedis; ?></td>
                                    <td><?php echo $namaPasien; ?></td>
                                    <td><?php echo $kodeJenisRawat; ?></td>
                                    <td><?php echo $namaPerawatan; ?></td>
                                    <td><?php echo $kodeDokter; ?></td>
                                    <td><?php echo $namaDokter; ?></td>
                                    <td><?php echo $nip; ?></td>
                                    <td><?php echo $namaPetugas; ?></td>
                                    <td><?php echo $dokterPerujuk; ?></td>
                                    <td><?php echo $namaDokterRujuk; ?></td>
                                    <td><?php echo $tanggalPeriksa; ?></td>
                                    <td><?php echo $jam; ?></td>
                                    <td><?php echo $penanggungJawab; ?></td>
                                    <td><?php echo $ruangan; ?></td>
                                    <td><?php echo $bagianrs; ?></td>
                                    <td><?php echo $bhp; ?></td>
                                    <td><?php echo $tarifPerujuk; ?></td>
                                    <td><?php echo $tarifTindakanDokter; ?></td>
                                    <td><?php echo $tarifTindakanPetugas; ?></td>
                                    <td><?php echo $kso; ?></td>
                                    <td><?php echo $manejemen; ?></td>
                                    <td><?php echo $total; ?></td>
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
            {
                extend: 'excel',
                text: 'Export ke Excel',
                className: 'custom-button'
            },
            {
                extend: 'pdf',
                text: 'Export ke PDF',
                className: 'custom-button',
            }
            // {
            //     extend: 'csv',
            //     text: 'Export ke csv',
            //     className: 'custom-button',
            // }
        ]
    } );
});
</script>

<script>    
//Buat Atur Tanggal
$("#start_date").datepicker({ dateFormat: 'yy-mm-dd' });
$("#end_date").datepicker({ dateFormat: 'yy-mm-dd' });


//Copy Exstewnsinya yaa
function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myCopy");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // Tampilan Mobile

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert(copyText.value + " " +"Udah Kesalin Ya.... ");
}

</script>


</html>