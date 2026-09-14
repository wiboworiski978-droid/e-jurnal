<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../auth/login.php");
    exit;
}

$id_guru = $_SESSION['id_guru'];


// Ambil data guru
$queryGuru = "SELECT * FROM guru WHERE id_guru = ?";

$stmtGuru = mysqli_prepare($conn, $queryGuru);
mysqli_stmt_bind_param($stmtGuru, "i", $id_guru);
mysqli_stmt_execute($stmtGuru);

$resultGuru = mysqli_stmt_get_result($stmtGuru);
$guru = mysqli_fetch_assoc($resultGuru);


// Ambil siswa yang dibimbing guru
$querySiswa = "SELECT *
               FROM siswa
               WHERE id_guru = ?
               ORDER BY nama_lengkap ASC";

$stmtSiswa = mysqli_prepare($conn, $querySiswa);
mysqli_stmt_bind_param($stmtSiswa, "i", $id_guru);
mysqli_stmt_execute($stmtSiswa);

$resultSiswa = mysqli_stmt_get_result($stmtSiswa);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Jurnal - E-Jurnal SMK</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin-bottom: 5px;
        }

        .header p {
            margin: 3px;
        }

        .siswa {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        th {
            text-align: center;
        }

        .foto {
            width: 100px;
        }

        .foto img {
            width: 90px;
            height: auto;
        }

        .tombol {
            margin-bottom: 20px;
        }

        @media print {

            .tombol {
                display: none;
            }

            body {
                margin: 15px;
            }

        }

    </style>

</head>

<body>


<div class="tombol">

    <button onclick="window.print()">
        Cetak Laporan
    </button>

    <button onclick="window.history.back()">
        Kembali
    </button>

</div>


<div class="header">

    <h1>LAPORAN JURNAL PKL</h1>

    <p>
        E-Jurnal SMK
    </p>

    <p>
        Guru Pembimbing:
        <?= htmlspecialchars($guru['nama_guru']); ?>
    </p>

    <p>
        NIP:
        <?= htmlspecialchars($guru['nip']); ?>
    </p>

</div>


<?php

if (mysqli_num_rows($resultSiswa) > 0) :

    while ($siswa = mysqli_fetch_assoc($resultSiswa)) :

        $id_siswa = $siswa['id_siswa'];


        // Ambil jurnal siswa
        $queryJurnal = "SELECT *
                        FROM jurnal
                        WHERE id_siswa = ?
                        ORDER BY tanggal_kegiatan ASC";

        $stmtJurnal = mysqli_prepare($conn, $queryJurnal);

        mysqli_stmt_bind_param(
            $stmtJurnal,
            "i",
            $id_siswa
        );

        mysqli_stmt_execute($stmtJurnal);

        $resultJurnal = mysqli_stmt_get_result($stmtJurnal);

?>

<div class="siswa">

    <h2>
        <?= htmlspecialchars($siswa['nama_lengkap']); ?>
    </h2>

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


    <?php if (mysqli_num_rows($resultJurnal) > 0) : ?>

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Tanggal</th>

                    <th>Nama DUDI</th>

                    <th>Pembimbing</th>

                    <th>Kegiatan Magang</th>

                    <th>Keterangan</th>

                    <th>Foto</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            while ($jurnal = mysqli_fetch_assoc($resultJurnal)) :

            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['tanggal_kegiatan']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['nama_dudi']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($jurnal['pembimbing']); ?>
                    </td>

                    <td>
                        <?= nl2br(htmlspecialchars($jurnal['kegiatan_magang'])); ?>
                    </td>

                    <td>
                        <?= nl2br(htmlspecialchars($jurnal['keterangan'])); ?>
                    </td>

                    <td class="foto">

                        <?php if (!empty($jurnal['foto'])) : ?>

                            <img
                                src="../uploads/jurnal/<?= htmlspecialchars($jurnal['foto']); ?>"
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
            Belum ada jurnal untuk siswa ini.
        </p>

    <?php endif; ?>

</div>


<?php

    endwhile;

else :

?>

    <p>
        Belum ada siswa yang dibimbing oleh guru ini.
    </p>

<?php endif; ?>


</body>

</html>