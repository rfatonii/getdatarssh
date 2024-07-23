<?php
session_start();
require_once('../conf/conf.php');
$konektor = bukakoneksi();

// if ($konektor) {
//     if (isset($_POST['panggil'])) {
//         $no_reg = $_POST['no_reg'];
//         $update_query = "UPDATE reg_periksa SET stts = 'Sudah' WHERE no_reg = '$no_reg'";
//         mysqli_query($konektor, $update_query);
//     }

    $poliklinikQuery = "SELECT * FROM poliklinik";
    $dokterQuery = "SELECT * FROM dokter ORDER BY nm_dokter";
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
               ORDER BY reg_periksa.jam_reg ASC 
               LIMIT 20";

    $result = mysqli_query($konektor, $query);
// }
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
            /* background-image:url('images/Logo_rssh.jpg'); */
            background-size:auto;
            font-family: 'Poppins', sans-serif;
        }
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 0; 
            z-index: 10;
            background-color:#4cda9f;
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif;color: black;">
    <nav class="navbar bg-white p-3 shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="images/logoRSSH1.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-top" style="margin-top:-7px">
            <span>Antrian Poli Gigi</span>  
          </a>

            <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar4-range" viewBox="0 0 16 16" style="margin-top:-4px; margin-left:9px;">
              <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
              <path d="M9 7.5a.5.5 0 0 1 .5-.5H15v2H9.5a.5.5 0 0 1-.5-.5v-1zm-2 3v1a.5.5 0 0 1-.5.5H1v-2h5.5a.5.5 0 0 1 .5.5z"/>
            </svg>
            <span id="tanggal-waktu" style="margin-right:20px;"></span>
            </div>

        </div>
    </nav>

    <div class="container mb-4" style="margin-top:6%;">
        <div class="row">
            <div class="col-12">
                <div class="card p-4 shadow" style="background-color:#4cda9f; color: white;">
                    <table style="font-size:18px;">
                        <tbody>
                        <tr><td width="250px">Panggilan Pasien</td><td>:</td><td><span id="nama-pasien"></td></tr>
                        <tr><td>No Rawat</td><td>:</td><td><span id="no-rawat"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col">
                <form method="post" action="">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-3">
                            <!-- <label for="poliklinik" class="form-label">Pilih Poliklinik:</label> -->
                            <select class="form-select" name="poliklinik">
                            <option value="">Plih Poliklinik</option>
                                <?php
                                // Mengurutkan array berdasarkan nama poliklinik
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
                            <!-- <label for="dokter" class="form-label">Pilih Dokter:</label> -->
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
                            <input type="submit" class="btn btn-primary" name="search" value="Cari">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
        if ($konektor && $result && mysqli_num_rows($result) > 0) {
            echo '<table class="table">';
            echo '<thead><tr><th>No. Reg</th><th>Nama Pasien</th><th>No. Rawat</th><th>Nama Dokter</th><th></th></tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr">';
                echo '<td>' . $row['no_reg'] . '</td>';
                // echo '<td>' . $row['no_rkm_medis'] . '</td>';
                echo '<td>' . $row['nm_pasien'] . '</td>';
                echo '<td>' . $row['no_rawat'] . '</td>';
                // echo '<td>' . $row['nm_dokter'] . '</td>';
                // echo '<td>' . $row['jam_reg'] . '</td>';
                // echo '<td>' . $row['stts'] . '</td>';
                echo '<td>';
                if ($row['stts'] === 'Belum') {
                    echo '<form method="post" action="tampilan.php">';
                    echo '<input type="hidden" name="no_reg" value="' . $row['no_reg'] . '">';
                    echo '<audio src="path/audio/Airport_Bell.mp3" preload="auto" id="Airport_Bell"></audio>';
                    echo '<audio src="path/audio/' . $row['nm_dokter'] . '.mp3" preload="auto" id="poliAudio' . $row['nm_dokter'] . '"></audio>';
                    echo '<audio src="path/audio/katakata.mp3" preload="auto" id="kataKataAudio"></audio>';
                    echo '<audio src="path/audio/' . $row['no_reg'] . '.mp3" preload="auto" id="registrasiAudio' . $row['no_reg'] . '"></audio>';
                    echo '<button type="button" name="panggil" class="btn btn-primary tampilkan-btn" onclick="playPoli(this);" data-nomor-registrasi="' . $row['no_reg'] . '" data-nama-dokter="' . $row['nm_dokter'] . '">Panggil</button>';
                    echo '</form>';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="alert alert-info">Tidak ada data antrian untuk poliklinik atau dokter yang dipilih.</div>';
        }
        ?>
           

        <footer width='100'>
            <div class="container">
                <div class="row">
                <div class="col-12 footer m-auto text-light">
                    <div class="row p-2">
                    <div class="col-1"><span>RS SYAHID</span></div>
                    <div class="col-10">
                        <marquee behavior="" direction=""><span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, numquam blanditiis!</span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem ab officia in consectetur quam amet aliquid nam repellendus officiis sed eaque voluptates magnam mollitia, dolore et dolores perspiciatis fugit eveniet!<span></span></marquee>
                    </div>
                    <div class="col-1"> 
                        <div id="DisplayClock" class="clock" onload="showTime()"></div>
                    </div>
                    </div>
                </div>   
                </div>
            </div>
        </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
<script>
    function playPoli(button) {
        var nomorRegistrasi = button.getAttribute("data-nomor-registrasi");
        var namaDokter = button.getAttribute("data-nama-dokter");
        var Airport_Bell = document.getElementById("Airport_Bell");
        var poliAudio = document.getElementById("poliAudio" + namaDokter);
        var kataKataAudio = document.getElementById("kataKataAudio");
        var registrasiAudio = document.getElementById("registrasiAudio" + nomorRegistrasi);
        Airport_Bell.play();
        setTimeout(function () {
            poliAudio.play();
            setTimeout(function () {
                kataKataAudio.play();
                setTimeout(function () {
                    registrasiAudio.play();
                }, 2000); // Penundaan 2 detik sebelum memutar audio nomor registrasi pasien
            }, 3000); // Penundaan 3 detik sebelum memutar audio kata-kata
        }, 5000); // Penundaan 5 detik sebelum memutar audio poli
    }
</script>
<script>
    function autoReload() {
        setTimeout(function () {
            location.reload();
        }, 15000); // Reload setiap 15 detik (15000 milidetik)
    }
    <?php if (isset($_POST['panggil'])) { ?>
        autoReload(); // Panggil fungsi autoReload jika tombol "Panggil" ditekan
    <?php } ?>
</script>

<script>
    // MENAMPILKAN WAKTU
    function showTime(){
      var date = new Date();
      var h = date.getHours();
      var m = date.getMinutes();
      var s = date.getSeconds();
      var session = "AM";

      if(h == 0){
        h = 12;
      }
      if (h > 12) {
        h = h - 12 ;
        session = "PM";
      }

      h = (h<10) ? "0" + h : h;
      m = (m<10) ? "0" + m : m;
      s = (s<10) ? "0" + s : s;

      var time = h + ":" + m + ":" + s + " " + session;

      document.getElementById("DisplayClock").innerText = time;
      document.getElementById("DisplayClock").textContent = time;

      setTimeout(showTime, 1000);
    }

    showTime();

    // MENAMPILKAN TANGGAL & WAKTU
    function tampilkanTanggalWaktu() {
            const sekarang = new Date(); 

            
            const daftarHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            
            const tanggal = sekarang.getDate();
            const hari = daftarHari[sekarang.getDay()];
            const tahun = sekarang.getFullYear();

            
            const daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const bulan = daftarBulan[sekarang.getMonth()];

            
            const jam = sekarang.getHours();
            const menit = sekarang.getMinutes();
            const detik = sekarang.getSeconds();
            const ampm = jam >= 12 ? 'PM' : 'AM';

            // Tampilkan tanggal dan waktu di dalam elemen HTML dengan id 'tanggal-waktu'
            document.getElementById('tanggal-waktu').innerHTML = `${hari}, ${tanggal} ${bulan} ${tahun}`;
            // ${jam}:${menit}:${detik} ${ampm}
        }

        // Panggil fungsi tampilkanTanggalWaktu setiap detik
        setInterval(tampilkanTanggalWaktu, 1000);

        // Tampilkan tanggal dan waktu saat halaman pertama kali dimuat
        tampilkanTanggalWaktu();


        // TARIK DATA SAAT DI KLIK PANGGIL
        const tampilkanBtns = document.querySelectorAll('.tampilkan-btn');
        const nomorAntrianElement = document.getElementById('nama-pasien');
        const noRawat = document.getElementById('no-rawat')


        tampilkanBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const nama = row.querySelector('td:nth-child(2)').textContent;
                const norw = row.querySelector('td:nth-child(3)').textContent;
                const poli = this.getAttribute('data-poli');
                nomorAntrianElement.innerText = nama;
                noRawat.innerText = norw;
            });
        });

        $(document).ready(function() {
        $('#cuka').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'excel','pdf'
            ]
        } );
    } )
</script>
</body>
</html>
