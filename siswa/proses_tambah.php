<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}


$id_siswa = $_SESSION['id_siswa'];

$tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';
$nama_dudi = $_POST['nama_dudi'] ?? '';
$pembimbing = $_POST['pembimbing'] ?? '';
$kegiatan_magang = $_POST['kegiatan_magang'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';


// Validasi input
if (
    empty($tanggal_kegiatan) ||
    empty($nama_dudi) ||
    empty($pembimbing) ||
    empty($kegiatan_magang)
) {
    die("Data jurnal wajib diisi.");
}


// ============================
// PROSES UPLOAD FOTO
// ============================

$nama_foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

    $foto = $_FILES['foto'];

    // Cek error upload
    if ($foto['error'] !== UPLOAD_ERR_OK) {
        die("Foto gagal diupload.");
    }

    // Batas ukuran 2 MB
    if ($foto['size'] > 2 * 1024 * 1024) {
        die("Ukuran foto maksimal 2 MB.");
    }

    // Ekstensi yang diperbolehkan
    $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];

    $nama_asli = $foto['name'];
    $ekstensi = strtolower(
        pathinfo($nama_asli, PATHINFO_EXTENSION)
    );

    if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
        die("Format foto harus JPG, JPEG, atau PNG.");
    }

    // Buat nama file unik
    $nama_foto = uniqid('jurnal_', true) . '.' . $ekstensi;

    $folder_upload = "../uploads/jurnal/";

    $tujuan = $folder_upload . $nama_foto;

    if (!move_uploaded_file($foto['tmp_name'], $tujuan)) {
        die("Foto gagal disimpan.");
    }
}


// ============================
// SIMPAN KE DATABASE
// ============================

$query = "INSERT INTO jurnal
          (
              id_siswa,
              tanggal_kegiatan,
              nama_dudi,
              pembimbing,
              kegiatan_magang,
              keterangan,
              foto
          )
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "issssss",
    $id_siswa,
    $tanggal_kegiatan,
    $nama_dudi,
    $pembimbing,
    $kegiatan_magang,
    $keterangan,
    $nama_foto
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: dashboard.php?success=Jurnal berhasil disimpan");
    exit;

} else {

    // Jika database gagal dan foto sudah terupload,
    // hapus kembali fotonya
    if ($nama_foto !== null) {
        $file = "../uploads/jurnal/" . $nama_foto;

        if (file_exists($file)) {
            unlink($file);
        }
    }

    die("Jurnal gagal disimpan: " . mysqli_error($conn));
}