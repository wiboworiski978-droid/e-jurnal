<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}


$id_siswa = $_SESSION['id_siswa'];

$id_jurnal = $_GET['id'] ?? 0;


$query = "SELECT *
          FROM jurnal
          WHERE id_jurnal = ?
          AND id_siswa = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id_jurnal,
    $id_siswa
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$jurnal = mysqli_fetch_assoc($result);


if (!$jurnal) {
    die("Jurnal tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Detail Jurnal - E-Jurnal SMK</title>

</head>

<body>

    <h1>Detail Jurnal</h1>


    <p>
        <strong>Tanggal:</strong><br>
        <?= htmlspecialchars($jurnal['tanggal_kegiatan']); ?>
    </p>


    <p>
        <strong>Nama DUDI:</strong><br>
        <?= htmlspecialchars($jurnal['nama_dudi']); ?>
    </p>


    <p>
        <strong>Pembimbing:</strong><br>
        <?= htmlspecialchars($jurnal['pembimbing']); ?>
    </p>


    <p>
        <strong>Kegiatan Magang:</strong><br>
        <?= nl2br(
            htmlspecialchars(
                $jurnal['kegiatan_magang']
            )
        ); ?>
    </p>


    <p>
        <strong>Keterangan:</strong><br>

        <?= nl2br(
            htmlspecialchars(
                $jurnal['keterangan']
            )
        ); ?>
    </p>


    <p>
        <strong>Foto:</strong>
    </p>


    <?php if (!empty($jurnal['foto'])): ?>

        <img
            src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
            width="400"
        >

    <?php else: ?>

        <p>Tidak ada foto.</p>

    <?php endif; ?>


    <br><br>

    <a href="edit_jurnal.php?id=<?= $jurnal['id_jurnal']; ?>">
        Edit Jurnal
    </a>

    <br><br>

    <a href="dashboard.php">
        Kembali ke Dashboard
    </a>

</body>

</html>