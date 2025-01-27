<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Diperlukan Autentikasi', 'pesan' => 'Silakan Login Terlebih Dahulu');
    session_destroy();
    exit(header("Location: ".$config['web']['url']."auth/login"));
}

if (isset($_SESSION['user'])) {
    $check_user = $conn->query("SELECT * FROM users WHERE username = '".$_SESSION['user']['username']."' AND password = '".$_SESSION['user']['password']."' AND level = 'Developers'");
    $data_user = $check_user->fetch_assoc();
    $check_username = $check_user->num_rows;

    // Check token
    $check_token = $conn->query("SELECT tokenuser FROM users_token WHERE username = '".$_SESSION['user']['username']."'");

    if ($check_username == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Diperlukan Autentikasi', 'pesan' => 'Silakan Login Kembali');
        session_destroy();
        exit(header("Location: ".$config['web']['url']."auth/login"));

    } else if ($data_user['status'] == "Tidak Aktif") {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Akun Telah Dinonaktifkan', 'pesan' => 'Hubungi Kontak Support Untuk Mengaktifkan Akun Anda');
        session_destroy();
        exit(header("Location: ".$config['web']['url']."auth/login"));
    }

    if (mysqli_num_rows($check_token) > 0) {
        $row = mysqli_fetch_array($check_token); 
        $tokenuser = $row['tokenuser']; 
        if($_SESSION['tokenuser'] != $tokenuser){
            session_destroy();
            exit(header("Location: ".$config['web']['url']."auth/login"));
        }
    }

    $sess_username = $_SESSION['user']['username'];
}

?>