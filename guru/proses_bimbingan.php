<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../auth/login.php");
    exit;
}

$id_guru = $_SESSION['id_guru'];

$id_siswa = $_POST['id_siswa'] ?? '';
$tanggal_bimbingan = $_POST['tanggal_bimbingan'] ?? '';
$nama_dudi = trim($_POST['nama_dudi'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');


// ==============================
// VALIDASI DATA
// ==============================

if (
    empty($id_siswa) ||
    empty($tanggal_bimbingan) ||
    empty($nama_dudi) ||
    empty($keterangan)
) {
    die("Semua data wajib diisi.");
}


// ==============================
// CEK SISWA
// HARUS SISWA YANG DIBIMBING GURU
// ==============================

$query = "SELECT id_siswa
          FROM siswa
          WHERE id_siswa = ?
          AND id_guru = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id_siswa,
    $id_guru
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("Siswa tidak ditemukan atau bukan siswa bimbingan Anda.");
}


// ==============================
// UPLOAD FOTO
// ==============================

$nama_foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        die("Upload foto gagal.");
    }

    // Maksimal 2 MB
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        die("Ukuran foto maksimal 2 MB.");
    }

    $nama_file_asli = $_FILES['foto']['name'];

    $ekstensi = strtolower(
        pathinfo($nama_file_asli, PATHINFO_EXTENSION)
    );

    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png'];

    if (!in_array($ekstensi, $ekstensi_diizinkan)) {
        die("Format foto harus JPG, JPEG, atau PNG.");
    }

    $nama_foto = uniqid('bimbingan_', true) . '.' . $ekstensi;

    $folder_upload = "../uploads/bimbingan/";

    if (!is_dir($folder_upload)) {
        mkdir($folder_upload, 0777, true);
    }

    $tujuan = $folder_upload . $nama_foto;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
        die("Foto gagal disimpan.");
    }
}


// ==============================
// SIMPAN KE DATABASE
// ==============================

$query = "INSERT INTO bimbingan
          (
              id_guru,
              id_siswa,
              tanggal_bimbingan,
              nama_dudi,
              keterangan,
              foto
          )
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "iissss",
    $id_guru,
    $id_siswa,
    $tanggal_bimbingan,
    $nama_dudi,
    $keterangan,
    $nama_foto
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: riwayat_bimbingan.php?success=1");
    exit;

} else {

    // Kalau database gagal, hapus foto yang sudah diupload
    if ($nama_foto && file_exists("../uploads/bimbingan/" . $nama_foto)) {
        unlink("../uploads/bimbingan/" . $nama_foto);
    }

    die("Data gagal disimpan: " . mysqli_error($conn));
}