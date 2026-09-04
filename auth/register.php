<?php
session_start();
require '../config.php';

if (isset($_SESSION['user'])) {
    header("Location: ".$config['web']['url']);
    exit;
}

if (isset($_POST['daftar'])) {
    if (daftar($_POST) > 0) {
        $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Pendaftaran Berhasil!', 'pesan' => 'Silakan Login Akun Anda');
        header('Location: login');
        exit;
    }
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Data pendaftaran tidak valid atau username/email sudah digunakan.');
}

require '../lib/header.php';
?>

<title>Register <?php echo $data['short_title']; ?></title>
<meta name="description" content="Registrasi Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>
<meta content="Registrasi <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Registrasi Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card-box">
            <h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-user-plus"></i> Register <?php echo $data['short_title']; ?></h1>
            <div style="text-align: center;">
                <img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Register <?php echo $data['short_title']; ?>" title="Register <?php echo $data['short_title']; ?>" width="200">
            </div>
            <form id="daftar" class="form-horizontal m-t-20" role="form" method="POST" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($config['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group"><label>Nama</label><input id="nama" type="text" name="nama" class="form-control" placeholder="Nama Lengkap" autocomplete="name" required></div>
                <div class="form-group"><label>Email</label><input id="email" type="email" name="email" class="form-control" placeholder="Email Aktif" autocomplete="email" required></div>
                <div class="form-group"><label>No Whatsapp</label><input id="no_hp" type="tel" name="no_hp" class="form-control" placeholder="Whatsapp Aktif" autocomplete="tel" required></div>
                <div class="form-group"><label>Username</label><input id="user" type="text" name="username" class="form-control" placeholder="Username" autocomplete="username" required></div>
                <div class="form-group"><label>Password</label><span class="badge badge-success float-right" id="mybtn" role="button" tabindex="0" onclick="change()"><i class="mdi mdi-eye"></i> Show</span><input id="pass" type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" required></div>
                <div class="form-group"><label>Ulangi Password</label><input id="pass2" type="password" name="password2" class="form-control" placeholder="Konfirmasi Password" autocomplete="new-password" required></div>
                <button type="submit" class="btn btn-primary btn-block" name="daftar">Register</button>
                <br>
                <div style="text-align: center;"><i aria-hidden="true" class="fa fa-user"></i> Sudah punya akun?<a href="login" class="btn btn-success btn-block">Login</a></div>
            </form>
        </div>
    </div>
</div>

<script>
function change() {
    const fields = [document.getElementById('pass'), document.getElementById('pass2')];
    const visible = fields[0].type === 'password';
    fields.forEach(field => field.type = visible ? 'text' : 'password');
    document.getElementById('mybtn').innerHTML = visible ? '<i class="mdi mdi-eye-off"></i> Hide' : '<i class="mdi mdi-eye"></i> Show';
}
</script>

<?php require '../lib/footer.php'; ?>