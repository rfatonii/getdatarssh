<?php
session_start();
require_once('../conf/conf.php');

// Fungsi untuk mendapatkan daftar dokter
function getdokterList($konektor)
{
    $dokterBlacklist = array('0');
    $query = "SELECT DISTINCT dokter.nm_dokter FROM dokter WHERE dokter.status NOT IN ('" . implode("','", $dokterBlacklist) . "')";
    $result = mysqli_query($konektor, $query);
    $dokterList = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $dokterList[] = $row['nm_dokter'];
    }

    return $dokterList;
}
$konektor = bukakoneksi();
if ($konektor) {
    $konektor = bukakoneksi();

}

$dokterList = getdokterList($konektor);
if (isset($_POST['search1'])) {
    $tanggalPertama1 = $_POST['tanggal_pertama1'];
    $tanggalKedua1 = $_POST['tanggal_kedua1'];
    $selecteddokter = $_POST['dokter'];
    $_SESSION['tanggalPertama1'] = $tanggalPertama1;
    $_SESSION['tanggalKedua1'] = $tanggalKedua1;
    $_SESSION['selected '] = $selecteddokter;
} else {
    if (isset($_SESSION['tanggalPertama1'])) {
        $tanggalPertama1 = $_SESSION['tanggalPertama1'];
    } else {
        $tanggalPertama1 = date('Y-m-d');
    }
    if (isset($_SESSION['tanggalKedua1'])) {
        $tanggalKedua1 = $_SESSION['tanggalKedua1'];
    } else {
        $tanggalKedua1 = date('Y-m-d');
    }
    if (isset($_SESSION['selecteddokter'])) {
        $selecteddokter = $_SESSION['selecteddokter'];
    } else {
        $selecteddokter = '';
    }
}
$query = "SELECT reg_periksa.no_rawat AS 'No. Rawat', pasien.nm_pasien AS 'Nama Pasien', dokter.nm_dokter AS 'dokter', reg_periksa.stts AS 'Pemeriksaan Dokter',
    reg_periksa.status_bayar AS 'Keterangan'
    FROM reg_periksa
    INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
    INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
    WHERE dokter.status NOT IN ('0') AND reg_periksa.status_bayar = 'Sudah Bayar' AND reg_periksa.status_lanjut = 'Ralan'";

$query2 = "SELECT reg_periksa.no_rawat AS 'No. Rawat1', pasien.nm_pasien AS 'Nama Pasien1', dokter.nm_dokter AS 'dokter1', reg_periksa.stts AS 'Pemeriksaan Dokter1',
    reg_periksa.status_bayar AS 'Keterangan1'
    FROM reg_periksa
    INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
    INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
    WHERE dokter.status NOT IN ('0')    AND reg_periksa.status_bayar = 'Belum Bayar' AND reg_periksa.stts = 'Sudah' AND reg_periksa.status_lanjut = 'Ralan'";

$query3 = "SELECT COUNT(reg_periksa.no_rawat) AS 'Jumlah Rawat', MONTH(reg_periksa.tgl_registrasi) AS 'Bulan'
        FROM reg_periksa
        INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
        WHERE dokter.status NOT IN ('0') 
        AND reg_periksa.status_bayar = 'Sudah Bayar' 
        AND reg_periksa.status_lanjut = 'Ralan'
        AND dokter.nm_dokter = '$selecteddokter'
        AND YEAR(reg_periksa.tgl_registrasi) = YEAR(CURDATE())
        GROUP BY MONTH(reg_periksa.tgl_registrasi)";

$query4 = "SELECT COUNT(reg_periksa.no_rawat) AS 'Jumlah Rawat', MONTH(reg_periksa.tgl_registrasi) AS 'Bulan'
        FROM reg_periksa
        INNER JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
        WHERE dokter.status NOT IN ('0') 
        AND reg_periksa.status_bayar = 'Sudah Bayar' 
        AND reg_periksa.status_lanjut = 'Ralan'
        AND dokter.nm_dokter = '$selecteddokter'
        AND YEAR(reg_periksa.tgl_registrasi) = YEAR(CURDATE()) - 1
        GROUP BY MONTH(reg_periksa.tgl_registrasi)";

$queryDataTabel = mysqli_query($konektor, $query3);
$queryDataTabel2 = mysqli_query($konektor, $query4);

$dataTabel = array();
while ($row = mysqli_fetch_assoc($queryDataTabel)) {
    $dataTabel[] = $row;
}
$dataTabel2 = array();
while ($row = mysqli_fetch_assoc($queryDataTabel2)) {
    $dataTabel2[] = $row;
}


$jumlahPerBulan = array();
foreach ($dataTabel as $row) {
    $bulan = $row['Bulan'];
    $jumlahRawat = $row['Jumlah Rawat'];
    $jumlahPerBulan[$bulan] = $jumlahRawat;
}

$jumlahPerBulan2 = array();
foreach ($dataTabel2 as $row) {
    $bulan = $row['Bulan'];
    $jumlahRawat = $row['Jumlah Rawat'];
    $jumlahPerBulan2[$bulan] = $jumlahRawat;
}


// Menambahkan kondisi pencarian berdasarkan tanggal
$query .= " AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama1' AND '$tanggalKedua1'";
$query2 .= " AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama1' AND '$tanggalKedua1'";

// Menambahkan kondisi pencarian berdasarkan dokter.nm_dokter
if (!empty($selecteddokter)) {
    $query .= " AND dokter.nm_dokter = '$selecteddokter'";
}
$queryDataTabel = mysqli_query($konektor, $query);
$dataTabel = array();
while ($row = mysqli_fetch_assoc($queryDataTabel)) {
    $dataTabel[] = $row;
}
if (!empty($selecteddokter)) {
    $query2 .= " AND dokter.nm_dokter = '$selecteddokter'";
}
$queryDataTabel2 = mysqli_query($konektor, $query2);
$dataTabel2 = array();
while ($row = mysqli_fetch_assoc($queryDataTabel2)) {
    $dataTabel2[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>

<body style="font-family: 'Poppins', sans-serif;color: black;">
    <?php include 'include/navbar.php'; ?>

    <!-- container -->
    <div class="container-xl">
        <div class="row mb-3">
            <h2>Dashboard Dokter</h2>
        </div>
        <div class="col-lg-12 mb-3">
            <form action="" method="post" class="form-inline">
                <div class="form-group">
                    <label for="tanggal_pertama1">Pencarian Tanggal:</label>
                    <input type="date" class="form-control mx-2" id="tanggal_pertama1" name="tanggal_pertama1" value="<?php echo $tanggalPertama1; ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal_kedua1">s/d</label>
                    <input type="date" class="form-control mx-2" id="tanggal_kedua1" name="tanggal_kedua1" value="<?php echo $tanggalKedua1; ?>">
                </div>
                <div class="form-group">
                    <label for="dokter">Jenis dokter:</label>
                    <select class="form-control mx-2" id="dokter" name="dokter">
                        <option value="">- Pilih dokter -</option>
                        <?php foreach ($dokterList as $dokter) { ?>
                            <option value="<?php echo $dokter; ?>" <?php echo ($selecteddokter == $dokter) ? 'selected' : ''; ?>><?php echo $dokter; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="search1">Cari</button>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="single-doctor-box card-full-height">
                    <div class="doctor-image text-center">
                        <!-- Tambahkan kode untuk menampilkan foto atau gambar dokter dari folder 'images' -->
                        <img src="images/logoRSSH1.png" alt="Foto Dokter">
                    </div>
                    <br>
                    <div class="doctor-details text-center">
                        <!-- memanggil nama dokter-->
                        <h5>
                            <?php
                                if (!empty($selecteddokter)) {
                                    echo $selecteddokter;
                                } else {
                                    echo "Nama Dokter Default";
                                }
                            ?>
                        </h5>
                        <!-- Tambahkan kode untuk memanggil spesialis dokter dari database -->
                        <span>RS Syarif Hidayatullah</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Biodata</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>Nama Anda</td>
                                </tr>
                                <tr>
                                    <td>Spesialis</td>
                                    <td>:</td>
                                    <td>Spesialis Anda</td>
                                </tr>
                                <tr>
                                    <td>Tempat/Tgl Lahir</td>
                                    <td>:</td>
                                    <td>Jenis Kelamin Anda</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>Jenis Kelamin Anda</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>Alamat Anda</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>Status Anda</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Kunjungan Tahun Sekarang</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jan</th>
                                    <th>Feb</th>
                                    <th>Mar</th>
                                    <th>Apr</th>
                                    <th>May</th>
                                    <th>Jun</th>
                                    <th>Jul</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="jan" scope="col"><?php echo isset($jumlahPerBulan[1]) ? $jumlahPerBulan[1] : 0; ?></td>
                                    <td id="feb"><?php echo isset($jumlahPerBulan[2]) ? $jumlahPerBulan[2] : 0; ?></td>
                                    <td id="mar"><?php echo isset($jumlahPerBulan[3]) ? $jumlahPerBulan[3] : 0; ?></td>
                                    <td id="apr"><?php echo isset($jumlahPerBulan[4]) ? $jumlahPerBulan[4] : 0; ?></td>
                                    <td id="may"><?php echo isset($jumlahPerBulan[5]) ? $jumlahPerBulan[5] : 0; ?></td>
                                    <td id="jun"><?php echo isset($jumlahPerBulan[6]) ? $jumlahPerBulan[6] : 0; ?></td>
                                    <td id="jul"><?php echo isset($jumlahPerBulan[7]) ? $jumlahPerBulan[7] : 0; ?></td>
                                    
                                </tr>
                            </tbody>
                        </table>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aug</th>
                                    <th>Sep</th>
                                    <th>Oct</th>
                                    <th>Nov</th>
                                    <th>Dec</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="aug"><?php echo isset($jumlahPerBulan[8]) ? $jumlahPerBulan[8] : 0; ?></td>
                                    <td id="sep"><?php echo isset($jumlahPerBulan[9]) ? $jumlahPerBulan[9] : 0; ?></td>
                                    <td id="oct"><?php echo isset($jumlahPerBulan[10]) ? $jumlahPerBulan[10] : 0; ?></td>
                                    <td id="nov"><?php echo isset($jumlahPerBulan[11]) ? $jumlahPerBulan[11] : 0; ?></td>
                                    <td id="dec"><?php echo isset($jumlahPerBulan[12]) ? $jumlahPerBulan[12] : 0; ?></td>
                                    <td id="total">0</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Kunjungan Tahun Sebelumnya</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jan</th>
                                    <th>Feb</th>
                                    <th>Mar</th>
                                    <th>Apr</th>
                                    <th>May</th>
                                    <th>Jun</th>
                                    <th>Jul</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="jan"><?php echo isset($jumlahPerBulan2[1]) ? $jumlahPerBulan2[1] : 0; ?></td>
                                    <td id="feb"><?php echo isset($jumlahPerBulan2[2]) ? $jumlahPerBulan2[2] : 0; ?></td>
                                    <td id="mar"><?php echo isset($jumlahPerBulan2[3]) ? $jumlahPerBulan2[3] : 0; ?></td>
                                    <td id="apr"><?php echo isset($jumlahPerBulan2[4]) ? $jumlahPerBulan2[4] : 0; ?></td>
                                    <td id="may"><?php echo isset($jumlahPerBulan2[5]) ? $jumlahPerBulan2[5] : 0; ?></td>
                                    <td id="jun"><?php echo isset($jumlahPerBulan2[6]) ? $jumlahPerBulan2[6] : 0; ?></td>
                                    <td id="jul"><?php echo isset($jumlahPerBulan2[7]) ? $jumlahPerBulan2[7] : 0; ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aug</th>
                                    <th>Sep</th>
                                    <th>Oct</th>
                                    <th>Nov</th>
                                    <th>Dec</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="aug"><?php echo isset($jumlahPerBulan2[8]) ? $jumlahPerBulan2[8] : 0; ?></td>
                                    <td id="sep"><?php echo isset($jumlahPerBulan2[9]) ? $jumlahPerBulan2[9] : 0; ?></td>
                                    <td id="oct"><?php echo isset($jumlahPerBulan2[10]) ? $jumlahPerBulan2[10] : 0; ?></td>
                                    <td id="nov"><?php echo isset($jumlahPerBulan2[11]) ? $jumlahPerBulan2[11] : 0; ?></td>
                                    <td id="dec"><?php echo isset($jumlahPerBulan2[12]) ? $jumlahPerBulan2[12] : 0; ?></td>
                                    <td id="total">0</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tabel Pasien</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Nomor Rawat</th>
                                    <th>Cara Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- include script -->
    <?php include 'include/script.php'; ?>
    <!-- include JS -->
    <?php include 'include/js.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
    // Menghitung jumlah dari kolom Jan sampai Dec
    var total = 0;
    var table = document.getElementsByTagName('table')[0];
    for (var i = 1; i <= 12; i++) {
        total += parseInt(table.rows[1].cells[i].innerHTML);
    }
    document.getElementById('total').innerHTML = total;
</script>
</body>

</html>
