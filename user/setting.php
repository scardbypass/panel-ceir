<?php 
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';

if (isset($_POST['ganti_pass'])) {
  $password = $conn->real_escape_string(trim($_POST['password_lama']));
  $password_baru = $conn->real_escape_string(trim($_POST['password_baru']));
  $konfirmasi_baru = $conn->real_escape_string(trim($_POST['konf_pass_baru']));

  $cek_passwordnya = password_verify($password, $data_user['password']);
  $hash_passwordnya = password_hash($password_baru, PASSWORD_DEFAULT);
  if (!$password || !$password_baru || !$konfirmasi_baru) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Password <br /> - Password Baru <br /> - Konfirmasi Password Baru');
  } else if ($cek_passwordnya == false) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Password Lama Yang Anda Masukkan Tidak Sesuai');
  } else if (strlen($password_baru) < 5 ){
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Password Baru Minimal 5 Karakter Tanpa Spasi');
  } else if ($password_baru <> $konfirmasi_baru){
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Konfirmasi Password Baru Tidak Sesuai');
  } else {

    if ($conn->query("UPDATE users SET password = '$hash_passwordnya' WHERE username = '$sess_username'") == true) {
      $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Password Berhasil Diubah. Silakan Login Kembali');
    } else { 
      $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan gagal');
    }
  }

} else if (isset($_POST['setting_nama'])) {
  $password = $conn->real_escape_string(trim($_POST['password']));
  $nama_baru = $conn->real_escape_string(trim(filter($_POST['nama'])));
  $cek_passwordnya = password_verify($password, $data_user['password']);
  if (!$password || !$nama_baru) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Nama <br /> - Password');
  } else if ($cek_passwordnya == false) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Password Yang Anda Masukkan Tidak Sesuai');
  } else {
    if ($conn->query("UPDATE users SET nama = '$nama_baru', update_nama = '0' WHERE username = '$sess_username'") == true) {
      $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Nama Berhasil Diubah');
    } else { 
      $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan gagal');
    }
  }

} else if (isset($_POST['ganti_apinya'])) {
  $api_barunya = acak(32);
  if ($conn->query("UPDATE users SET api_key = '$api_barunya' WHERE username = '$sess_username'") == true) {
    $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Api Key Berhasil Diubah');
  } else { 
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan gagal');
  }
}

require '../lib/header.php';
?>

<!--Title-->
<title>Pengaturan Akun</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <center><img src="/assets/images/kitadigital/webkmpanelblack.png" alt="user-img" class="card-img-top"></img></center>    
        <div class="caption p-2">
          <center><h4 class="header-title m-t-0 m-b-30"><i class="fas fa-cogs"></i><b> INFORMASI AKUN</b></h4></center>
        </div>      
        <div class="caption p-2"> 
          <div style="text-align:center; margin-top: -15px !important; margin-bottom: 15px !important;">
            <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/halaman/platform-aplikasi" target="_blank" class="btn btn-xs btn-primary"> <i aria-hidden="true" class="fa fa-download"></i> Unduh </a>
            <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/" target="_blank" class="btn btn-xs btn-primary"> <i aria-hidden="true" class="fa fa-rss"></i> Beranda </a>
          </div>
          Nama: <b><?php echo $data_user['nama']; ?></b><br />
          Username: <b><?php echo $sess_username; ?></b><br />
          Email: <b><?php echo $data_user['email']; ?></b><br />
          Whatsapp: <b><?php echo $data_user['no_hp']; ?></b><br />
          Level: <b><?php echo $data_user['level']; ?></b><br />  
          Uplink: <b><?php echo $data_user['uplink']; ?></b><br /><br/>
          Saldo: <b>Rp. <?php echo number_format($data_user['saldo'],0,',','.'); ?></b><br/>
          Bonus: <b>Rp. 0</b><br/><br/>
          <div style="text-align:center; margin-top: -15px !important; margin-bottom: -15px !important;">
            <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/pay" class="btn btn-xs btn-primary"> <i class="fas fa-plus-circle"></i> Deposit </a>
            <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/pemesanan/e-money" class="btn btn-xs btn-primary"> <i class="fas fa-minus-circle"></i> Withdraw </a>
          </div>
        </div>                                 	
      </div>
    </div>
  </div>                                         

  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <div class="caption p-2">
          <center><h4 class="header-title m-t-0 m-b-30"><i class="fas fa-cogs"></i><b> PENGATURAN AKUN</b></h4></center>
        </div>
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="nama-tab" data-toggle="tab" href="#nama" role="tab" aria-controls="nama" aria-selected="true">
              <span class="d-block d-sm-none"><i aria-hidden="true" class="fa fa-user"></i></span>
              <span class="d-none d-sm-block">Ganti Nama</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">
              <span class="d-block d-sm-none"><i class="mdi mdi-account-key"></i></span>
              <span class="d-none d-sm-block">Ganti Password</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="api-doc-tab" data-toggle="tab" href="#api_doc" role="tab" aria-controls="api_doc" aria-selected="false">
              <span class="d-block d-sm-none"><i class="mdi mdi-account-network"></i></span>
              <span class="d-none d-sm-block">Ganti API</span>
            </a>
          </li>                                    
        </ul>
        <div class="tab-content">
          <div class="tab-pane show active" id="nama" role="nama" aria-labelledby="nama-tab">
            <form class="form-horizontal" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
              <?php
              $CallUsers = $conn->query("SELECT * FROM users WHERE username = '$sess_username'");
              while ($DataUsers = $CallUsers->fetch_assoc()) {
                if ($DataUsers['update_nama'] == "1") {
                  $cek = ''; 
                  $disabled = '';
                } else if ($DataUsers['update_nama'] == "0") {
                  $cek = ''; 
                  $disabled = '';
                }
                ?>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama" value="<?php echo $DataUsers['nama'] ?>" <?php echo $cek; ?>>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Password</label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control" name="password" <?php echo $cek; ?>>
                  </div>
                </div>
                <div class="form-group row m-b-0">
                  <div class="offset-sm-3 col-sm-9">
                    <button type="submit" name="setting_nama" class="btn btn-xs btn-primary waves-effect waves-light" <?php echo $disabled; ?>><i class="mdi mdi-check-all"></i> Ganti </button>
                    <button type="refresh" class="btn btn-xs btn-danger waves-effect waves-light" ><i aria-hidden="true" class="fa fa-refresh"></i> Ulangi</button>
                  </div>
                </div>
              <?php } ?>                                    
            </form>
          </div>
          <div class="tab-pane" id="password" role="tabpanel" aria-labelledby="password-tab">
            <form class="form-horizontal" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
              <div class="form-group row">
                <label class="col-sm-3 control-label">Password Lama</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="password_lama" placeholder="Password Lama">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label">Password Baru</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="password_baru" placeholder="Password Baru">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 control-label">Ulangi Password Baru</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="konf_pass_baru" placeholder="Ulangi Password Baru">
                </div>
              </div>
              <div class="form-group row">
                <div class="offset-sm-3 col-sm-9">
                  <button type="submit" name="ganti_pass" class="btn btn-xs btn-primary waves-effect waves-light"><i class="mdi mdi-check-all"></i> Ganti</button>
                  <button type="refresh" class="btn btn-xs btn-danger waves-effect waves-light"><i aria-hidden="true" class="fa fa-refresh"></i> Ulangi</button>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="api_doc" role="api_doc" aria-labelledby="api-doc-tab">
            <form class="form-horizontal" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
              <div class="form-group row">
                <label class="col-sm-3 control-label">API Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="api" value="<?php echo $data_user['api_key']; ?>" readonly>
                </div>
              </div>
              <div class="form-group row m-b-0">
                <div class="offset-sm-3 col-sm-9">
                  <button type="submit" name="ganti_apinya" class="btn btn-xs btn-primary waves-effect waves-light"><i class="mdi mdi-shuffle-variant"></i> Ganti</button>
                </div>
              </div>
            </form>
            <div class="card-body">
              <center><h4 class="header-title m-t-0 m-b-30"><i class="fas fa-cogs"></i><b> DOKUMENTASI API</b></h4></center>
              <div style="text-align:center; margin-top: 15px !important; margin-bottom: 15px !important;">
                <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/halaman/api-dokumentasi-profile" class="btn btn-xs btn-primary"> <i aria-hidden="true" class="fa fa-fire"></i> API Profil </a>
                <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/halaman/api-dokumentasi-pulsa" class="btn btn-xs btn-primary"> <i aria-hidden="true" class="fa fa-fire"></i> API Pulsa </a>
                <a style="margin-top:2.5px; margin-bottom:2.5px;" href="/halaman/api-dokumentasi-sosmed" class="btn btn-xs btn-primary"> <i aria-hidden="true" class="fa fa-fire"></i> API Sosmed </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-4">
    <div class="widget-rounded-circle card-box">
      <div class="row">
        <div class="col-6">
          <div class="avatar-lg rounded-circle bg-soft-danger">
            <i class="ri-shopping-cart-line font-24 avatar-title text-danger"></i>
          </div>
        </div>
        <div class="col-6">
          <div class="text-right">
            <h4 class="text-dark mt-1"><span><?php echo $jumlah_order_sosmed+$jumlah_order_pulsa; ?></span></h4>
            <p class="text-muted mb-1 text-truncate">Jumlah Orderan</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-4">
    <div class="widget-rounded-circle card-box">
      <div class="row">
        <div class="col-6">
          <div class="avatar-lg rounded-circle bg-soft-warning">
            <i class="ri-shopping-cart-line font-24 avatar-title text-warning"></i>
          </div>
        </div>
        <div class="col-6">
          <div class="text-right">
            <h4 class="text-dark mt-1"><span>Rp <?php echo number_format($data_user['pemakaian_saldo'],0,',','.'); ?></span></h4>
            <p class="text-muted mb-1 text-truncate">Pemakaian Saldo</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-4">
    <div class="widget-rounded-circle card-box">
      <div class="row">
        <div class="col-6">
          <div class="avatar-lg rounded-circle bg-soft-success">
            <i class="ti-wallet font-24 avatar-title text-success"></i>
          </div>
        </div>
        <div class="col-6">
          <div class="text-right">
            <h4 class="text-dark mt-1"><span><?php echo $jumlah_deposit_user; ?></span></h4>
            <p class="text-muted mb-1 text-truncate">Jumlah Deposit</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
</div>
</div> <!-- end col -->                   
</div>                
<?php
require '../lib/footer.php';
?>