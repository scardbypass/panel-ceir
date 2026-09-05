<?php
session_start();
include '../config.php';

if (isset($_SESSION['user'])) {
    header("Location: ".$config['web']['url']);
    exit;
}

if (isset($_POST['login'])) {
    $post_username = $conn->real_escape_string(trim(filter($_POST['username'] ?? '')));
    $post_password = trim($_POST['password'] ?? '');

    if (!$post_username || !$post_password) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Username<br/> - Password');
    } else {
        $check_user = $conn->query("SELECT * FROM users WHERE username = '$post_username' LIMIT 1");
        $data_user = $check_user ? mysqli_fetch_assoc($check_user) : null;

        if (!$data_user) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Username / Password Salah');
        } else if ($data_user['status'] == 'Tidak Aktif') {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Akun Telah Dinonaktifkan');
        } else if (!password_verify($post_password, $data_user['password'])) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Username / Password Salah');
        } else {
            $tokenuser = bin2hex(random_bytes(16));
            $_SESSION['user'] = $data_user;
            $_SESSION['tokenuser'] = $tokenuser;
            $tanggal = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("SELECT COUNT(*) FROM users_token WHERE username = ?");
            $stmt->bind_param('s', $post_username);
            $stmt->execute();
            $row_token = $stmt->get_result()->fetch_row();
            $stmt->close();

            if ((int)$row_token[0] > 0) {
                $stmt = $conn->prepare("UPDATE users_token SET tokenuser = ? WHERE username = ?");
                $stmt->bind_param('ss', $tokenuser, $post_username);
            } else {
                $stmt = $conn->prepare("INSERT INTO users_token (username, tokenuser, tanggal) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $post_username, $tokenuser, $tanggal);
            }
            $stmt->execute();
            $stmt->close();

            $ip = get_client_ip();
            $aksi = 'Login';
            $stmt = $conn->prepare("INSERT INTO log (username, aksi, ip, date, time) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $post_username, $aksi, $ip, $date, $time);
            $stmt->execute();
            $stmt->close();

            header("Location: ".$config['web']['url']);
            exit;
        }
    }
}

require '../lib/header.php';
?>

<title>Login <?php echo $data['short_title']; ?></title>
<meta name="description" content="Login Member Area <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>
<meta content="Login <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Login Member Area <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card-box">
            <h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-user"></i> Login <?php echo $data['short_title']; ?></h1>
            <div style="text-align: center;">
                <img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Login <?php echo $data['short_title']; ?>" title="Login <?php echo $data['short_title']; ?>" width="200">
            </div>
            <form id="login" class="form-horizontal m-t-20" role="form" method="POST" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($config['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <label>Username</label>
                    <input id="user" name="username" type="text" class="form-control" placeholder="Username" autocomplete="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <span class="badge badge-success float-right" id="mybtn" role="button" tabindex="0" onclick="change()"><i class="mdi mdi-eye"></i> Show</span>
                    <input id="pass" name="password" type="password" class="form-control" placeholder="Password" autocomplete="current-password" required>
                </div>
                <button id="login-button" name="login" type="submit" class="btn btn-success btn-block">Login</button>
                <br/>
                <div style="text-align: center;">
                    <i aria-hidden="true" class="fa fa-key"></i> Lupa password?
                    <a href="lupa-password" class="btn btn-danger btn-block">Reset</a>
                    <br/>
                    <i aria-hidden="true" class="fa fa-user-plus"></i> Belum punya akun?
                    <a href="register" class="btn btn-primary btn-block">Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function change() {
    const pass = document.getElementById('pass');
    const btn = document.getElementById('mybtn');
    const visible = pass.type === 'password';
    pass.type = visible ? 'text' : 'password';
    btn.innerHTML = visible ? '<i class="mdi mdi-eye-off"></i> Hide' : '<i class="mdi mdi-eye"></i> Show';
}
</script>

<?php require '../lib/footer.php'; ?>