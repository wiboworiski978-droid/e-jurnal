<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_GET['id'] ?? '';

if (empty($id_siswa)) {
    die("ID siswa tidak ditemukan.");
}

$id_siswa = (int) $id_siswa;


// Ambil foto jurnal siswa
$queryFoto = "SELECT foto
              FROM jurnal
              WHERE id_siswa = ?";

$stmtFoto = mysqli_prepare($conn, $queryFoto);

mysqli_stmt_bind_param(
    $stmtFoto,
    "i",
    $id_siswa
);

mysqli_stmt_execute($stmtFoto);

$resultFoto = mysqli_stmt_get_result($stmtFoto);

while ($data = mysqli_fetch_assoc($resultFoto)) {

    if (!empty($data['foto'])) {

        $file = "../uploads/jurnal/" . $data['foto'];

        if (file_exists($file)) {
            unlink($file);
        }

    }

}


// Ambil foto bimbingan
$queryFotoBimbingan = "SELECT foto
                       FROM bimbingan
                       WHERE id_siswa = ?";

$stmtFotoBimbingan = mysqli_prepare(
    $conn,
    $queryFotoBimbingan
);

mysqli_stmt_bind_param(
    $stmtFotoBimbingan,
    "i",
    $id_siswa
);

mysqli_stmt_execute($stmtFotoBimbingan);

$resultFotoBimbingan = mysqli_stmt_get_result(
    $stmtFotoBimbingan
);

while ($data = mysqli_fetch_assoc($resultFotoBimbingan)) {

    if (!empty($data['foto'])) {

        $file = "../uploads/bimbingan/" . $data['foto'];

        if (file_exists($file)) {
            unlink($file);
        }

    }

}


// Hapus siswa
$query = "DELETE FROM siswa
          WHERE id_siswa = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_siswa
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: siswa.php?deleted=1");
    exit;

} else {

    die("Gagal menghapus siswa: " . mysqli_error($conn));

}