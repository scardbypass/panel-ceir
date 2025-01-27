<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';                    
require '../lib/header_admin.php';

// Panggil API ID, API KEY, SECRET KEY dengan CODE Provider

$cek_mail = $conn->query("SELECT * FROM setting_web");
$data_mail = mysqli_fetch_assoc($cek_mail);

$cekdpedia = $conn->query("SELECT * FROM provider WHERE code = 'roamerimei'");
$datadpedia = mysqli_fetch_assoc($cekdpedia);
$actionarie = 'profile';

$cekwa = $conn->query("SELECT * FROM provider WHERE code = 'WAGATEWAY'");
$datawa = mysqli_fetch_assoc($cekwa);

?> 

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen Data Akun Provider Server Pusat Database</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-4">
        <div class="card">    
            <div class="card-body table-responsive">                            
                <h4 class="m-t-0 header-title text-center">INFORMASI AKUN PUSAT</h4><hr>
                <!--roamerimei-->
            <?php
            $postdata = "api_key=".$datadpedia['api_key']."&action=".$actionarie."";
            $url = 'https://roamerimei.xyz/api/profile';
                
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
            $json_result = json_decode($response, true);
            ?>
                <h5 class="btn btn-primary btn-block">roamerimei.xyz</h5>
                <div class="text-dark">
                    Username: <?php echo $json_result['data']['username']; ?><br />
                    Poin: <?php echo number_format($json_result['data']['poin'],0,',','.'); ?><br />
                    <b>Saldo: Rp. <?php echo number_format($json_result['data']['sisa_saldo'],0,',','.'); ?></b>
                </div>

                <!--GATEWAY-->

                <h5 class="btn btn-primary btn-block">WAGATEWAY</h5>
                <div class="text-dark">
                    <b>Silahkan Hubungi Pihak https://scardwaget.my.id</b>
                </div>
                

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card table-responsive">
            <div class="card-body">
                <h4 class="m-t-0 header-title text-center">UPDATE LAYANAN & KATEGORI</h4><hr>
                <center>
                     <b> roamerimei</b>
                    <div class="center">
                        <a class="btn btn-info waves-effect w-md waves-light" href="../get/upd-all-category-digital" target="_blank">Upd. Kategori</a>
                        <a class="btn btn-info waves-effect w-md waves-light" href="../get/upd-all-layanan-digital" target="_blank">Upd. Layanan</a>
                        <a class="btn btn-success waves-effect w-md waves-light" href="../cronsjob/status_digital" target="_blank">Status Order</a>
                    </div>
                </center>                 
            </div>                               
        </div>                        
    </div>

    <div class="col-md-4">
        <div class="card table-responsive">
            <div class="card-body">
                <h4 class="m-t-0 header-title text-center">HAPUS LAYANAN & KATEGORI</h4><hr>	
                <center>
                    <b> roamerimei</b>
                    <div class="center">
                        <a class="btn btn-warning waves-effect w-md waves-light" href="../get/del-all-layanan-digital" target="_blank">Del. Digital</a>
                    </div> <br/>
                    <div class="center">
                        <a class="btn btn-danger waves-effect w-md waves-light" href="../get/del-all-layanan" target="_blank">Delete All</a>
                    </div>
                </center>
            </div>

        </div>                        
    </div>

    <?php 
    require '../lib/footer_admin.php';
    ?>