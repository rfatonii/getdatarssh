

<!-- backupan code jadwaldokter3.php -->
<?php
session_start();
require_once('../conf/conf.php');

$konektor = bukakoneksi();

$query = "SELECT dokter.kd_dokter, dokter.nm_dokter, poliklinik.nm_poli
          FROM jadwal 
          INNER JOIN poliklinik ON jadwal.kd_poli = poliklinik.kd_poli
          INNER JOIN dokter ON jadwal.kd_dokter = dokter.kd_dokter 
          WHERE dokter.kd_dokter NOT IN ('Res','Leg','Opt','-','--','---','----','D0000114','rs0104','rs0105') and poliklinik.kd_poli not in('u0023','u0049','u0059')
          GROUP BY dokter.kd_dokter order by poliklinik.nm_poli asc;";
$result = mysqli_query($konektor, $query);

?>
<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php';?>
<body style="font-family: 'Poppins', sans-serif; color: black;">

    <?php include 'include/navbar.php'; ?>
    <?php
        $tampilPoli = "select * from poliklinik where poliklinik.kd_poli not in('u0023','u0049','u0059','U0063','U0057','U0050','U0059','u0023','u0049','U0053','RO','FIS','LAB','ADM','U0062','U0054','SHM','U00TE','-','SKS','U0014','RPD','U0048') order by poliklinik.nm_poli asc;";
        $queryPoli = mysqli_query($konektor, $tampilPoli);
    ?>

    <div class="carousel-item">
      <img src="..." class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>

    <div class="container-xl">
        <div class="row mb-4 justify-content-end">
            <div class="col-4">
                <select class="form-select" aria-label="Default select example">
                <option selected>Pilih Poli</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($queryPoli)) {
                        echo '<option value="">'.$row['nm_poli'].'</option>';
                    }
                    ?>
                    
                </select>
            </div>
        </div>
        
        <div class="row" id="card-container">
                <?php     
                while ($row = mysqli_fetch_assoc($result)){
                    $kd_dokter = $row['kd_dokter'];
                    $dokter = $row['nm_dokter'];
                    $poli = $row['nm_poli'];
                    ?>

                <div class="col-sm-6 col-md-4">
                    <div class="card user-card shadow">
                         
                        <div class="card-block">
                            <div class="row">
                                <div class="col-4">
                                    <div class="user-image">
                                        <img src="" class="img-radius" alt="User-Profile-Image">
                                    </div>
                                </div>

                                <div class="col-8">
                                    <h6 class="f-w-600 m-t-25 m-b-10"><?php echo $dokter; ?></h6>
                                    <p class="text-muted"><?php echo $poli; ?></p>
                                </div>
                            </div>
                            <hr>
                            <p class="text-start">
                                <a class="btn btn-success" data-bs-toggle="collapse" href="#collapseExample<?php echo $kd_dokter; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $kd_dokter; ?>">
                                    Jadwal Praktek
                                </a>
                            </p>
                            <div class="collapse" id="collapseExample<?php echo $kd_dokter; ?>">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">Hari</th>
                                            <th scope="col">Mulai</th>
                                            <th scope="col">Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $jadwalQuery = "SELECT hari_kerja, jam_mulai, jam_selesai FROM jadwal WHERE kd_dokter = '$kd_dokter'";
                                        $jadwalResult = mysqli_query($konektor, $jadwalQuery);

                                        while ($jadwalRow = mysqli_fetch_assoc($jadwalResult)) {
                                            $hariKerja = $jadwalRow['hari_kerja'];
                                            $mulai = $jadwalRow['jam_mulai'];
                                            $selesai = $jadwalRow['jam_selesai'];

                                            echo '<tr>
                                                    <td>' . $hariKerja . '</td>
                                                    <td>' . $mulai . '</td>
                                                    <td>' . $selesai . '</td>
                                                  </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php   
                }  
            ?>
        </div>
        
        <div class="row">
            <div class="col-4 mb-5">
                <button class="btn btn-outline-dark" id="show-more-btn">Tampilkan Lebih Banyak</button>
            </div>
        </div>

    </div>

    <?php include 'include/script.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var cardContainer = document.getElementById('card-container');
            var showMoreBtn = document.getElementById('show-more-btn');
            var cards = document.querySelectorAll('.user-card');

            var visibleCards = 6;
            var totalCards = cards.length;

            // Fungsi untuk menampilkan kartu sesuai dengan jumlah yang telah ditentukan
            function showCards() {
                for (var i = 0; i < totalCards; i++) {
                    if (i < visibleCards) {
                        cards[i].style.display = 'block';
                    } else {
                        cards[i].style.display = 'none';
                    }
                }
            }

            // Panggil fungsi pertama kali untuk menampilkan kartu awal
            showCards();

            // Tambahkan event listener untuk tombol "Tampilkan Lebih Banyak"
            showMoreBtn.addEventListener('click', function () {
                visibleCards += 9; // Menambahkan 9 kartu setiap kali tombol ditekan
                showCards();
            });
        });
    </script>

    
</body>

</html>