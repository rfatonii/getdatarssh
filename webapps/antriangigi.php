<?php
session_start();
require_once('conf/conf.php');
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
    // if (isset($_POST['search'])) {
    //     $poliklinikSelected = $_POST['poliklinik'];
    //     $dokterSelected = $_POST['dokter'];
    // }

    $query = "SELECT reg_periksa.no_reg, reg_periksa.no_rkm_medis, pasien.nm_pasien, reg_periksa.no_rawat, dokter.nm_dokter, reg_periksa.jam_reg, reg_periksa.stts 
              FROM reg_periksa 
              INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter 
              INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis 
              INNER JOIN poliklinik ON reg_periksa.kd_poli = poliklinik.kd_poli 
              WHERE DATE(reg_periksa.tgl_registrasi) = CURDATE()
              AND poliklinik.nm_poli IS NOT NULL
              AND dokter.nm_dokter IS NOT NULL";

    // if (!empty($poliklinikSelected)) {
    //     $query .= " AND poliklinik.kd_poli = '$poliklinikSelected'";
    // }
    // if (!empty($dokterSelected)) { 
    //     $query .= " AND dokter.kd_dokter = '$dokterSelected'";
    // }

    $query .= " AND reg_periksa.stts = 'Belum' 
               ORDER BY reg_periksa.jam_reg ASC 
               LIMIT 20";

    $result = mysqli_query($konektor, $query);
// }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <style>
      body {
        background-image:url('images/Logo_rssh.jpg');
        background-size:cover;
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
  <body>
    <nav class="navbar p-3 shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            <img src="images/logoRSSH1.png" alt="Logo" width="37" height="37" class="d-inline-block align-text-top">
           <span style="">Antrian Poli Gigi</span> 
            </a>
            <p id="date" class="text-end bg-warning"></p>
        </div>
    </nav>

    <div class="container">
        <div class="card bg-none shadow p-3 mt-4">
        <div class="row">
        <div class="col">
            <form method="post" action="">
                <div class="row mt-3">
                    <div class="col">
                        <label for="poliklinik" class="form-label">Pilih Poliklinik:</label>
                        <select class="form-select" name="poliklinik">
                            <option value="">Semua Poliklinik</option>
                            <?php
                            foreach ($poliklinikData as $poliklinik) {
                                $selected = ($poliklinik['kd_poli'] == $poliklinikSelected) ? 'selected' : '';
                               echo '<option value="' . $poliklinik['kd_poli'] . '" ' . $selected . '>' . $poliklinik['nm_poli'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="dokter" class="form-label">Pilih Dokter:</label>
                        <select class="form-select" name="dokter">
                            <option value="">Semua Dokter</option>
                            <?php
                           foreach ($dokterData as $dokter) {
                               $selected = ($dokter['kd_dokter'] == $dokterSelected) ? 'selected' : '';
                               echo '<option value="' . $dokter['kd_dokter'] . '" ' . $selected . '>' . $dokter['nm_dokter'] . '</option>';
                           }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <input type="submit" class="btn btn-primary" name="search" value="Cari">
                </div>
            </form>
        </div>
    </div>
    <div class="col">
        <?php
        if ($konektor && $result && mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>No. Registrasi</th><th>No. Rekam Medis</th><th>Nama Pasien</th><th>No. Rawat</th><th>Nama Dokter</th><th>Jam Registrasi</th><th>Status</th><th></th></tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['no_reg'] . '</td>';
                echo '<td>' . $row['no_rkm_medis'] . '</td>';
                echo '<td>' . $row['nm_pasien'] . '</td>';
                echo '<td>' . $row['no_rawat'] . '</td>';
                echo '<td>' . $row['nm_dokter'] . '</td>';
                echo '<td>' . $row['jam_reg'] . '</td>';
                echo '<td>' . $row['stts'] . '</td>';
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
    </div>
</div>
            <!-- <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">No Registrasi</th>
                <th scope="col">Nama Pasien</th>
                <th scope="col">Dokter</th>
                <th scope="col">No. Antrian</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <th scope="row">2023/08/08/000022</th>
                <td>Markus</td>
                <td>Ferra dr</td>
                <td>002</td>
                <td><button class="btn btn-primary">panggil</button></td>
                </tr>
                <tr>
                <th scope="row">2023/08/08/000022</th>
                <td>Markus</td>
                <td>Ferra dr</td>
                <td>002</td>
                <td><button class="btn btn-primary">panggil</button></td>
                </tr>
                <tr>
                <th scope="row">2023/08/08/000022</th>
                <td>Markus</td>
                <td>Ferra dr</td>
                <td>002</td>
                <td><button class="btn btn-primary">panggil</button></td>
                </tr>
            </tbody>
            </table> -->
        </div>
    </div>
    



    <footer>
      <div class="container">
        <div class="row">
          <div class="col-12 footer m-auto text-light">
            <div class="row p-2">
              <div class="col-1"><span>RS SYAHID</span></div>
              <div class="col-9">
                <marquee behavior="" direction=""><span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, numquam blanditiis!</span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem ab officia in consectetur quam amet aliquid nam repellendus officiis sed eaque voluptates magnam mollitia, dolore et dolores perspiciatis fugit eveniet!<span></span></marquee>
              </div>
              <div class="col-2 text-end"> 
                <div id="DisplayClock" class="clock" onload="showTime()"></div>
              </div>
            </div>
          </div>   
        </div>
      </div>
    </footer>
    
    <script>
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

    function updateDate() {
    const daysOfWeek = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    const currentDate = new Date();
    const dayOfWeek = daysOfWeek[currentDate.getDay()];
    const dayOfMonth = currentDate.getDate();
    const month = months[currentDate.getMonth()];
    const year = currentDate.getFullYear();
    
    const dateElement = document.getElementById("date");
    dateElement.innerHTML = `<span>${dayOfWeek},</span><br><span>${dayOfMonth} ${month} ${year}</span>`;
  }
  
  // Panggil fungsi updateDate saat halaman dimuat
  window.onload = function() {
    updateDate();
  };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
  </body>
</html>