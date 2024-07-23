<?php
session_start();
require_once('../conf/conf.php');

$konektor = bukakoneksi();

$hari = getOne("select DAYNAME(current_date())");
$namahari = "";
if ($hari == "Sunday") {
    $namahari = "AKHAD";
} else if ($hari == "Monday") {
    $namahari = "SENIN";
} else if ($hari == "Tuesday") {
    $namahari = "SELASA";
} else if ($hari == "Wednesday") {
    $namahari = "RABU";
} else if ($hari == "Thursday") {
    $namahari = "KAMIS";
} else if ($hari == "Friday") {
    $namahari = "JUMAT";
} else if ($hari == "Saturday") {
    $namahari = "SABTU";
}
$_sql = "Select dokter.nm_dokter,poliklinik.nm_poli,jadwal.jam_mulai,jadwal.jam_selesai 
        from jadwal inner join dokter inner join poliklinik on dokter.kd_dokter=jadwal.kd_dokter 
        and jadwal.kd_poli=poliklinik.kd_poli where jadwal.hari_kerja='$namahari'and poliklinik.kd_poli not in('u0023','u0049','u0059','U0063','U0057','U0050','U0059','u0023','U0037','u0049','U0053','RO','FIS','LAB','ADM','U0062','U0054','U0056','SHM','U00TE','-','SKS','U0014','RPD','U0048') order by poliklinik.nm_poli;";
$hasil = mysqli_query($konektor, $_sql);

// Menghitung total kartu dokter
$totalKartu = mysqli_num_rows($hasil);

// Jumlah kartu yang ingin ditampilkan pada setiap carousel-item
$kartuPerSlide = 6;

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>

<body style="font-family: 'Poppins', sans-serif; color: black;">

    <?//php include 'include/navbar.php'; ?>

    <div class="bg-today py-5">
        <div class="container container-header text-center mb-3">
            <p class="p-header">Jadwal Dokter RS Syarif Hidayatullah</p>
            <p class="p-header">Hari <span id="tanggal-waktu"></span></p>
        </div>



        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
            <div class="carousel-inner">

                <?php
                $item_count = 0;
                $kartu_count = 0;

                while ($data = mysqli_fetch_array($hasil)) {

                    if ($kartu_count % $kartuPerSlide == 0) {
                        echo '<div class="carousel-item ' . ($item_count == 0 ? 'active' : '') . '">';
                        echo '<div class="container">';
                        echo '<div class="row row-card">';
                    }



                    echo '<div class="card col-card col-sm-6 col-md-4 border">';
                    echo '<div class="user-card">';
                    echo '<div class="card-block">';
                    echo '<div class="row">';
                    echo '<div class="col-4">';
                    echo '<div class="user-image">';
                    echo '<img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="img-radius" alt="User-Profile-Image">';
                    echo '</div></div>';

                    echo '<div class="col-8">';
                    echo '<h6 class="f-w-600 m-t-25 m-b-10">' . $data['nm_dokter'] . '</h6>';
                    echo '<p class="text-muted">' . $data['nm_poli'] . '</p>';
                    echo '</div></div><hr>';

                    echo '<div class="row text-center">';
                    echo '<div class="col-6">';
                    echo '<p>Jam Mulai</p>';
                    echo '<span>' . substr($data['jam_mulai'], 0, 5) . '</span>';
                    echo '</div><div class="col-6">';
                    echo '<p>Jam Selesai</p>';
                    echo '<span>' . substr($data['jam_selesai'], 0, 5) . '</span>';
                    echo '</div></div></div></div></div>';

                    // Selesai item setiap 6 kartu
                    if ($kartu_count % $kartuPerSlide == $kartuPerSlide - 1 || $kartu_count == $totalKartu - 1) {
                        echo '</div></div></div>';
                        $item_count++;
                    }

                    $kartu_count++;
                }
                ?>

            </div>

            <!-- Tombol kontrol carousel -->
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button> -->
        </div>
    </div>

    <?php include 'include/script.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var carousel = new bootstrap.Carousel(document.getElementById('productCarousel'), {
            interval: 10000
        });

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
    </script>


</body>

</html>