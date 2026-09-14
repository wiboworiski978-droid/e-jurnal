<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];
$id_jurnal = $_GET['id'] ?? 0;


// Ambil jurnal milik siswa yang sedang login
$query = "SELECT *
          FROM jurnal
          WHERE id_jurnal = ?
          AND id_siswa = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $id_jurnal, $id_siswa);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$jurnal = mysqli_fetch_assoc($result);


// Jika jurnal tidak ditemukan
if (!$jurnal) {
    die("Jurnal tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Jurnal - E-Jurnal SMK</title>

</head>

<body>

    <h1>Edit Jurnal</h1>

    <form
        action="proses_edit.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <input
            type="hidden"
            name="id_jurnal"
            value="<?= $jurnal['id_jurnal']; ?>"
        >


        <label>Tanggal Kegiatan</label>
        <br>

        <input
            type="date"
            name="tanggal_kegiatan"
            value="<?= htmlspecialchars($jurnal['tanggal_kegiatan']); ?>"
            required
        >

        <br><br>


        <label>Nama DUDI</label>
        <br>

        <input
            type="text"
            name="nama_dudi"
            value="<?= htmlspecialchars($jurnal['nama_dudi']); ?>"
            required
        >

        <br><br>


        <label>Pembimbing</label>
        <br>

        <input
            type="text"
            name="pembimbing"
            value="<?= htmlspecialchars($jurnal['pembimbing']); ?>"
            required
        >

        <br><br>


        <label>Kegiatan Magang</label>
        <br>

        <textarea
            name="kegiatan_magang"
            rows="5"
            required
        ><?= htmlspecialchars($jurnal['kegiatan_magang']); ?></textarea>

        <br><br>


        <label>Keterangan / Detail Kegiatan</label>
        <br>

        <textarea
            name="keterangan"
            rows="5"
        ><?= htmlspecialchars($jurnal['keterangan']); ?></textarea>

        <br><br>


        <label>Foto Saat Ini</label>
        <br>

        <?php if (!empty($jurnal['foto'])): ?>

            <img
                src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
                width="150"
            >

        <?php else: ?>

            <p>Belum ada foto.</p>

        <?php endif; ?>

        <br><br>


        <label>Ganti Foto</label>
        <br>

        <input
            type="file"
            name="foto"
            accept=".jpg,.jpeg,.png"
        >

        <br>

        <small>
            Kosongkan jika tidak ingin mengganti foto.
        </small>

        <br><br>


        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="dashboard.php">
            Batal
        </a>

    </form>

</body>

</html>