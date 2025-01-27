<?php
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>

<!--Title-->
<title>Mutasi Saldo</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <center><h4 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-history text-primary"></i><b> Mutasi Saldo</h4></b><hr></center>
                <form method="GET">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label>Tampilkan Beberapa</label>
                            <select class="form-control" name="tampil">
                                <option value="10">Default</option>
                                <option value="20">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-4">
                            <label>Filter Tipe</label>
                            <select class="form-control" name="tipe">
                                <option value="">Semua</option>
                                <option value="Penambahan Saldo">Penambahan</option>
                                <option value="Pengurangan Saldo">Pengurangan</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-4">
                            <label>Submit</label>
                            <button type="submit" class="btn btn-block btn-dark">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Icon</th>
                                <th>Tipe</th>
                                <th>Waktu</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['tipe'])) {
                                    $cari_tipe = $conn->real_escape_string(filter($_GET['tipe']));
                                    $cek_data = "SELECT * FROM history_saldo WHERE aksi LIKE '%$cari_tipe%' AND username = '$sess_username' ORDER BY id DESC"; // edit
                                    } else {
                                    $cek_data = "SELECT * FROM history_saldo WHERE username = '$sess_username' ORDER BY id DESC"; // edit
                                    }
                                    if (isset($_GET['tipe'])) {
                                        $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    $records_per_page = $cari_urut; // edit
                                    } else {
                                    $records_per_page = 10; // edit
                                    }

                                    $starting_position = 0;
                                    if(isset($_GET["halaman"])) {
                                        $starting_position = ($conn->real_escape_string(filter($_GET["halaman"]))-1) * $records_per_page;
                                    }
                                    $new_query = $cek_data." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    $no = $starting_position+1;
                                    // end paging config
                                    while ($view_data = $new_query->fetch_assoc()) {
                                        if ($view_data['aksi'] == "Penambahan Saldo") {
                                            $label = "success";
                                            $icon = "+";
                                        } else if ($view_data['aksi'] == "Pengurangan Saldo") {
                                            $label = "danger";
                                            $icon = "-";
                                        }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge badge-pill badge-<?php echo $label; ?>"><?php echo $icon; ?></span></td>
                                <td width="20%"><span class="badge badge-<?php echo $label; ?>"><?php echo $view_data['aksi']; ?></span></td>
                                <td><?php echo tanggal_indo($view_data['date']); ?>, <?php echo $view_data['time']; ?></td>
                                <td width="20%"><span class="badge badge-pill badge-<?php echo $label; ?>">Rp <?php echo $icon; ?>  <?php echo number_format($view_data['nominal'],0,',','.'); ?></span></td>
                                <td><?php echo $view_data['pesan']; ?></td>
                            </tr>   
                            <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <ul class="pagination">
                        <?php
                            // start paging link
                            if (isset($_GET['tipe'])) {
                                $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                            } else {
                                $cari_urut =  10;
                            }  
                            if (isset($_GET['tipe'])) {
                                $cari_tipe = $conn->real_escape_string(filter($_GET['tipe']));
                                $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                $self = $_SERVER['PHP_SELF'];
                            } else {
                                $self = $_SERVER['PHP_SELF'];
                            }
                            $cek_data = $conn->query($cek_data);
                            $total_records = mysqli_num_rows($cek_data);
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
                                    if (isset($_GET['tipe'])) {
                                        $cari_tipe = $conn->real_escape_string(filter($_GET['tipe']));
                                        $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$cari_urut."&tipe=".$cari_tipe."'><<</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$cari_urut."&tipe=".$cari_tipe."'><</a></li>";
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
                                    if (isset($_GET['tipe'])) {
                                        $cari_tipe = $conn->real_escape_string(filter($_GET['tipe']));
                                        $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                        if($i==$current_page) {
                                            echo "<li class='active page-item'><a class='page-link' href='#'>".$i."</a></li>";
                                        } else {
                                            echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."&tampil=".$cari_urut."&tipe=".$cari_tipe."'>".$i."</a></li>";
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
                                    if (isset($_GET['tipe'])) {
                                        $cari_tipe = $conn->real_escape_string(filter($_GET['tipe']));
                                        $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$cari_urut."&tipe=".$cari_tipe."'>></a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$cari_urut."&tipe=".$cari_tipe."'>>></a></li>";
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
</div>

<?php
require '../lib/footer.php';
?>