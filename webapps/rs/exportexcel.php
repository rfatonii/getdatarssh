<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require_once('../conf/conf.php');

$konektor = bukakoneksi();

if (isset($_POST['search1'])) {
    $tanggalPertama1 = $_GET['tanggal_pertama1'];
    $tanggalKedua1 = $_GET['tanggal_kedua1'];
} else {
    $tanggalPertama1 = date('Y-m-d');
    $tanggalKedua1 = date('Y-m-d');
}

$queryDataTabel = mysqli_query($konektor, "SELECT reg_periksa.no_rawat as rawat, pasien.nm_pasien as pasien, penjab.png_jawab AS menggunakan, reg_periksa.stts AS Pemeriksaan_Dokter, reg_periksa.status_bayar AS Keterangan FROM reg_periksa INNER JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis INNER JOIN penjab ON reg_periksa.kd_pj = penjab.kd_pj WHERE reg_periksa.status_bayar = 'Belum Bayar' AND reg_periksa.stts = 'Sudah' AND DATE(reg_periksa.tgl_registrasi) BETWEEN '$tanggalPertama1' AND '$tanggalKedua1'");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat objek Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set judul kolom pada tabel
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'No Rawat');
$sheet->setCellValue('C1', 'Pasien');
$sheet->setCellValue('D1', 'Menggunakan');
$sheet->setCellValue('E1', 'Pemeriksaan Dokter');
$sheet->setCellValue('F1', 'Keterangan');

// Ambil data dari database dan tambahkan ke worksheet
$no = 2; // Baris awal data
while ($row = mysqli_fetch_assoc($queryDataTabel)) {
    $rawat = $row['rawat'];
    $pasien = $row['pasien'];
    $menggunakan = $row['menggunakan'];
    $Pemeriksaan_Dokter = $row['Pemeriksaan_Dokter'];
    $keterangan = $row['Keterangan'];

    $sheet->setCellValue('A' . $no, $no - 1);
    $sheet->setCellValue('B' . $no, $rawat);
    $sheet->setCellValue('C' . $no, $pasien);
    $sheet->setCellValue('D' . $no, $menggunakan);
    $sheet->setCellValue('E' . $no, $Pemeriksaan_Dokter);
    $sheet->setCellValue('F' . $no, $keterangan);

    $no++;
}

// Konfigurasi header untuk ekspor ke Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_excel.xls"');
header('Cache-Control: max-age=0');

// Ekspor ke file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
