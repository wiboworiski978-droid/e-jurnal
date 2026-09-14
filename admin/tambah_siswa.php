<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


// Ambil semua guru
$queryGuru = "SELECT id_guru, nip, nama_guru
              FROM guru
              ORDER BY nama_guru ASC";

$resultGuru = mysqli_query($conn, $queryGuru);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Siswa - E-Jurnal SMK</title>

</head>

<body>

    <h1>Tambah Siswa</h1>

    <a href="siswa.php">
        ← Kembali
    </a>

    <br><br>

    <form action="proses_tambah_siswa.php" method="POST">

        <label>NISN</label>
        <br>

        <input
            type="text"
            name="nisn"
            required
        >

        <br><br>


        <label>Nama Lengkap</label>
        <br>

        <input
            type="text"
            name="nama_lengkap"
            required
        >

        <br><br>


        <label>Kelas</label>
        <br>

        <input
            type="text"
            name="kelas"
            placeholder="Contoh: XII RPL 1"
            required
        >

        <br><br>


        <label>Jurusan</label>
        <br>

        <input
            type="text"
            name="jurusan"
            placeholder="Contoh: Rekayasa Perangkat Lunak"
            required
        >

        <br><br>


        <label>DUDI Magang</label>
        <br>

        <input
            type="text"
            name="dudi_magang"
            required
        >

        <br><br>


        <label>Guru Pembimbing</label>
        <br>

        <select name="id_guru">

            <option value="">
                -- Belum Ditentukan --
            </option>

            <?php while ($guru = mysqli_fetch_assoc($resultGuru)) : ?>

                <option value="<?= $guru['id_guru']; ?>">

                    <?= htmlspecialchars($guru['nama_guru']); ?>
                    -
                    <?= htmlspecialchars($guru['nip']); ?>

                </option>

            <?php endwhile; ?>

        </select>

        <br><br>


        <label>Password</label>
        <br>

        <input
            type="password"
            name="password"
            required
        >

        <br><br>


        <button type="submit">
            Simpan Siswa
        </button>

    </form>

</body>

</html>