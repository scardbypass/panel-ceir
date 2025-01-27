<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>

<!--Title-->
<title>Riwayat Pemesanan Digital Dll</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-history text-primary"></i> Riwayat Pemesanan Digital Dll</h4><hr>
                <div class="alert alert-success">
                    Klik Ikon <b><i class="mdi mdi-view-list"></i></b> Untuk Melihat Detail Pesanan
                </div>                                
                <form class="form-horizontal" method="GET">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <badge>Tampilkan Beberapa</badge>
                            <select class="form-control" name="tampil">
                                <option value="10">10</option>
                                <option value="20">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <badge>Filter Status</badge>
                            <select class="form-control" name="status">
                                <option value="">Semua</option>
                                <option value="Pending" >Pending</option>
                                <option value="Processing" >Processing</option>
                                <option value="Success" >Success</option>
                                <option value="Error" >Error</option>
                                <option value="Partial" >Partial</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <badge>Cari Order ID</badge>
                            <input type="text" class="form-control" name="cari" placeholder="Cari Order ID" value="">
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
                                <th>ID</th>
                                <th>Waktu</th>
                                <th>Layanan</th>
                                <th>Target</th>                                       
                                <th>Harga</th>
                                <th>Status <a href="/halaman/status-order" target="_blank"><b><i aria-hidden="true" class="fa fa-question-circle"></i></b></a></th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['cari'])) {
                                    $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cek_pesanan = "SELECT * FROM pembelian_digital WHERE oid LIKE '%$cari_oid%' AND status LIKE '%$cari_status%' AND user = '$sess_username' ORDER BY id DESC"; // edit
                                    } else {
                                    $cek_pesanan = "SELECT * FROM pembelian_digital WHERE user = '$sess_username' ORDER BY id DESC"; // edit
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
                                    $new_query = $cek_pesanan." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    // end paging config
                                    while ($data_pesanan = $new_query->fetch_assoc()) {
                                        if ($data_pesanan['status'] == "Pending") {
                                            $badge = "warning";
                                        } else if ($data_pesanan['status'] == "Partial") {
                                            $badge = "danger";
                                        } else if ($data_pesanan['status'] == "Error") {
                                            $badge = "danger";    
                                        } else if ($data_pesanan['status'] == "Processing") {
                                            $badge = "info";    
                                        } else if ($data_pesanan['status'] == "Success") {
                                            $badge = "success";    
                                        }
                            ?>
                            <tr>
                                <td class="text-primary"><?php echo $data_pesanan['oid']; ?></td>
                                <td><?php echo tanggal_indo($data_pesanan['date']); ?>, <?php echo $data_pesanan['time']; ?></td>
                                <td><?php echo $data_pesanan['layanan']; ?></td>
                                <td style="min-width: 200px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $data_pesanan['target']; ?>" id="target-<?php echo $data_pesanan['oid']; ?>" readonly="">
                                        <button data-toggle="tooltip" title="Copy Target" class="btn btn-xs btn-primary" type="button" onclick="copy_to_clipboard('target-<?php echo $data_pesanan['oid']; ?>')"><i class="mdi mdi-content-copy"></i></button>
                                    </div>
                                </td>                                      
                                <td>Rp <?php echo number_format($data_pesanan['harga'],0,',','.'); ?></td>
                                <td><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_pesanan['status']; ?></badge>
                                </td>
                                <td><button class="view_order btn btn-primary" data-toggle="modal" id="<?php echo $data_pesanan['id']; ?>" data-target='#myDetail'><i class="mdi mdi-view-list"></i></button>
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
                        $cek_pesanan = $conn->query($cek_pesanan);
                        $total_records = mysqli_num_rows($cek_pesanan);
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
                                    $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_oid."'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_oid."'><</a></li>";
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
                                    $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    if($i==$current_page) {
                                        echo "<li class='active page-item'><a class='page-link' href='#'>".$i."</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_oid."'>".$i."</a></li>";
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
                                    $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_oid."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$cari_urut."&status=".$cari_status."&cari=".$cari_oid."'>>></a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."'>></i></a></li>";
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
                <h4 class="modal-title mt-0" id="myModalbadge">Detail Pesanan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-badge="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data_order">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function copy_to_clipboard(element) {
        var copyText = document.getElementById(element);
        copyText.select();
        document.execCommand("copy");
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.view_order').click(function(){
            var id = $(this).attr("id");

            // memulai ajax
            $.ajax({
                url: '<?php echo $config['web']['url'];?>riwayat/ajax/detail-digital.php',
                method: 'post',		
                data: {id:id},	
                success:function(data){	
                    $('#data_order').html(data);
                    $('#myDetail').modal("show");
                }
            });
        });
    });
</script>

<?php
require '../lib/footer.php';
?>