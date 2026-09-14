<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


$id_guru = $_GET['id'] ?? '';

if (empty($id_guru)) {
    die("ID guru tidak ditemukan.");
}

$id_guru = (int) $id_guru;


// Pastikan guru memang ada
$queryCek = "SELECT id_guru
             FROM guru
             WHERE id_guru = ?";

$stmtCek = mysqli_prepare($conn, $queryCek);

mysqli_stmt_bind_param(
    $stmtCek,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmtCek);

$resultCek = mysqli_stmt_get_result($stmtCek);

if (mysqli_num_rows($resultCek) === 0) {

    die("Guru tidak ditemukan.");

}


// Hapus guru
$query = "DELETE FROM guru
          WHERE id_guru = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_guru
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: guru.php?deleted=1");
    exit;

} else {

    die(
        "Gagal menghapus guru: "
        . mysqli_error($conn)
    );

}