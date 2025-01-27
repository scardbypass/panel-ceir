<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Dokumentasi API Profil di <?php echo $data['short_title']; ?></title>
<meta name="description" content="Dokumentasi API <?php echo $data['short_title']; ?> dapat Anda gunakan untuk melakukan pembelian sebagai reseller melalui web SMM dan PPOB oper dengan harga yang lebih murah."/>

<!--OG2-->
<meta content="Dokumentasi API Profil <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Dokumentasi API <?php echo $data['short_title']; ?> dapat Anda gunakan untuk melakukan pembelian sebagai reseller melalui web SMM dan PPOB oper dengan harga yang lebih murah." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Dokumentasi API Profil <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dokumentasi-api.png" property="og:image"/>
<meta content="Dokumentasi API Profil <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Dokumentasi API <?php echo $data['short_title']; ?> dapat Anda gunakan untuk melakukan pembelian sebagai reseller melalui web SMM dan PPOB oper dengan harga yang lebih murah." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dokumentasi-api.png" property="twitter:image"/>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h1 class="m-t-0 text-uppercase text-center header-title"> <b>Dokumentasi API Profil <?php echo $data['short_title']; ?></b></h1>
            <div class="box-content text-center">
                <a href="/halaman/api-dokumentasi-profile" title="<?php echo $data['short_title'];?>" alt="<?php echo $data['title'];?>"> <img src="/assets/images/halaman/dokumentasi-api.png" class="img-responsive" style="max-width: 95%; min-width: 250px;"></a>
            </div><br/>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        <tr>
                            <td>METODE HTTP</td>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <td>API URL</td>
                            <td>
                                <input type="text" class="form-control" name="api" value="<?php echo $config['web']['url'];?>api/profile" readonly> 
                            </td>
                        </tr>
                        <tr>
                            <td>API KEY</td>
                            <td>
                                <input type="text" class="form-control" name="api" value="<?php echo $data_user['api_key']; ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>FORMAT RESPON</td>
                            <td>JSON</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

		<div class="col-md-0">
			<div class="card">
				<div class="card-body">
					<h4 class="text-dark header-title m-t-0">Melakukan Cek <font color="#005CAA">Profile</font></h4>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>Parameter</th>
								<th>Deskripsi</th>
							</tr>
							<tr>
								<td>api_key</td>
								<td>
									<input type="text" class="form-control" name="api" value="<?php echo $data_user['api_key']; ?>" readonly> </td>
								</td>
							</tr>
							<tr>
								<td>action</td>
								<td>profile</td>
							</tr>
						</table>
					</div>
						<b>Contoh Respons Yang Di Dapat</b>
						<div class="alert alert-success-2">
<pre>
Jika Pemesanan Sukses
{
	"data": {
	"nama": "KITA DIGITAL",
	"username": "kitadigital",
	"email": "ronipriyatna@hotmail.com",
	"sisa_saldo": "69.401",
	"total_pemakaian": "96.401"
	}
}

Jika Pemesanan Gagal
{
	"status": false,
	"data": {
	"pesan": "Permintaan Tidak Sesuai"
	}
}
</pre>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<!-- end container -->
</div>
<!-- end content -->

<?php
require '../lib/footer.php';
?>