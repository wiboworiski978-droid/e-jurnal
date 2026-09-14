<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Hitung jumlah siswa
$querySiswa = "SELECT COUNT(*) AS total FROM siswa";
$resultSiswa = mysqli_query($conn, $querySiswa);
$totalSiswa = mysqli_fetch_assoc($resultSiswa)['total'];

// Hitung jumlah guru
$queryGuru = "SELECT COUNT(*) AS total FROM guru";
$resultGuru = mysqli_query($conn, $queryGuru);
$totalGuru = mysqli_fetch_assoc($resultGuru)['total'];

// Hitung jumlah jurnal
$queryJurnal = "SELECT COUNT(*) AS total FROM jurnal";
$resultJurnal = mysqli_query($conn, $queryJurnal);
$totalJurnal = mysqli_fetch_assoc($resultJurnal)['total'];

// Hitung jumlah bimbingan
$queryBimbingan = "SELECT COUNT(*) AS total FROM bimbingan";
$resultBimbingan = mysqli_query($conn, $queryBimbingan);
$totalBimbingan = mysqli_fetch_assoc($resultBimbingan)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - E-Jurnal SMK</title>

</head>

<body>

    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang,
        <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
    </p>

    <hr>

    <h2>Statistik</h2>

    <p>
        Total Siswa:
        <strong><?= $totalSiswa; ?></strong>
    </p>

    <p>
        Total Guru:
        <strong><?= $totalGuru; ?></strong>
    </p>

    <p>
        Total Jurnal:
        <strong><?= $totalJurnal; ?></strong>
    </p>

    <p>
        Total Dokumentasi Bimbingan:
        <strong><?= $totalBimbingan; ?></strong>
    </p>

    <hr>

    <h2>Menu Admin</h2>

    <ul>

        <li>
            <a href="siswa.php">
                Kelola Siswa
            </a>
        </li>

        <li>
            <a href="guru.php">
                Kelola Guru
            </a>
        </li>

        <li>
            <a href="jurnal.php">
                Semua Jurnal
            </a>
        </li>

        <li>
            <a href="bimbingan.php">
                Dokumentasi Bimbingan
            </a>
        </li>

        <li>
            <a href="../auth/logout.php">
                Logout
            </a>
        </li>

    </ul>

</body>

</html>