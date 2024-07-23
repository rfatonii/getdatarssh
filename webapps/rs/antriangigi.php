<?php
session_start();
require_once('../conf/conf.php');
$konektor = bukakoneksi();

$poliklinikQuery = "SELECT * FROM poliklinik WHERE kd_poli='u0010'";
$dokterQuery = "SELECT * FROM dokter WHERE kd_dokter IN ('dr0021','dr0022','dr0023','dr0024','dr0026','dr0027','dr0028','dr0031')";
$poliklinikResult = mysqli_query($konektor, $poliklinikQuery);
$dokterResult = mysqli_query($konektor, $dokterQuery);
$poliklinikData = mysqli_fetch_all($poliklinikResult, MYSQLI_ASSOC);
$dokterData = mysqli_fetch_all($dokterResult, MYSQLI_ASSOC);

$poliklinikSelected = '';
$dokterSelected = '';
if (isset($_POST['search'])) {
    $poliklinikSelected = $_POST['poliklinik'];
    $dokterSelected = $_POST['dokter'];
}

$query = "SELECT reg_periksa.no_reg, reg_periksa.no_rkm_medis, pasien.nm_pasien, reg_periksa.no_rawat, dokter.nm_dokter, reg_periksa.jam_reg, reg_periksa.stts 
          FROM reg_periksa 
          INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter 
          INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis 
          INNER JOIN poliklinik ON reg_periksa.kd_poli = poliklinik.kd_poli 
          WHERE DATE(reg_periksa.tgl_registrasi) = CURDATE()
          AND poliklinik.nm_poli IS NOT NULL
          AND dokter.nm_dokter IS NOT NULL";

if (!empty($poliklinikSelected)) {
    $query .= " AND poliklinik.kd_poli = '$poliklinikSelected'";
}
if (!empty($dokterSelected)) {
    $query .= " AND dokter.kd_dokter = '$dokterSelected'";
}

$query .= " AND reg_periksa.stts = 'Belum' 
           ORDER BY reg_periksa.no_reg ASC 
           LIMIT 20";

$result = mysqli_query($konektor, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Poliklinik Gigi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <style>
        .navbar {
            position: fixed;
            top: 0; 
            width: 100%; 
            z-index: 1000; 
        }

        body {
            background-size: auto;
            font-family: 'Poppins', sans-serif;
        }
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            z-index: 10;
            background-color: #4cda9f;
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif; color: black;">
    <nav class="navbar bg-white p-3 shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/logoRSSH1.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-top" style="margin-top: -7px">
                <span>Antrian Poli Gigi</span>
            </a>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar4-range" viewBox="0 0 16 16" style="margin-top: -4px; margin-left: 9px;">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                    <path d="M9 7.5a.5.5 0 0 1 .5-.5H15v2H9.5a.5.5 0 0 1-.5-.5v-1zm-2 3v1a.5.5 0 0 1-.5.5H1v-2h5.5a.5.5 0 0 1 .5.5z"/>
                </svg>
                <span id="tanggal-waktu" style="margin-right: 20px;"></span>
            </div>
        </div>
    </nav>

    <div class="container mb-4" style="margin-top: 6%;">
        <div class="row">
            <div class="col-12">
                <div class="card p-4 shadow" style="background-color: #4cda9f; color: white;">
                    <table style="font-size: 18px;">
                        <tbody>
                            <tr>
                                <td width="250px">Panggilan Pasien</td>
                                <td>:</td>
                                <td><span id="nama-pasien"></span></td>
                            </tr>
                            <tr>
                                <td>No. Antrian</td>
                                <td>:</td>
                                <td><span id="no-rawat"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col">
                <form method="post" action="">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-3">
                            <select class="form-select" name="poliklinik">
                                <option value="">Pilih Poliklinik</option>
                                <?php
                                usort($poliklinikData, function($a, $b) {
                                    return strcmp($a['nm_poli'], $b['nm_poli']);
                                });

                                foreach ($poliklinikData as $poliklinik) {
                                    $selected = ($poliklinik['kd_poli'] == $poliklinikSelected) ? 'selected' : '';
                                    echo '<option value="' . $poliklinik['kd_poli'] . '" ' . $selected . '>' . $poliklinik['nm_poli'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-3">
                            <select class="form-select" name="dokter">
                                <option value="">Pilih Dokter</option>
                                <?php
                                foreach ($dokterData as $dokter) {
                                    $selected = ($dokter['kd_dokter'] == $dokterSelected) ? 'selected' : '';
                                    echo '<option value="' . $dokter['kd_dokter'] . '" ' . $selected . '>' . $dokter['nm_dokter'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-1">
                            <input type="submit" class="btn btn-outline-primary" name="search" value="Cari">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
if ($konektor && $result && mysqli_num_rows($result) > 0) {
    echo '<div class="container">';
    echo '<table class="table">';
    echo '<thead><tr><th>No. Rawat</th><th>Nama Pasien</th><th class="text-center">No. Antrian</th><th>Nama Dokter</th><th></th></tr></thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['no_rkm_medis'] . '</td>';
        echo '<td>' . $row['nm_pasien'] . '</td>';
        echo '<td class="text-center">' . $row['no_reg'] . '</td>';
        echo '<td>' . $row['nm_dokter'] . '</td>';
        echo '<td>';
        if ($row['stts'] === 'Belum') {
            echo '<form method="post" action="tampilan.php">';
            echo '<input type="hidden" name="no_reg" value="' . $row['no_reg'] . '">';
            echo '<audio src="path/audio/Airport_Bell.mp3" preload="auto" id="Airport_Bell"></audio>';
            echo '<audio src="path/audio/' . $row['nm_dokter'] . '.mp3" preload="auto" id="poliAudio' . $row['nm_dokter'] . '"></audio>';
            echo '<audio src="path/audio/katakata.mp3" preload="auto" id="kataKataAudio"></audio>';
            echo '<audio src="path/audio/' . $row['no_reg'] . '.mp3" preload="auto" id="registrasiAudio' . $row['no_reg'] . '"></audio>';
            echo '<button type="button" name="panggil" class="btn btn-outline-primary tampilkan-btn" onclick="playPoli(this);" data-nomor-registrasi="' . $row['no_reg'] . '" data-nama-dokter="' . $row['nm_dokter'] . '">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-down" viewBox="0 0 16 16">
                      <path d="M9 4a.5.5 0 0 0-.812-.39L5.825 5.5H3.5A.5.5 0 0 0 3 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 9 12zM6.312 6.39 8 5.04v5.92L6.312 9.61A.5.5 0 0 0 6 9.5H4v-3h2a.5.5 0 0 0 .312-.11"/>
                      <path d="M12.025 8a4.5 4.5 0 0 1-1.318 3.182L10 10.475A3.5 3.5 0 0 0 11.025 8 3.5 3.5 0 0 0 10 5.525l.707-.707A4.5 4.5 0 0 1 12.025 8"/>
                  </svg>
                  </button>';
            echo '</form>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="alert alert-info">Tidak ada data antrian untuk poliklinik atau dokter yang dipilih.</div>';
}
?>

    <div class="fixed-bottom">
        <footer class="footer w-100 p-3">
            <marquee scrollamount="5" behavior="scroll">
                <p class="text-white" style="font-size: 18px;">
                    Informasi penting: Jika ada keluhan atau pertanyaan, silakan menghubungi petugas kami.
                </p>
            </marquee>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateTanggalWaktu() {
                var currentDateTime = new Date();
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                var formattedDateTime = currentDateTime.toLocaleDateString('id-ID', options);
                document.getElementById('tanggal-waktu').textContent = formattedDateTime;
            }

            setInterval(updateTanggalWaktu, 1000);
            updateTanggalWaktu();
        });
    </script>
</body>
</html>
