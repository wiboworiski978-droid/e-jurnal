<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


$nip = trim($_POST['nip'] ?? '');
$nama_guru = trim($_POST['nama_guru'] ?? '');
$password = $_POST['password'] ?? '';


// Validasi
if (
    empty($nip) ||
    empty($nama_guru) ||
    empty($password)
) {
    die("Semua data wajib diisi.");
}


// Cek NIP
$queryCek = "SELECT id_guru
             FROM guru
             WHERE nip = ?";

$stmtCek = mysqli_prepare($conn, $queryCek);

mysqli_stmt_bind_param(
    $stmtCek,
    "s",
    $nip
);

mysqli_stmt_execute($stmtCek);

$resultCek = mysqli_stmt_get_result($stmtCek);

if (mysqli_num_rows($resultCek) > 0) {

    die("NIP sudah terdaftar.");

}


// Hash password
$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


// Simpan guru
$query = "INSERT INTO guru
          (
              nip,
              nama_guru,
              password
          )
          VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $nip,
    $nama_guru,
    $password_hash
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: guru.php?success=1");
    exit;

} else {

    die(
        "Gagal menambahkan guru: "
        . mysqli_error($conn)
    );

}