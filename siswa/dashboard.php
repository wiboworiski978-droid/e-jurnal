<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

// Cek apakah sudah login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];

// Ambil data siswa
$query = "SELECT 
            siswa.*,
            guru.nama_guru
          FROM siswa
          LEFT JOIN guru 
            ON siswa.id_guru = guru.id_guru
          WHERE siswa.id_siswa = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);


// Ambil jurnal siswa
$query_jurnal = "SELECT *
                 FROM jurnal
                 WHERE id_siswa = ?
                 ORDER BY tanggal_kegiatan DESC";

$stmt_jurnal = mysqli_prepare($conn, $query_jurnal);
mysqli_stmt_bind_param($stmt_jurnal, "i", $id_siswa);
mysqli_stmt_execute($stmt_jurnal);

$result_jurnal = mysqli_stmt_get_result($stmt_jurnal);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Siswa - E-Jurnal SMK</title>

</head>

<body>

    <!-- HEADER -->

    <header>

        <h1>E-Jurnal SMK</h1>

        <p>
            Siswa: <?= htmlspecialchars($siswa['nama_lengkap']); ?>
        </p>

        <a href="../auth/logout.php">
            Logout
        </a>

    </header>


    <!-- INFORMASI SISWA -->

    <main>

        <h2>Dashboard Siswa</h2>

        <section>

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
                <strong>Tempat Magang:</strong>
                <?= htmlspecialchars($siswa['dudi_magang']); ?>
            </p>

            <p>
                <strong>Guru Pembimbing:</strong>
                <?= htmlspecialchars($siswa['nama_guru'] ?? '-'); ?>
            </p>

        </section>


        <!-- TOMBOL TAMBAH JURNAL -->

        <a href="tambah_jurnal.php">
            Isi Jurnal
        </a>


        <!-- RIWAYAT JURNAL -->

        <h2>Riwayat Jurnal</h2>

        <table border="1">

            <thead>

                <tr>
                    <th>Tanggal</th>
                    <th>DUDI</th>
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result_jurnal) > 0): ?>

                    <?php while ($jurnal = mysqli_fetch_assoc($result_jurnal)): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($jurnal['tanggal_kegiatan']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($jurnal['nama_dudi']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($jurnal['kegiatan_magang']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($jurnal['keterangan']); ?>
                            </td>

                            <td>

                                <?php if (!empty($jurnal['foto'])): ?>

                                    <img
                                        src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
                                        width="100"
                                    >

                                <?php else: ?>

                                    Tidak ada foto

                                <?php endif; ?>

                            </td>

                            <td>

                                <a href="edit_jurnal.php?id=<?= $jurnal['id_jurnal']; ?>">
                                    Edit
                                </a>

                                |

                                <a
                                    href="hapus_jurnal.php?id=<?= $jurnal['id_jurnal']; ?>"
                                    onclick="return confirm('Yakin ingin menghapus jurnal ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6">
                            Belum ada jurnal.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </main>


    <!-- FOOTER -->

    <footer>

        <p>
            E-Jurnal SMK
        </p>

    </footer>

</body>

</html>