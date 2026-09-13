<?php

session_start();

require_once __DIR__ . "/../config/koneksi.php";

$role = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($role) || empty($username) || empty($password)) {
    die("semua data wajib diisi.");
}

// login siswa
if ($role === "siswa") {

    $query = "SELECT * FROM siswa WHERE nisn = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $siswa = mysqli_fetch_assoc($result);

    if ($siswa && password_verify($password, $siswa['password'])) {

        $_SESSION['login'] = true;
        $_SESSION['role'] = "siswa";
        $_SESSION['id_siswa'] = $siswa['id_siswa'];
        $_SESSION['nama'] = $siswa['nama_lengkap'];

        header("Location: ../siswa/dashboard.php");
        exit;
    } else {
        
        header("Location: login.php?error=Login siswa gagal");
        exit;
    }
}

// login guru

if ($role === "guru") {
    $query = "SELECT * FROM guru WHERE nip = ?";

    $stmt =mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $guru = mysqli_fetch_assoc($result);

    if ($guru && password_verify($password, $guru['password'])) {

        $_SESSION['login'] = true;
        $_SESSION['role'] = "guru";
        $_SESSION['id_guru'] = $guru['id_guru'];
        $_SESSION['nama'] = $guru['nama_guru'];

        header("Location: ../guru/dashboard.php");
        exit;

    } else {
        header("Location: login.php?error=Login guru gagal");
        exit;
    }
}


// login admin
if ($role === "admin") {
    $query = "SELECT * FROM admin WHERE username = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['kode_unik'])) {
        
        $_SESSION['login'] = true;
        $_SESSION['role'] = "admin";
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['nama'] = $admin['username'];

        header("Location: ../admin/dahboard.php");
    } else {
        header("Location: login.php?error=Login admin gagal");
        exit;
    }
}

// role tidak valid
header("Location: login.php?error=Role tidak valid");
exit;