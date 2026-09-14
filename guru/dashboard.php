<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";


// ============================
// CEK LOGIN
// ============================

if (
    !isset($_SESSION['login']) ||
    $_SESSION['role'] !== 'guru'
) {
    header("Location: ../auth/login.php");
    exit;
}


$id_guru = $_SESSION['id_guru'];


// ============================
// AMBIL DATA GURU
// ============================

$query_guru = "SELECT *
               FROM guru
               WHERE id_guru = ?";

$stmt_guru = mysqli_prepare(
    $conn,
    $query_guru
);

mysqli_stmt_bind_param(
    $stmt_guru,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmt_guru);

$result_guru = mysqli_stmt_get_result(
    $stmt_guru
);

$guru = mysqli_fetch_assoc(
    $result_guru
);


// ============================
// JUMLAH SISWA BIMBINGAN
// ============================

$query_siswa = "SELECT COUNT(*) AS total
                FROM siswa
                WHERE id_guru = ?";

$stmt_siswa = mysqli_prepare(
    $conn,
    $query_siswa
);

mysqli_stmt_bind_param(
    $stmt_siswa,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmt_siswa);

$result_siswa = mysqli_stmt_get_result(
    $stmt_siswa
);

$total_siswa = mysqli_fetch_assoc(
    $result_siswa
)['total'];


// ============================
// JUMLAH JURNAL SISWA BIMBINGAN
// ============================

$query_jurnal = "SELECT COUNT(*) AS total
                 FROM jurnal j
                 INNER JOIN siswa s
                    ON j.id_siswa = s.id_siswa
                 WHERE s.id_guru = ?";

$stmt_jurnal = mysqli_prepare(
    $conn,
    $query_jurnal
);

mysqli_stmt_bind_param(
    $stmt_jurnal,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmt_jurnal);

$result_jurnal = mysqli_stmt_get_result(
    $stmt_jurnal
);

$total_jurnal = mysqli_fetch_assoc(
    $result_jurnal
)['total'];


// ============================
// JUMLAH BIMBINGAN
// ============================

$query_bimbingan = "SELECT COUNT(*) AS total
                     FROM bimbingan
                     WHERE id_guru = ?";

$stmt_bimbingan = mysqli_prepare(
    $conn,
    $query_bimbingan
);

mysqli_stmt_bind_param(
    $stmt_bimbingan,
    "i",
    $id_guru
);

mysqli_stmt_execute($stmt_bimbingan);

$result_bimbingan = mysqli_stmt_get_result(
    $stmt_bimbingan
);

$total_bimbingan = mysqli_fetch_assoc(
    $result_bimbingan
)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Guru - E-Jurnal SMK</title>

</head>

<body>


    <!-- HEADER -->

    <header>

        <h1>E-Jurnal SMK</h1>

        <p>
            Guru: 
            <?= htmlspecialchars($guru['nama_guru']); ?>
        </p>

        <a href="../auth/logout.php">
            Logout
        </a>

    </header>


    <!-- DASHBOARD -->

    <main>

        <h2>Dashboard Guru</h2>


        <!-- INFORMASI GURU -->

        <section>

            <h3>
                Selamat datang,
                <?= htmlspecialchars($guru['nama_guru']); ?>
            </h3>

        </section>


        <!-- STATISTIK -->

        <section>

            <div>

                <h3>Siswa Bimbingan</h3>

                <p>
                    <?= $total_siswa; ?>
                </p>

                <a href="siswa.php">
                    Lihat Siswa
                </a>

            </div>


            <div>

                <h3>Total Jurnal</h3>

                <p>
                    <?= $total_jurnal; ?>
                </p>

                <a href="siswa.php">
                    Lihat Jurnal
                </a>

            </div>


            <div>

                <h3>Total Bimbingan</h3>

                <p>
                    <?= $total_bimbingan; ?>
                </p>

                <a href="riwayat_bimbingan.php">
                    Lihat Bimbingan
                </a>

            </div>

        </section>


        <!-- MENU -->

        <section>

            <h3>Menu</h3>

            <a href="siswa.php">
                Siswa Bimbingan
            </a>

            <br><br>

            <a href="tambah_bimbingan.php">
                Tambah Bimbingan
            </a>

            <br><br>

            <a href="riwayat_bimbingan.php">
                Riwayat Bimbingan
            </a>

        </section>

    </main>


    <!-- FOOTER -->

    <footer>

        <p>
            E-Jurnal SMK
        </p>

    </footer>


</body>

</html>