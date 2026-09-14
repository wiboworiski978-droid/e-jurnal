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

$id_siswa = $_GET['id'] ?? 0;


// ============================
// CEK SISWA
// ============================

// Penting:
// Guru hanya boleh melihat siswa
// yang memang menjadi bimbingannya.

$query_siswa = "SELECT *
                FROM siswa
                WHERE id_siswa = ?
                AND id_guru = ?";

$stmt_siswa = mysqli_prepare(
    $conn,
    $query_siswa
);

mysqli_stmt_bind_param(
    $stmt_siswa,
    "ii",
    $id_siswa,
    $id_guru
);

mysqli_stmt_execute($stmt_siswa);

$result_siswa = mysqli_stmt_get_result(
    $stmt_siswa
);

$siswa = mysqli_fetch_assoc(
    $result_siswa
);


if (!$siswa) {
    die("Siswa tidak ditemukan atau bukan siswa bimbingan Anda.");
}


// ============================
// AMBIL JURNAL
// ============================

$query_jurnal = "SELECT *
                 FROM jurnal
                 WHERE id_siswa = ?
                 ORDER BY tanggal_kegiatan DESC";

$stmt_jurnal = mysqli_prepare(
    $conn,
    $query_jurnal
);

mysqli_stmt_bind_param(
    $stmt_jurnal,
    "i",
    $id_siswa
);

mysqli_stmt_execute($stmt_jurnal);

$result_jurnal = mysqli_stmt_get_result(
    $stmt_jurnal
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Jurnal Siswa - E-Jurnal SMK</title>

</head>

<body>


    <h1>Jurnal Siswa</h1>


    <!-- DATA SISWA -->

    <section>

        <p>
            <strong>Nama:</strong>
            <?= htmlspecialchars($siswa['nama_lengkap']); ?>
        </p>

        <p>
            <strong>NISN:</strong>
            <?= htmlspecialchars($siswa['nisn']); ?>
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

    </section>


    <hr>


    <!-- TABEL JURNAL -->

    <table border="1">

        <thead>

            <tr>

                <th>No</th>
                <th>Tanggal</th>
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

            if (mysqli_num_rows($result_jurnal) > 0):

            ?>

                <?php while ($jurnal = mysqli_fetch_assoc($result_jurnal)): ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $jurnal['tanggal_kegiatan']
                            ); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $jurnal['nama_dudi']
                            ); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $jurnal['pembimbing']
                            ); ?>
                        </td>

                        <td>
                            <?= nl2br(
                                htmlspecialchars(
                                    $jurnal['kegiatan_magang']
                                )
                            ); ?>
                        </td>

                        <td>
                            <?= nl2br(
                                htmlspecialchars(
                                    $jurnal['keterangan']
                                )
                            ); ?>
                        </td>

                        <td>

                            <?php if (!empty($jurnal['foto'])): ?>

                                <img
                                    src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
                                    width="120"
                                >

                            <?php else: ?>

                                Tidak ada foto

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">
                        Siswa belum memiliki jurnal.
                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>


    <br>


    <a href="siswa.php">
        Kembali ke Daftar Siswa
    </a>


</body>

</html>