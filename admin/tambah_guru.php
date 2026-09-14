<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru - E-Jurnal SMK</title>

</head>

<body>

    <h1>Tambah Guru</h1>

    <a href="guru.php">
        ← Kembali
    </a>

    <br><br>

    <form action="proses_tambah_guru.php" method="POST">

        <label>NIP</label>

        <br>

        <input
            type="text"
            name="nip"
            placeholder="Masukkan NIP"
            required
        >

        <br><br>


        <label>Nama Guru</label>

        <br>

        <input
            type="text"
            name="nama_guru"
            placeholder="Masukkan nama guru"
            required
        >

        <br><br>


        <label>Password</label>

        <br>

        <input
            type="password"
            name="password"
            placeholder="Masukkan password"
            required
        >

        <br><br>


        <button type="submit">
            Simpan Guru
        </button>

    </form>

</body>

</html>