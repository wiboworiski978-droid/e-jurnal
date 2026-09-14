<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../auth/login.php");
    exit;
}

$id_guru = $_SESSION['id_guru'];

$query = "SELECT
            b.id_bimbingan,
            b.tanggal_bimbingan,
            b.nama_dudi,
            b.keterangan,
            b.foto,
            s.nisn,
            s.nama_lengkap,
            s.kelas
          FROM bimbingan b
          INNER JOIN siswa s
              ON b.id_siswa = s.id_siswa
          WHERE b.id_guru = ?
          ORDER BY b.tanggal_bimbingan DESC";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Bimbingan - E-Jurnal SMK</title>

</head>

<body>

    <h1>Riwayat Dokumentasi Bimbingan</h1>

    <a href="dashboard.php">
        ← Kembali ke Dashboard
    </a>

    <br><br>

    <a href="tambah_bimbingan.php">
        + Tambah Dokumentasi Bimbingan
    </a>

    <br><br>

    <?php if (isset($_GET['success'])) : ?>

        <p>
            Dokumentasi bimbingan berhasil disimpan.
        </p>

    <?php endif; ?>


    <?php if (mysqli_num_rows($result) > 0) : ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>DUDI</th>
                    <th>Keterangan</th>
                    <th>Foto</th>
                </tr>

            </thead>

            <tbody>

                <?php
                $no = 1;
                while ($data = mysqli_fetch_assoc($result)) :
                ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['tanggal_bimbingan']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nama_lengkap']); ?>
                            <br>
                            <?= htmlspecialchars($data['nisn']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['kelas']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nama_dudi']); ?>
                        </td>

                        <td>
                            <?= nl2br(htmlspecialchars($data['keterangan'])); ?>
                        </td>

                        <td>

                            <?php if (!empty($data['foto'])) : ?>

                                <img
                                    src="../uploads/bimbingan/<?= htmlspecialchars($data['foto']); ?>"
                                    width="120"
                                    alt="Foto Bimbingan"
                                >

                            <?php else : ?>

                                Tidak ada foto

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else : ?>

        <p>
            Belum ada dokumentasi bimbingan.
        </p>

    <?php endif; ?>

</body>

</html>