<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "SELECT
            b.id_bimbingan,
            b.tanggal_bimbingan,
            b.nama_dudi,
            b.keterangan,
            b.foto,
            s.nisn,
            s.nama_lengkap,
            s.kelas,
            guru.nip,
            guru.nama_guru
          FROM bimbingan b
          INNER JOIN siswa s
              ON b.id_siswa = s.id_siswa
          INNER JOIN guru
              ON b.id_guru = guru.id_guru
          ORDER BY b.tanggal_bimbingan DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dokumentasi Bimbingan - E-Jurnal SMK</title>

</head>

<body>

    <h1>Dokumentasi Bimbingan</h1>

    <a href="dashboard.php">
        ← Kembali ke Dashboard
    </a>

    <br><br>

    <?php if (mysqli_num_rows($result) > 0) : ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Guru</th>
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

            while ($bimbingan = mysqli_fetch_assoc($result)) :

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $bimbingan['tanggal_bimbingan']
                        ); ?>
                    </td>

                    <td>

                        <?= htmlspecialchars(
                            $bimbingan['nama_guru']
                        ); ?>

                        <br>

                        NIP:
                        <?= htmlspecialchars(
                            $bimbingan['nip']
                        ); ?>

                    </td>

                    <td>

                        <?= htmlspecialchars(
                            $bimbingan['nama_lengkap']
                        ); ?>

                        <br>

                        NISN:
                        <?= htmlspecialchars(
                            $bimbingan['nisn']
                        ); ?>

                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $bimbingan['kelas']
                        ); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $bimbingan['nama_dudi']
                        ); ?>
                    </td>

                    <td>
                        <?= nl2br(
                            htmlspecialchars(
                                $bimbingan['keterangan']
                            )
                        ); ?>
                    </td>

                    <td>

                        <?php if (!empty($bimbingan['foto'])) : ?>

                            <img
                                src="../uploads/bimbingan/<?= htmlspecialchars($bimbingan['foto']); ?>"
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