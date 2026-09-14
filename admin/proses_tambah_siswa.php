<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


$nisn = trim($_POST['nisn'] ?? '');
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');
$dudi_magang = trim($_POST['dudi_magang'] ?? '');
$password = $_POST['password'] ?? '';
$id_guru = $_POST['id_guru'] ?? '';


// Validasi
if (
    empty($nisn) ||
    empty($nama_lengkap) ||
    empty($kelas) ||
    empty($jurusan) ||
    empty($dudi_magang) ||
    empty($password)
) {
    die("Semua data wajib diisi.");
}


// Cek NISN
$queryCek = "SELECT id_siswa
             FROM siswa
             WHERE nisn = ?";

$stmtCek = mysqli_prepare($conn, $queryCek);

mysqli_stmt_bind_param(
    $stmtCek,
    "s",
    $nisn
);

mysqli_stmt_execute($stmtCek);

$resultCek = mysqli_stmt_get_result($stmtCek);

if (mysqli_num_rows($resultCek) > 0) {
    die("NISN sudah terdaftar.");
}


// Hash password
$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// Kalau guru kosong, gunakan NULL
if ($id_guru === '') {

    $query = "INSERT INTO siswa
              (
                  nisn,
                  nama_lengkap,
                  kelas,
                  jurusan,
                  dudi_magang,
                  password,
                  id_guru
              )
              VALUES (?, ?, ?, ?, ?, ?, NULL)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $nisn,
        $nama_lengkap,
        $kelas,
        $jurusan,
        $dudi_magang,
        $password_hash
    );

} else {

    $id_guru = (int) $id_guru;

    $query = "INSERT INTO siswa
              (
                  nisn,
                  nama_lengkap,
                  kelas,
                  jurusan,
                  dudi_magang,
                  password,
                  id_guru
              )
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $nisn,
        $nama_lengkap,
        $kelas,
        $jurusan,
        $dudi_magang,
        $password_hash,
        $id_guru
    );
}


if (mysqli_stmt_execute($stmt)) {

    header("Location: siswa.php?success=1");
    exit;

} else {

    die("Gagal menambahkan siswa: " . mysqli_error($conn));

}