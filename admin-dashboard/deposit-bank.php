<?php
    session_start();
    require '../config.php';
    require '../lib/session_login_admin.php';
    require '../lib/header_admin.php';

    if (isset($_POST['update'])) {
        $get_id = $conn->real_escape_string($_GET['id_deposit']);
        $status = $conn->real_escape_string($_POST['status']);

        $deponya = $conn->query("SELECT * FROM deposit_bank WHERE kode_deposit = '$get_id'");
        $datanya = $deponya->fetch_assoc();

        $username = $datanya['username'];
        $saldo = $datanya['get_saldo'];

        if ($deponya->num_rows == 0) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Deposit Belum Ditemukan');
        } else if ($datanya['status'] == "Success") { 
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Deposit ID '.$get_id.' Telah Success');
        } else {
            if ($conn->query("UPDATE deposit_bank SET status = '$status'  WHERE kode_deposit = '$get_id'") == true){
                if ($status == "Success") {
                    $conn->query("UPDATE users set saldo = saldo + $saldo WHERE username = '$username'");
                    $conn->query("INSERT INTO history_saldo VALUES ('', '$username', 'Penambahan Saldo', '$saldo', 'Penambahan Saldo. Deposit ID $get_id', '$date', '$time')");

                    $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Data Deposit Berhasil Di Update 
                        <br /> Deposit ID: '.$get_id.'
                        <br /> Status: '.$status.'
                        <br /> Tujuan: '.$username.'
                        <br /> Nominal: '.$saldo.'
                        ');
                } else {
                    $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Data Deposit Berhasil Di Update 
                        <br /> Deposit ID: '.$get_id.'
                        <br /> Status: '.$status.'
                        ');
                }
            } else {
                $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Gagal');
            }
        }

    } else if (isset($_POST['hapus'])) {
        $get_id = $conn->real_escape_string($_GET['id_deposit']);
        $cek_deponya = $conn->query("SELECT * FROM deposit_bank WHERE kode_deposit = '$get_id'");

        if ($cek_deponya->num_rows == 0) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Deposit Belum Ditemukan');
        } else {
            if ($conn->query("DELETE FROM deposit_bank WHERE kode_deposit = '$get_id'") == TRUE) {
                $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Permintaan Berhasil', 'pesan' => 'Data Deposit Dihapus 
                    <br /> Deposit ID: '.$get_id.'');
            }
        }

    }

?>

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen dan Laporan Data Deposit Bank Dana Member</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-12">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="bg-icon float-left">
                    <i class="mdi mdi-wallet"></i>
                </div>
                <div class="text-right">
                    <p class="text-uppercase font-14 m-b-10 font-600">Total Deposit Bank</p>
                    <h3 class="m-b-10"><span>Rp <?php echo number_format($data_deposit_bank['total'],0,',','.'); ?> Dari <?php echo $jumlah_deposit_bank_member; ?> Deposit</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-list"></i>    Daftar Deposit</b></h4>                              
                <form>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label>Tampilkan Beberapa</label>
                            <select class="form-control" name="tampil">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <label>Filter Status</label>
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
                            <label>Cari Deposit ID</label>
                            <input type="text" class="form-control" name="cari" placeholder="Cari Deposit ID" value="">
                        </div>
                        <div class="form-group col-lg-3">
                            <label>Submit</label>
                            <button type="submit" class="btn btn-block btn-dark">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>Kode Deposit</th>
                                <th>Tanggal/Waktu</th>
                                <th>Username</th>
                                <th>Tipe</th>
                                <th>Provider</th>
                                <th>Pembayaran</th>
                                <th>Pengirim</th>
                                <th>Jumlah Transfer</th>
                                <th>Saldo Didapat</th>
                                <th>Status</th>
                                <th>API</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['cari'])) {
                                    $cari_oid = $conn->real_escape_string(filter($_GET['cari']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cek_depo = "SELECT * FROM deposit_bank WHERE kode_deposit LIKE '%$cari_oid%' ORDER BY id DESC"; // edit
                                } else {
                                    $cek_depo = "SELECT * FROM deposit_bank ORDER BY id DESC"; // edit
                                }

                                if (isset($_GET['cari'])) {
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    $records_per_page = $cari_urut; // edit
                                } else {
                                    $records_per_page = 10; // edit
                                }

                                $starting_position = 0;
                                if (isset($_GET["halaman"])) {
                                    $starting_position = ($conn->real_escape_string(filter($_GET["halaman"]))-1) * $records_per_page;
                                }
                                $new_query = $cek_depo." LIMIT $starting_position, $records_per_page";
                                $new_query = $conn->query($new_query);
                                // end paging config
                                while ($data_depo = $new_query->fetch_assoc()) {
                            ?>
                            <tr>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id_deposit=<?php echo $data_depo['kode_deposit']; ?>" class="form-inline" role="form" method="POST"> 
                                    <td><?php echo $data_depo['kode_deposit']; ?></td>
                                    <td><?php echo tanggal_indo($data_depo['date']); ?>, <?php echo $data_depo['time']; ?></td>
                                    <td><?php echo $data_depo['username']; ?></td>
                                    <td><?php echo $data_depo['tipe']; ?></td>
                                    <td><?php echo $data_depo['provider']; ?></td>
                                    <td><?php echo $data_depo['payment']; ?></td>
                                    <td><?php echo $data_depo['nomor_pengirim']; ?></td>
                                    <td>Rp <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></td>
                                    <td >Rp <?php echo number_format($data_depo['get_saldo'],0,',','.'); ?></td>
                                    <td>
                                        <select class="form-control" style="width: 100px;" name="status">
                                            <?php
                                                if ($data_depo['status'] == "Success") {
                                                ?>
                                                    <option value="<?php echo $data_depo['status']; ?>"><?php echo $data_depo['status']; ?></option>
                                                    <?php
                                                } else {
                                                ?>
                                                    <option value="<?php echo $data_depo['status']; ?>"><?php echo $data_depo['status']; ?></option>
                                                    <option value="Success">Success</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Error">Error</option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php
                                            if($data_depo['place_from'] == "API") {
                                            ?>
                                                <span class="badge badge-success"><i aria-hidden="true" class="fa fa-check"></i></span>
                                                <?php
                                            } else {
                                        ?>
                                        <span class="badge badge-danger"><i aria-hidden="true" class="fa fa-times"></i></span>
                                        <?php }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <a href="javascript:;" onclick="deposit('<?php echo $config['web']['url'];?>admin-dashboard/ajax/deposit/view-deposit-bank?kode_deposit=<?php echo $data_depo['kode_deposit']; ?>')" class="btn btn-sm btn-primary">
                                            <i aria-hidden="true" class="fa fa-eye" title="view"></i>
                                        </a>
                                        <button data-toggle="tooltip" title="Update" type="submit" name="update" class="btn btn-sm btn-bordred btn-info">
                                            <i aria-hidden="true" class="fa fa-edit"></i>
                                        </button>
                                        <button data-toggle="tooltip" title="Hapus" type="submit" name="hapus" class="btn btn-sm btn-bordred btn-danger">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </button>
                                    </td>
                                 </form>
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

<script type="text/javascript">
    function copy_to_clipboard(element) {
        var copyText = document.getElementById(element);
        copyText.select();
        document.execCommand("copy");
    }
</script>

<script type="text/javascript">
    function deposit(url) {
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
                        <h4 class="modal-title mt-0" id="myModalLabel"><i class="mdi mdi-wallet"></i> Detail Deposit Saldo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-detail-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../lib/footer_admin.php';
?>