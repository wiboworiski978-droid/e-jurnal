<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];

// Ambil data siswa
$query = "SELECT siswa.*, guru.nama_guru
          FROM siswa
          LEFT JOIN guru ON siswa.id_guru = guru.id_guru
          WHERE siswa.id_siswa = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Isi Jurnal - E-Jurnal SMK</title>
</head>

<body>

    <h1>Isi Jurnal Magang</h1>

    <p>
        <strong>Nama:</strong>
        <?= htmlspecialchars($siswa['nama_lengkap']); ?>
    </p>

    <p>
        <strong>Kelas:</strong>
        <?= htmlspecialchars($siswa['kelas']); ?>
    </p>

    <p>
        <strong>Jurusan:</strong>
        <?= htmlspecialchars($siswa['jurusan']); ?>
    </p>

    <p>
        <strong>DUDI:</strong>
        <?= htmlspecialchars($siswa['dudi_magang']); ?>
    </p>

    <p>
        <strong>Pembimbing:</strong>
        <?= htmlspecialchars($siswa['nama_guru'] ?? '-'); ?>
    </p>

    <hr>

    <form
        action="proses_tambah.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <label>Tanggal Kegiatan</label>
        <br>

        <input
            type="date"
            name="tanggal_kegiatan"
            required
        >

        <br><br>


        <label>Nama DUDI</label>
        <br>

        <input
            type="text"
            name="nama_dudi"
            value="<?= htmlspecialchars($siswa['dudi_magang']); ?>"
            required
        >

        <br><br>


        <label>Pembimbing</label>
        <br>

        <input
            type="text"
            name="pembimbing"
            value="<?= htmlspecialchars($siswa['nama_guru'] ?? ''); ?>"
            required
        >

        <br><br>


        <label>Kegiatan Magang</label>
        <br>

        <textarea
            name="kegiatan_magang"
            rows="5"
            required
        ></textarea>

        <br><br>


        <label>Keterangan / Detail Kegiatan</label>
        <br>

        <textarea
            name="keterangan"
            rows="5"
        ></textarea>

        <br><br>


        <label>Foto Kegiatan</label>
        <br>

        <input
            type="file"
            name="foto"
            accept=".jpg,.jpeg,.png"
        >

        <br>

        <small>
            Format yang diperbolehkan: JPG, JPEG, PNG
        </small>

        <br><br>


        <button type="submit">
            Simpan Jurnal
        </button>

        <a href="dashboard.php">
            Batal
        </a>

    </form>

</body>

</html>