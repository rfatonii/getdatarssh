<?php
session_start();
require_once('../conf/conf.php');
//import koneksi ke database
$konektor = bukakoneksi();

if (isset($_POST['search1'])) {
    $tanggalPertama1 = $_POST['tanggal_pertama1'];
    $tanggalKedua1 = $_POST['tanggal_kedua1'];
} else {
    $tanggalPertama1 = date('Y-m-d');
    $tanggalKedua1 = date('Y-m-d');
}
?>
<html>
<head>
  <title>Stock Barang</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
			<h2>Stock Bahan</h2>
			<h4>(Inventory)</h4>
				<div class="data-tables datatable-dark">
					
					<!-- Masukkan table nya disini, dimulai dari tag TABLE -->
                    <form action="" method="post" class="form-inline justify-content-center">
                    <div class="form-group">
                        <label for="tanggal_pertama1">Pencarian Belum Bayar:</label>
                        <input type="date" class="form-control mx-2" id="tanggal_pertama1" name="tanggal_pertama1"
                            value="<?php echo $tanggalPertama1; ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kedua1">s/d</label>
                        <input type="date" class="form-control mx-2" id="tanggal_kedua1" name="tanggal_kedua1"
                            value="<?php echo $tanggalKedua1; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="search1">Cari</button>
                    <!-- Tambahkan tombol untuk ekspor ke Excel -->
                    <!-- <a href="export.php" class="btn btn-secondary">Export to Excel</a>  -->
                    <!-- Tambahkan tombol untuk ekspor ke PDF -->
                    <!-- <a href="pdf.php" class="btn btn-danger">Export to PDF</a>  -->
                    
                    </form>
                    <table class="table table-light table-hover text-black-50" id="mauexport">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">No Rawat</th>
                            <th scope="col">Pasien</th>
                            <th scope="col">Menggunakan</th>
                            <th scope="col">Pemeriksaan Dokter</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $queryDataTabel = mysqli_query($konektor, "SELECT reg_periksa.no_rawat as rawat, pasien.nm_pasien as pasien, penjab.png_jawab AS menggunakan, reg_periksa.stts AS Pemeriksaan_Dokter, reg_periksa.status_bayar AS Keterangan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj WHERE reg_periksa.status_bayar = 'Belum Bayar' AND reg_periksa.stts = 'Sudah' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama1' AND '$tanggalKedua1'");
                            $no = 1;
                            $totalBelumBayar = 0; // Menyimpan total belum bayar
                            while ($row = mysqli_fetch_assoc($queryDataTabel)) 
                            {
                                $rawat = $row['rawat'];
                                $pasien = $row['pasien'];
                                $menggunakan = $row['menggunakan'];
                                $Pemeriksaan_Dokter = $row['Pemeriksaan_Dokter'];
                                $keterangan = $row['Keterangan'];
                                // Menambahkan nilai belum bayar ke totalBelumBayar
                                //$totalBelumBayar += $belumbayar;
                                ?>
                                    <tr>
                                    <th scope="row"><?php echo $no; ?></th>
                                    <td><?php echo $rawat; ?></td>
                                    <td><?php echo $pasien; ?></td>
                                    <td><?php echo $menggunakan; ?></td>
                                    <td><?php echo $Pemeriksaan_Dokter; ?></td>
                                    <td><?php echo $keterangan; ?></td>
                                    </tr>
                                <?php
                                $no++;
                            }
                        ?>
                    </tbody>
                </table>
					
				</div>
</div>
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy','csv','excel', 'pdf', 'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>