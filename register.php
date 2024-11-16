<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $verifyCode = trim($_POST['verifycode']);

    // Validasi input
    if (empty($username) || empty($verifyCode)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Input tidak valid',
                text: 'Semua kolom harus diisi!'
            });
        </script>";
        exit;
    }

    // Koneksi database
    $servername = "127.0.0.1"; // Ganti dengan server database Anda
    $dbusername = "root";      // Ganti dengan username database Anda
    $dbpassword = "";          // Ganti dengan password database Anda
    $dbname = "gcrp"; // Ganti dengan nama database Anda

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek apakah username sudah ada
    $checkSql = "SELECT username FROM ucp WHERE username = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Registrasi gagal',
                text: 'Username sudah terdaftar!'
            });
        </script>";
    } else {
        // Masukkan data dengan kolom Password, Salt, dan lainnya kosong
        $insertSql = "INSERT INTO `ucp` (`username`, `verifemail`, `pin`, `DiscordID`) VALUES ('%e', '%d', '%d', '%s')", Nama_UCP, verif, hu, DiscordID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $currentTime = time();
        $stmt->bind_param("ssi", $username, $verifyCode, $currentTime);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi berhasil',
                    text: 'Akun berhasil didaftarkan!'
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi gagal',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
            </script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>