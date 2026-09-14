<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";


if (
    !isset($_SESSION['login']) ||
    $_SESSION['role'] !== 'guru'
) {
    header("Location: ../auth/login.php");
    exit;
}


$id_guru = $_SESSION['id_guru'];


// Ambil siswa yang dibimbing guru
$query = "SELECT *
          FROM siswa
          WHERE id_guru = ?
          ORDER BY nama_lengkap ASC";

$stmt = mysqli_prepare(
    $conn,
    $query
);

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Siswa Bimbingan - E-Jurnal SMK</title>

</head>

<body>

    <h1>Siswa Bimbingan</h1>


    <table border="1">

        <thead>

            <tr>

                <th>No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>DUDI Magang</th>
                <th>Aksi</th>

            </tr>

        </thead>


        <tbody>

            <?php

            $no = 1;

            if (mysqli_num_rows($result) > 0):

            ?>

                <?php while ($siswa = mysqli_fetch_assoc($result)): ?>

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

                            <a
                                href="jurnal_siswa.php?id=<?= $siswa['id_siswa']; ?>"
                            >
                                Lihat Jurnal
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">
                        Belum ada siswa bimbingan.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>


    <br>

    <a href="dashboard.php">
        Kembali ke Dashboard
    </a>

</body>

</html>