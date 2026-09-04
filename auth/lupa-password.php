<?php
session_start();
require '../config.php';
require '../lib/class.phpmailer.php';

if (isset($_SESSION['user'])) {
    header("Location: ".$config['web']['url']."user/setting");
    exit;
}

if (isset($_POST['reset'])) {
    $PostEmail = $conn->real_escape_string(filter(trim($_POST['email'] ?? '')));
    $PostUsername = $conn->real_escape_string(filter(trim($_POST['username'] ?? '')));

    if (!$PostEmail && !$PostUsername) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Isi Email atau Username.');
        header('Location: lupa-password');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param('ss', $PostUsername, $PostEmail);
    $stmt->execute();
    $useremail = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$useremail) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Email atau Username tidak terdaftar.');
        header('Location: lupa-password');
        exit;
    }

    $acakin_password = acak(10).acak_nomor(10);
    $hash_pass = password_hash($acakin_password, PASSWORD_DEFAULT);

    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPSecure = $config['email']['enkripsi'];
    $mail->Host = $config['email']['mailhost'];
    $mail->SMTPDebug = 0;
    $mail->Port = $config['email']['mailport'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['email']['mailusername'];
    $mail->Password = $config['email']['mailpassword'];
    $mail->SetFrom($config['email']['mailusername'], $data['short_title']);
    $mail->Subject = 'Reset Password Berhasil';
    $mail->AddAddress($useremail['email'], '');
    $mail->MsgHTML('<p>Hai '.htmlspecialchars($useremail['username'], ENT_QUOTES, 'UTF-8').'</p><p>Permintaan reset password telah diproses.</p><p>Email: '.htmlspecialchars($useremail['email'], ENT_QUOTES, 'UTF-8').'<br>Username: '.htmlspecialchars($useremail['username'], ENT_QUOTES, 'UTF-8').'<br>Password baru: <b>'.$acakin_password.'</b><br>Halaman Login: '.htmlspecialchars($config['web']['url_canonical'], ENT_QUOTES, 'UTF-8').'</p>');

    if (!$mail->Send()) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset password gagal!', 'pesan' => 'Email reset password tidak dapat dikirim.');
        header('Location: lupa-password');
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET password = ?, random_kode = ? WHERE id = ?");
    $stmt->bind_param('ssi', $hash_pass, $acakin_password, $useremail['id']);
    $updated = $stmt->execute();
    $stmt->close();

    if ($updated) {
        $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Reset password berhasil!', 'pesan' => 'Cek email untuk mengetahui password baru');
        header('Location: login');
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset password gagal!', 'pesan' => 'Permintaan ganti password gagal');
        header('Location: lupa-password');
    }
    exit;
}

require '../lib/header.php';
?>

<title>Reset Password <?php echo $data['short_title']; ?></title>
<meta name="description" content="Ganti Password Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>
<meta content="Ganti Password <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Ganti Password Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card-box">
            <h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-key"></i> Reset Password <?php echo $data['short_title']; ?></h1>
            <div style="text-align: center;"><img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Reset Password <?php echo $data['short_title']; ?>" title="Reset Password <?php echo $data['short_title']; ?>" width="200"></div>
            <form id="reset" class="form-horizontal m-t-20" role="form" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($config['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group"><label>Email</label><input id="email" type="email" name="email" class="form-control" placeholder="Email" autocomplete="email"></div>
                <center><b>ATAU</b></center>
                <div class="form-group"><label>Username</label><input id="user" type="text" name="username" class="form-control" placeholder="Username" autocomplete="username"></div>
                <button type="submit" class="btn btn-danger btn-block" name="reset">Reset</button>
                <br/>
                <div style="text-align: center;"><i aria-hidden="true" class="fa fa-user"></i> Sudah ingat password?<a href="login" class="btn btn-success btn-block">Login</a></div>
            </form>
        </div>
    </div>
</div>

<?php require '../lib/footer.php'; ?>