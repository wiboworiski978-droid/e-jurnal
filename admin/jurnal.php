<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "SELECT
            j.id_jurnal,
            j.tanggal_kegiatan,
            j.nama_dudi,
            j.pembimbing,
            j.kegiatan_magang,
            j.keterangan,
            j.foto,
            s.nisn,
            s.nama_lengkap,
            s.kelas,
            s.jurusan
          FROM jurnal j
          INNER JOIN siswa s
              ON j.id_siswa = s.id_siswa
          ORDER BY j.tanggal_kegiatan DESC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Semua Jurnal - E-Jurnal SMK</title>

</head>

<body>

    <h1>Semua Jurnal Siswa</h1>

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
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>DUDI</th>
                    <th>Pembimbing</th>
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Foto</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while ($jurnal = mysqli_fetch_assoc($result)) :

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['tanggal_kegiatan']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['nama_lengkap']); ?>
                        <br>
                        NISN:
                        <?= htmlspecialchars($jurnal['nisn']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['kelas']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['nama_dudi']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['pembimbing']); ?>
                    </td>

                    <td>
                        <?= nl2br(
                            htmlspecialchars($jurnal['kegiatan_magang'])
                        ); ?>
                    </td>

                    <td>
                        <?= nl2br(
                            htmlspecialchars($jurnal['keterangan'])
                        ); ?>
                    </td>

                    <td>

                        <?php if (!empty($jurnal['foto'])) : ?>

                            <img
                                src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
                                width="120"
                                alt="Foto Jurnal"
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
            Belum ada jurnal siswa.
        </p>

    <?php endif; ?>

</body>

</html>