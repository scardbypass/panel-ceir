<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';
require '../lib/header_admin.php';

?>

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Halaman Manajemen Konten Level Developer</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i aria-hidden="true" class="fa fa-shopping-cart"></i>
                </div>-->
                <div class="text-left">
                    <p class="text-uppercase font-14 m-b-10 font-600">Produk Digital</p>
                    <h3 class="m-b-10"><span><?php echo $layanan_digital; ?></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="ri-team-fill"></i>
                </div>-->
                <div class="text-left">
                    <p class="text-uppercase font-14 m-b-10 font-600">Jumlah Pengguna</p>
                    <h3 class="m-b-10"><span><?php echo $total_pengguna; ?></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="ri-wallet-3-fill"></i>
                </div>-->
                <div class="text-left">
                    <p class="text-uppercase font-14 m-b-10 font-600">Jumlah Deposit</p>
                    <h3 class="m-b-10"><span><?php echo $jumlah_deposit_member; ?></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="ri-wallet-3-fill"></i>
                </div>-->
                <div class="text-left">
                    <p class="text-uppercase font-14 m-b-10 font-600">Saldo Member</p>
                    <h3 class="m-b-10"><span>Rp. <?php echo number_format($data_saldo_member['total'],0,',','.'); ?></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="ri-wallet-3-fill"></i>
                </div>-->
                <div class="text-left">
                    <p class="text-uppercase font-14 m-b-10 font-600">Transaksi Member</p>
                    <h3 class="m-b-10"><span>Rp. <?php echo number_format($data_transaksi_member['total'],0,',','.'); ?></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Bank</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            Rp. <?php echo number_format($data_deposit_bank['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_bank_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Emoney</p>
                    <h3 class="m-b-10">
                        <span>
                            Rp. <?php echo number_format($data_deposit_emoney['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_emoney_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Epayment</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            $<?php echo number_format($data_deposit_epayment['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_epayment_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Tsel</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            Rp. <?php echo number_format($data_deposit_tsel['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_tsel_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Voucher</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            Rp. <?php echo number_format($data_deposit_voucher['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_voucher_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-credit-card"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Deposit Oper</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            Rp. <?php echo number_format($data_deposit['total'],0,',','.'); ?> dari <?php echo $jumlah_deposit_lain_member; ?> trx
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-cart-plus"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Order roamerimei</p>
                    <h3 class="m-b-10">
                        <span>                                            	
                            Rp. <?php echo number_format($data_pesanan_digital['total'],0,',','.'); ?> dari <?php echo $jumlah_pesanan_digital; ?> trx	
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card widget-box-three">
            <div class="card-body">
                <!--<div class="bg-icon float-left">
                    <i class="mdi mdi-cart-plus"></i>
                </div>-->
                <div class="text-left">
                    <p class="m-b-10 text-uppercase font-14 font-600">Total Order Indodax</p>
                    <h3 class="m-b-10">
                        <span>                                              
                            Rp. 0 dari 0 trx  
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>   

<!--
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 "><i aria-hidden="true" class="fa fa-area-chart fa-fw"></i> Grafik Order 7 Hari Terakhir</h4>
                <div id="line-chart7" style="height: 300px;"></div>
                <script type="text/javascript">
                    $(function(){
                        new Morris.Area({
                            element: 'line-chart7',
                            data: [
                            <?php
                            $list_tanggal = array();
                            for ($i = 6; $i > -1; $i--) {
                                $list_tanggal[] = date('Y-m-d', strtotime('-'.$i.' days'));
                            }

                            for ($i = 0; $i < count($list_tanggal); $i++) {
                                $get_order_sosmed = $conn->query("SELECT * FROM pembelian_sosmed WHERE date = '".$list_tanggal[$i]."' ");
                                $get_order_pulsa = $conn->query("SELECT * FROM pembelian_pulsa WHERE date = '".$list_tanggal[$i]."' ");
                                print("{ y: '".tanggal_indo($list_tanggal[$i])."', a: ".mysqli_num_rows($get_order_sosmed).", b: ".mysqli_num_rows($get_order_pulsa)." }, ");

                            }
                            ?>
                            ],
                            xkey: 'y',
                            ykeys: ['a','b'],
                            labels: ['Pesanan Sosial Media','Pesanan Pulsa'],
                            lineColors: ['#02c0ce','#53c68c'],
                            gridLineColor: '#eef0f2',
                            pointSize: 0,
                            lineWidth: 0,
                            resize: true,
                            parseTime: false
                        });
                    });
                </script>                          
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 "><i aria-hidden="true" class="fa fa-area-chart fa-fw"></i> Grafik Order 30 Hari Terakhir</h4>
                <div id="line-chart30" style="height: 300px;"></div>
                <script type="text/javascript">
                    $(function(){
                        new Morris.Area({
                            element: 'line-chart30',
                            data: [
                            <?php
                            $list_tanggal = array();
                            for ($i = 29; $i > -1; $i--) {
                                $list_tanggal[] = date('Y-m-d', strtotime('-'.$i.' days'));
                            }

                            for ($i = 0; $i < count($list_tanggal); $i++) {
                                $get_order_sosmed = $conn->query("SELECT * FROM pembelian_sosmed WHERE date = '".$list_tanggal[$i]."' ");
                                $get_order_pulsa = $conn->query("SELECT * FROM pembelian_pulsa WHERE date = '".$list_tanggal[$i]."' ");
                                print("{ y: '".tanggal_indo($list_tanggal[$i])."', a: ".mysqli_num_rows($get_order_sosmed).", b: ".mysqli_num_rows($get_order_pulsa)." }, ");

                            }
                            ?>
                            ],
                            xkey: 'y',
                            ykeys: ['a','b'],
                            labels: ['Pesanan Sosial Media','Pesanan Pulsa'],
                            lineColors: ['#02c0ce','#53c68c'],
                            gridLineColor: '#eef0f2',
                            pointSize: 0,
                            lineWidth: 0,
                            resize: true,
                            parseTime: false
                        });
                    });
                </script>                          
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 "><i aria-hidden="true" class="fa fa-area-chart fa-fw"></i> Grafik Order 360 Hari Terakhir</h4>
                <div id="line-chart360" style="height: 300px;"></div>
                <script type="text/javascript">
                    $(function(){
                        new Morris.Area({
                            element: 'line-chart360',
                            data: [
                            <?php
                            $list_tanggal = array();
                            for ($i = 359; $i > -1; $i--) {
                                $list_tanggal[] = date('Y-m-d', strtotime('-'.$i.' days'));
                            }

                            for ($i = 0; $i < count($list_tanggal); $i++) {
                                $get_order_sosmed = $conn->query("SELECT * FROM pembelian_sosmed WHERE date = '".$list_tanggal[$i]."' ");
                                $get_order_pulsa = $conn->query("SELECT * FROM pembelian_pulsa WHERE date = '".$list_tanggal[$i]."' ");
                                print("{ y: '".tanggal_indo($list_tanggal[$i])."', a: ".mysqli_num_rows($get_order_sosmed).", b: ".mysqli_num_rows($get_order_pulsa)." }, ");

                            }
                            ?>
                            ],
                            xkey: 'y',
                            ykeys: ['a','b'],
                            labels: ['Pesanan Sosial Media','Pesanan Pulsa'],
                            lineColors: ['#02c0ce','#53c68c'],
                            gridLineColor: '#eef0f2',
                            pointSize: 0,
                            lineWidth: 0,
                            resize: true,
                            parseTime: false
                        });
                    });
                </script>                          
            </div>
        </div>
    </div>
</div>
-->

<?php
require '../lib/footer_admin.php';
?>