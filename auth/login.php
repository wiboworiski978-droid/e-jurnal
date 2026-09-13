<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Jurnal SMK</title>
</head>

<body>

    <h1>E-Jurnal SMK</h1>

    <form action="proses_login.php" method="POST">

        <label>Login Sebagai</label>
        <select name="role" id="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="siswa">Siswa</option>
            <option value="guru">Guru</option>
            <option value="admin">Admin</option>
        </select>

        <br><br>

        <label id="labelUsername">NISN / NIP / Username</label>

        <input
            type="text"
            name="username"
            id="username"
            placeholder="Masukkan NISN"
            required
        >

        <br><br>

        <label id="labelPassword">Password</label>

        <input
            type="password"
            name="password"
            id="password"
            placeholder="Masukkan password"
            required
        >

        <br><br>

        <button type="submit">Login</button>

    </form>

    <script>
        const role = document.getElementById("role");
        const labelUsername = document.getElementById("labelUsername");
        const username = document.getElementById("username");

        role.addEventListener("change", function () {

            if (this.value === "siswa") {
                labelUsername.textContent = "NISN";
                username.placeholder = "Masukkan NISN";

            } else if (this.value === "guru") {
                labelUsername.textContent = "NIP";
                username.placeholder = "Masukkan NIP";

            } else if (this.value === "admin") {
                labelUsername.textContent = "Username";
                username.placeholder = "Masukkan username";

            } else {
                labelUsername.textContent = "NISN / NIP / Username";
                username.placeholder = "Masukkan data login";
            }

        });
    </script>

</body>
</html>