<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>

<!--Title-->
<title>Riwayat Transfer Saldo</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-history text-primary"></i> Riwayat Transfer Saldo</h4><hr>
                <form class="form-horizontal" method="GET">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <badge>Tampilkan Beberapa</badge>
                            <select class="form-control" name="tampil">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>                                             
                        <div class="form-group col-lg-4">
                            <badge>Cari Kata Kunci</badge>
                            <input type="text" class="form-control" name="search" placeholder="Cari Kata Kunci">
                        </div>
                        <div class="form-group col-lg-4">
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
                                <th>Penerima</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $tampil = $conn->real_escape_string(filter($_GET['tampil']));
                                    $cek_data = "SELECT * FROM riwayat_transfer WHERE penerima LIKE '%$search%' AND pengirim = '$sess_username' ORDER BY id DESC"; // edit
                                    } else {
                                    $cek_data = "SELECT * FROM riwayat_transfer WHERE pengirim = '$sess_username' ORDER BY id DESC"; // edit
                                    }
                                    if (isset($_GET['search'])) {
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
                                    while ($datanya = $new_query->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $datanya['penerima']; ?></td>
                                <td>Rp <span class="text-success"><?php echo number_format($datanya['jumlah'],0,',','.'); ?></span></td>
                                <td><span class="badge badge-success">Berhasil di Transfer</span></td>
                                <td><?php echo tanggal_indo($datanya['date']); ?>, <?php echo $datanya['time']; ?></td>
                            </tr>   
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <ul class="pagination pagination-split">
                    <?php
                        // start paging link
                        if (isset($_GET['search'])) {
                            $cari_urut = $conn->real_escape_string(filter($_GET['search']));
                        } else {
                            $cari_urut =  10;
                        }  
                        if (isset($_GET['search'])) {
                            $search = $conn->real_escape_string(filter($_GET['search']));
                            $tampil = $conn->real_escape_string(filter($_GET['tampil']));
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
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));

                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$tampil."&search=".$search."'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$tampil."&search=".$search."'><</a></li>";
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
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $tampil = $conn->real_escape_string(filter($_GET['tampil']));
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
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $tampil = $conn->real_escape_string(filter($_GET['tampil']));

                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$tampil."&search=".$search."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$tampil."&search=".$search."'>>></a></li>";
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

<?php
require '../lib/footer.php';
?>