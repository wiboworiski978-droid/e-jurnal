<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "SELECT
            siswa.*,
            guru.nama_guru
          FROM siswa
          LEFT JOIN guru
              ON siswa.id_guru = guru.id_guru
          ORDER BY siswa.nama_lengkap ASC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Siswa - E-Jurnal SMK</title>

</head>

<body>

    <h1>Kelola Siswa</h1>

    <a href="dashboard.php">
        ← Kembali ke Dashboard
    </a>

    <br><br>

    <a href="tambah_siswa.php">
        + Tambah Siswa
    </a>

    <br><br>

    <?php if (isset($_GET['success'])) : ?>

        <p>
            Data siswa berhasil ditambahkan.
        </p>

    <?php endif; ?>


    <?php if (isset($_GET['deleted'])) : ?>

        <p>
            Data siswa berhasil dihapus.
        </p>

    <?php endif; ?>


    <?php if (mysqli_num_rows($result) > 0) : ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>

                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>DUDI</th>
                    <th>Guru Pembimbing</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while ($siswa = mysqli_fetch_assoc($result)) :

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($siswa['nisn']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($siswa['nama_lengkap']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($siswa['kelas']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($siswa['jurusan']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($siswa['dudi_magang']); ?>
                    </td>

                    <td>

                        <?php if ($siswa['nama_guru']) : ?>

                            <?= htmlspecialchars($siswa['nama_guru']); ?>

                        <?php else : ?>

                            Belum ditentukan

                        <?php endif; ?>

                    </td>

                    <td>

                        <a href="hapus_siswa.php?id=<?= $siswa['id_siswa']; ?>"
                           onclick="return confirm('Yakin ingin menghapus siswa ini?')">

                            Hapus

                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    <?php else : ?>

        <p>
            Belum ada data siswa.
        </p>

    <?php endif; ?>

</body>

</html>