<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "SELECT
            guru.id_guru,
            guru.nip,
            guru.nama_guru,
            guru.created_at,
            COUNT(siswa.id_siswa) AS jumlah_siswa
          FROM guru
          LEFT JOIN siswa
              ON guru.id_guru = siswa.id_guru
          GROUP BY
              guru.id_guru,
              guru.nip,
              guru.nama_guru,
              guru.created_at
          ORDER BY guru.nama_guru ASC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Guru - E-Jurnal SMK</title>

</head>

<body>

    <h1>Kelola Guru</h1>

    <a href="dashboard.php">
        ← Kembali ke Dashboard
    </a>

    <br><br>

    <a href="tambah_guru.php">
        + Tambah Guru
    </a>

    <br><br>

    <?php if (isset($_GET['success'])) : ?>

        <p>
            Data guru berhasil ditambahkan.
        </p>

    <?php endif; ?>


    <?php if (isset($_GET['deleted'])) : ?>

        <p>
            Data guru berhasil dihapus.
        </p>

    <?php endif; ?>


    <?php if (isset($_GET['error'])) : ?>

        <p>
            <?= htmlspecialchars($_GET['error']); ?>
        </p>

    <?php endif; ?>


    <?php if (mysqli_num_rows($result) > 0) : ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>

                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Jumlah Siswa Bimbingan</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while ($guru = mysqli_fetch_assoc($result)) :

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($guru['nip']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($guru['nama_guru']); ?>
                    </td>

                    <td>
                        <?= $guru['jumlah_siswa']; ?> siswa
                    </td>

                    <td>

                        <a
                            href="hapus_guru.php?id=<?= $guru['id_guru']; ?>"
                            onclick="return confirm('Yakin ingin menghapus guru ini? Siswa yang dibimbing akan menjadi tanpa guru pembimbing.')"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    <?php else : ?>

        <p>
            Belum ada data guru.
        </p>

    <?php endif; ?>

</body>

</html>