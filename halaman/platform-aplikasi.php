<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Download Platform Aplikasi <?php echo $data['short_title']; ?></title>
<meta name="description" content="Akses Semua Layanan <?php echo $data['short_title']; ?> dalam 1 Aplikasi dan Web Store. Download sekarang di Situs Resmi <?php echo $data['title']; ?>."/>

<!--OG2-->
<meta content="Download Platform Aplikasi <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Akses Semua Layanan <?php echo $data['short_title']; ?> dalam 1 Aplikasi dan Web Store. Download sekarang di Situs Resmi <?php echo $data['title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Download Platform Aplikasi <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/platform-aplikasi.png" property="og:image"/>
<meta content="Download Platform Aplikasi <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Akses Semua Layanan <?php echo $data['short_title']; ?> dalam 1 Aplikasi dan Web Store. Download sekarang di Situs Resmi <?php echo $data['title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/platform-aplikasi.png" property="twitter:image"/>

<div class="row">
	<div class="col-sm-12">
		<div class="card">

			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Download Platform Aplikasi <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
                		<a href="/halaman/platform-aplikasi" title="Platform Aplikasi <?php echo $data['short_title']; ?>" alt="Platform Aplikasi <?php echo $data['short_title']; ?>"> <img src="/assets/images/halaman/platform-aplikasi.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
            		</div><br/>
				</center>
				<?php 
				//Call Platform Aplikasi
				$CallPage = Show('halaman', "id = '2'");
				echo "".$CallPage['konten']."";
				?>
			</div>

			<div class="card-body table-responsive">
				<center>
					<h2 class="m-t-0 text-uppercase header-title">Akses Semua Layanan <?php echo $data['short_title']; ?> dalam 1 Aplikasi dan Web.</h2> </br><h3 class="text-uppercase header-title">Download sekarang!</h3>
				</center>
				<table class="table table-bordered dt-responsive nowrap" style="width: 100%;">
					<tbody>
						<tr>
							<td align="right" style="width: 50%;">
								<a href="<?php echo $config['web']['url'];?>" class="btn btn-primary btn-bordred waves-effect waves-light" target="_blank"> Website Official</a>
							</td>
							<td align="left" style="width: 50%;">
								<a href="<?php echo $config['web']['url'];?>PPOB.apk" class="btn btn-primary btn-bordred waves-effect waves-light" target="_blank"> Aplikasi Android</a>
							</td>
						</tr>
					</tbody>
				</table>
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

