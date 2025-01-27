<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>

<!--Title-->
<title>Riwayat Deposit via Emoney</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-history text-primary"></i><b> Riwayat Deposit via Emoney</b></h4><hr>
                <div class="alert alert-success">
                    <p>Klik Ikon <b><i class="mdi mdi-view-list mdi-18px"></i></b> Untuk Melihat Detail Deposit</p>

                    <p>Klik Ikon <b><i class="mdi mdi-checkbox-marked-outline mdi-18px"></i></b> Untuk Konfirmasi / Membatalkan</p>
                </div>                                
                <form class="form-horizontal" method="GET">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <badge>Tampilkan Beberapa</badge>
                            <select class="form-control" name="tampil">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <badge>Filter Status</badge>
                            <select class="form-control" name="status">
                                <option value="">Semua</option>
                                <option value="Pending" >Pending</option>
                                <option value="Success" >Success</option>
                                <option value="Error" >Error</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <badge>Cari ID Deposit</badge>
                            <input type="number" class="form-control" name="cari" placeholder="Cari Deposit ID" value="">
                        </div>
                        <div class="form-group col-lg-3">
                            <badge>Submit</badge>
                            <button type="submit" class="btn btn-block btn-dark">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Pembayaran</th>
                                <th>Pengirirm</th>
                                <th>Jumlah Transfer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                $no = 1;
                                if (isset($_GET['cari'])) {
                                    $cari_id = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cek_depo = "SELECT * FROM deposit_emoney WHERE kode_deposit LIKE '%$cari_id%' AND status LIKE '%$cari_status%' AND username = '$sess_username' ORDER BY id DESC"; // edit
                                    } else {
                                    $cek_depo = "SELECT * FROM deposit_emoney WHERE username = '$sess_username' ORDER BY id DESC"; // edit
                                    }
                                    if (isset($_GET['cari'])) {
                                        $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    $records_per_page = $cari_urut; // edit
                                    } else {
                                    $records_per_page = 10; // edit
                                    }

                                    $starting_position = 0;
                                    if(isset($_GET["halaman"])) {
                                        $starting_position = ($conn->real_escape_string(filter($_GET["halaman"]))-1) * $records_per_page;
                                    }
                                    $new_query = $cek_depo." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    // end paging config
                                    while ($data_depo = $new_query->fetch_assoc()) {
                                        if ($data_depo['status'] == "Pending") {
                                            $badge = "warning";
                                            $klik = "success";  
                                            $action = "checkbox-marked-outline";      
                                            $btn = "";
                                        } else if ($data_depo['status'] == "Error") {
                                            $badge = "danger";  
                                            $klik = "primary";
                                            $action = "blur-off";
                                            $btn = "disabled";   
                                        } else if ($data_depo['status'] == "Success") {
                                            $badge = "success";
                                            $klik = "primary"; 
                                            $action = "blur-off";
                                            $btn = "disabled";  
                                        }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo tanggal_indo($data_depo['date']); ?>, <?php echo $data_depo['time']; ?></td>
                                <td><?php echo $data_depo['payment']; ?></td>
                                <td><?php echo $data_depo['nomor_pengirim']; ?></td>
                                <td>Rp <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></td></td>
                                <td><span class="badge badge-<?php echo $badge; ?>"><?php echo $data_depo['status']; ?></span>
                                </td>
                                <td><button class="view_deposit btn btn-primary btn-small" data-toggle="modal" id="<?php echo $data_depo['id']; ?>" data-target='#myDetail'><i class="mdi mdi-view-list mdi-18px"></i></button><br /><br />
                                    <a href="<?php echo $config['web']['url'];?>invoice-emoney" class="btn btn-<?php echo $klik; ?> <?php echo $btn; ?> btn-small"><i class="mdi mdi-<?php echo $action; ?> mdi-18px"></i></a>
                                </td>
                            </tr>   
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <ul class="pagination pagination-split">
                    <?php
                        // start paging link
                        if (isset($_GET['cari'])) {
                            $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                        } else {
                            $cari_urut =  10;
                        }  
                        if (isset($_GET['cari'])) {
                            $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                            $cari_status = $conn->real_escape_string(filter($_GET['status']));
                            $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                        } else {
                            $self = $_SERVER['PHP_SELF'];
                        }
                        $cek_depo = $conn->query($cek_depo);
                        $total_records = mysqli_num_rows($cek_depo);
                        echo "<li class='disabled page-item'><a class='page-link' href='#'>Total: ".$total_records."</a></li>";
                        if($total_records > 0) {
                            $total_pages = ceil($total_records/$records_per_page);
                            $current_page = 1;
                            if(isset($_GET["halaman"])) {
                                $current_page = $conn->real_escape_string(filter($_GET["halaman"]));
                                if ($current_page < 1) {
                                    $current_page = 1;
                                }
                            }
                            if($current_page > 1) {
                                $previous = $current_page-1;
                                if (isset($_GET['cari'])) {
                                    $cari_id = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_id."'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_id."'><</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."'><</a></li>";
                                }
                            }
                            // limit page
                            $limit_page = $current_page+3;
                            $limit_show_link = $total_pages-$limit_page;
                            if ($limit_show_link < 0) {
                                $limit_show_link2 = $limit_show_link*2;
                                $limit_link = $limit_show_link - $limit_show_link2;
                                $limit_link = 3 - $limit_link;
                            } else {
                                $limit_link = 3;
                            }
                            $limit_page = $current_page+$limit_link;
                            // end limit page
                            // start page
                            if ($current_page == 1) {
                                $start_page = 1;
                            } else if ($current_page > 1) {
                                if ($current_page < 4) {
                                    $min_page  = $current_page-1;
                                } else {
                                    $min_page  = 3;
                                }
                                $start_page = $current_page-$min_page;
                            } else {
                                $start_page = $current_page;
                            }
                            // end start page
                            for($i=$start_page; $i<=$limit_page; $i++) {
                                if (isset($_GET['cari'])) {
                                    $cari_id = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    if($i==$current_page) {
                                        echo "<li class='active page-item'><a class='page-link' href='#'>".$i."</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_id."'>".$i."</a></li>";
                                    }
                                } else {
                                    if($i==$current_page) {
                                        echo "<li class='active page-item'><a class='page-link' href='#'>".$i."</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."'>".$i."</a></li>";
                                    }        
                                }
                            }
                            if($current_page!=$total_pages) {
                                $next = $current_page+1;
                                if (isset($_GET['cari'])) {
                                    $cari_id = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_id."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_id."'>>></a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."'>>></a></li>";
                                }
                            }
                        }
                        // end paging link
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myDetail" tabindex="-1" role="dialog" aria-badgeledby="myModalbadge">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0" id="myModalbadge">Detail Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-badge="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data_deposit">
            </div>				
            <div class="modal-footer">				
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.view_deposit').click(function(){
            var id = $(this).attr("id");
            // memulai ajax
            $.ajax({
                url: '<?php echo $config['web']['url'];?>riwayat/ajax/detail-deposit-emoney.php',
                method: 'post',		
                data: {id:id},	
                success:function(data){	
                    $('#data_deposit').html(data);
                    $('#myDetail').modal("show");
                }
            });
        });
    });
</script>

<?php
require '../lib/footer.php';
?>