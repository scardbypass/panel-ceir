<?php
session_start();
require '../../../config.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_SESSION['user'])) {
		exit("Anda Tidak Memiliki Akses!");
	}
	if (!isset($_GET['id'])) {
		exit("Anda Tidak Memiliki Akses!");
	} 		
	$GetID = $conn->real_escape_string(filter($_GET['id']));
	$CekData = $conn->query("SELECT * FROM setting_web WHERE id = '$GetID'");
	$Datanya = $CekData->fetch_assoc();
	if (mysqli_num_rows($CekData) == 0) {
		exit("Data Tidak Ditemukan");
	} else {
		?>										
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" role="form" method="POST">
					<div class="form-group">
						<label>Short Title</label>
						<input type="text" name="short_title" class="form-control" value="<?php echo $Datanya['short_title']; ?>">
					</div>
					<div class="form-group">
						<label>Title</label>
						<input type="text" name="title" class="form-control" value="<?php echo $Datanya['title']; ?>">
					</div>
					<div class="form-group">
						<label>Whatsapp Notif</label>
						<input type="text" name="wa_notif" class="form-control" value="<?php echo $Datanya['wa_notif']; ?>">
					</div>
					<div class="form-group">
						<label>Deskripsi</label>
						<textarea type="text" name="deskripsi" class="form-control"><?php echo $Datanya['deskripsi_web']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Kontak</label>
						<input type="text" name="kontak_utama" class="form-control" value="<?php echo $Datanya['kontak_utama']; ?>">
					</div>
					<div class="form-group">
						<label>Alamat</label>
						<input type="text" name="alamat" class="form-control" value="<?php echo $Datanya['alamat']; ?>">
					</div>
					<div class="form-group">
						<label>Url Maps</label>
						<input type="text" name="url_alamat" class="form-control" value="<?php echo $Datanya['url_alamat']; ?>">
					</div>
					<div class="form-group">
						<label>Facebook</label>
						<input type="text" name="facebook" class="form-control" value="<?php echo $Datanya['facebook']; ?>">
					</div>
					<div class="form-group">
						<label>Url Facebook</label>
						<input type="text" name="url_facebook" class="form-control" value="<?php echo $Datanya['url_facebook']; ?>">
					</div>
					<div class="form-group">
						<label>Instagram</label>
						<input type="text" name="instagram" class="form-control" value="<?php echo $Datanya['instagram']; ?>">
					</div>
					<div class="form-group">
						<label>Url Instagram</label>
						<input type="text" name="url_instagram" class="form-control" value="<?php echo $Datanya['url_instagram']; ?>">
					</div>
					<div class="form-group">
						<label>Whatsapp</label>
						<input type="text" name="whatsapp" class="form-control" value="<?php echo $Datanya['whatsapp']; ?>">
					</div>
					<div class="form-group">
						<label>Url Whatsapp</label>
						<input type="text" name="url_whatsapp" class="form-control" value="<?php echo $Datanya['url_whatsapp']; ?>">
					</div>

					<div class="form-group">
						<label>Youtube</label>
						<input type="text" name="youtube" class="form-control" value="<?php echo $Datanya['youtube']; ?>">
					</div>
					<div class="form-group">
						<label>Url Youtube</label>
						<input type="text" name="url_youtube" class="form-control" value="<?php echo $Datanya['url_youtube']; ?>">
					</div>
					<div class="form-group">
						<label>Twitter</label>
						<input type="text" name="twitter" class="form-control" value="<?php echo $Datanya['twitter']; ?>">
					</div>
					<div class="form-group">
						<label>Url Twitter</label>
						<input type="text" name="url_twitter" class="form-control" value="<?php echo $Datanya['url_twitter']; ?>">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" name="email" class="form-control" value="<?php echo $Datanya['email']; ?>">
					</div>
					<div class="form-group">
						<label>Url Email</label>
						<input type="text" name="url_email" class="form-control" value="<?php echo $Datanya['url_email']; ?>">
					</div>
					<div class="form-group">
						<label>Jam Kerja</label>
						<input type="text" name="jam_kerja" class="form-control" value="<?php echo $Datanya['jam_kerja']; ?>">
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-warning waves-effect w-md waves-light" name="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Update</button>
					</div>
				</form>
			</div>
		</div>
		<?php 
	}
} else {
	exit("Anda Tidak Memiliki Akses!");
}