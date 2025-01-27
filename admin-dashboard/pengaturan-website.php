<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';

if (isset($_POST['edit'])) {
    $PostStitle = $conn->real_escape_string(filter($_POST['short_title']));
    $PostTitle = $conn->real_escape_string(trim($_POST['title']));
    $PostNotif = $conn->real_escape_string(trim($_POST['wa_notif']));
    $PostDescWeb = $conn->real_escape_string(trim($_POST['deskripsi']));
    $PostKontak = $conn->real_escape_string(filter($_POST['kontak_utama']));
    $PostAlamat = $conn->real_escape_string(trim($_POST['alamat']));
    $PostUrlAlamat = $conn->real_escape_string(trim($_POST['url_alamat']));
    $PostFB = $conn->real_escape_string(filter($_POST['facebook']));
    $PostUrlFB = $conn->real_escape_string(trim($_POST['url_facebook']));
    $PostIG = $conn->real_escape_string(trim($_POST['instagram']));
    $PostUrlIG = $conn->real_escape_string(filter($_POST['url_instagram']));
    $PostWA = $conn->real_escape_string(trim($_POST['whatsapp']));
    $PostUrlWA = $conn->real_escape_string(trim($_POST['url_whatsapp']));
    $PostYT = $conn->real_escape_string(trim($_POST['youtube']));
    $PostUrlYT = $conn->real_escape_string(trim($_POST['url_youtube']));
    $PostTW = $conn->real_escape_string(trim($_POST['twitter']));
    $PostUrlTW = $conn->real_escape_string(trim($_POST['url_twitter']));
    $PostEmail = $conn->real_escape_string(trim($_POST['email']));
    $PostUrlEmail = $conn->real_escape_string(trim($_POST['url_email']));
    $PostJamKerja = $conn->real_escape_string(trim($_POST['jam_kerja']));

    if ($conn->query("UPDATE setting_web SET short_title = '$PostStitle', title = '$PostTitle', wa_notif = '$PostNotif', deskripsi_web = '$PostDescWeb', kontak_utama = '$PostKontak', alamat = '$PostAlamat', url_alamat = '$PostUrlAlamat', facebook = '$PostFB', url_facebook = '$PostUrlFB', instagram = '$PostIG', url_instagram = '$PostUrlIG', whatsapp = '$PostWA', url_whatsapp = '$PostUrlWA', youtube = '$PostYT', url_youtube = '$PostUrlYT', twitter = '$PostTW', url_twitter = '$PostUrlTW', email = '$PostEmail', url_email = '$PostUrlEmail', jam_kerja = '$PostJamKerja' WHERE id = '1'") == true) {
        $_SESSION['hasil'] = array(
            'alert' => 'success', 
            'judul' => 'Permintaan Berhasil', 
            'pesan' => '
            Pengaturan Website Telah Berhasil Diubah <br />                       
            ');                    
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
    }
}
?>  

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Pengaturan Profile dan Diskripsi Website</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<?php 
$CekData = $conn->query("SELECT * FROM setting_web WHERE id = '1'"); // edit
while ($ShowData = $CekData->fetch_assoc()) {
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i>    Pengaturan Website </b></h4>                             

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <tbody>
                            <tr>
                                <th width="200">Short Title</th>
                                <td><?php echo $ShowData['short_title']; ?></td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td><?php echo $ShowData['title']; ?></td>
                            </tr>
                            <tr>
                                <th>Whatsapp Notif</th>
                                <td><?php echo $ShowData['wa_notif']; ?></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td><?php echo $ShowData['deskripsi_web']; ?></td>
                            </tr>
                            <tr>
                                <th>Kontak</th>
                                <td><?php echo $ShowData['kontak_utama']; ?></td>
                            </tr>
                            <tr> 
                                <th>Alamat</th>
                                <td><?php echo $ShowData['alamat']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Maps</th>
                                <td><?php echo $ShowData['url_alamat']; ?></td>
                            </tr>
                            <tr>
                                <th>Facebook</th>
                                <td><?php echo $ShowData['facebook']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Facebook</th>
                                <td><?php echo $ShowData['url_facebook']; ?></td>
                            </tr>
                            <tr>
                                <th>Instagram</th>
                                <td><?php echo $ShowData['instagram']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Instagram</th>
                                <td><?php echo $ShowData['url_instagram']; ?></td>
                            </tr>
                            <tr>
                                <th>Whatsapp</th>
                                <td><?php echo $ShowData['whatsapp']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Whatsapp</th>
                                <td><?php echo $ShowData['url_whatsapp']; ?></td>
                            </tr>

                            <tr>
                                <th>Youtube</th>
                                <td><?php echo $ShowData['youtube']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Youtube</th>
                                <td><?php echo $ShowData['url_youtube']; ?></td>
                            </tr>
                            <tr>
                                <th>Twitter</th>
                                <td><?php echo $ShowData['twitter']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Twitter</th>
                                <td><?php echo $ShowData['url_twitter']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $ShowData['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Url Email</th>
                                <td><?php echo $ShowData['url_email']; ?></td>
                            </tr>
                            <tr>
                                <th>Jam Kerja</th>
                                <td><?php echo $ShowData['jam_kerja']; ?></td>
                            </tr>

                            <tr>
                                <th>Aksi</th>
                                <td>
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/pengaturan-website/edit?id=1')" class="btn btn-sm btn-warning"><i aria-hidden="true" class="fa fa-pencil" title="Edit"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    `   </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function users(url) {
        $.ajax({
            type: "GET",
            url: url,
            beforeSend: function() {
                $('#modal-detail-body').html('Sedang memuat...');
            },
            success: function(result) {
                $('#modal-detail-body').html(result);
            },
            error: function() {
                $('#modal-detail-body').html('Terjadi kesalahan.');
            }
        });
        $('#modal-detail').modal();
    }
</script> 

<div class="row">
    <div class="col-md-12">     
        <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mt-0" id="myModalLabel"><i aria-hidden="true" class="fa fa-gears"></i> Pengaturan Website</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-detail-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../lib/footer_admin.php';
?>