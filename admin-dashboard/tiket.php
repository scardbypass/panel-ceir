<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';
require '../lib/header_admin.php';

if (isset($_POST['tutup'])) {
    $PostID = $conn->real_escape_string($_POST['id']);
    $CheckTiket = $conn->query("SELECT * FROM tiket WHERE id = '$PostID'");
    if ($CheckTiket->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tiket Tidak Ditemukan');
    } else {
        $tutup = $conn->query("UPDATE tiket SET status = 'Closed' WHERE id = '$PostID'");
        if ($tutup == TRUE) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Tiket Berhasil Ditutup');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal!');
        }
    }

} else if (isset($_POST['delete'])) {
    $PostID = $conn->real_escape_string($_POST['id']);
    $CheckTiket = $conn->query("SELECT * FROM tiket WHERE id = '$PostID'");
    if ($CheckTiket->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tiket Tidak Ditemukan');
    } else {
        $tutup = $conn->query("DELETE FROM tiket WHERE id = '$PostID'");
        if ($tutup == TRUE) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Tiket Berhasil Dihapus');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal!');
        }
    }
}

?>

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen Data Tiket Dukungan Pelanggan</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title m-t-0 m-b-30"><i class="dripicons-ticket"></i> 	Tiket</h4>
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
                                <option value="Closed" >Closed</option>
                                <option value="Waiting" >Waiting</option>
                                <option value="Responded" >Responded</option>
                            </select>
                        </div>                                                
                        <div class="form-group col-lg-3">
                            <label>Cari Kata Kunci</label>
                            <input type="text" class="form-control" name="search" placeholder="Cari Kata Kunci" value="">
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
                                <th>Tanggal/Waktu</th>
                                <th>Username</th>
                                <th>Update Terakhir</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            // start paging config
                            if (isset($_GET['search'])) {
                                $cari = $conn->real_escape_string(filter($_GET['search']));
                                $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                $cek_tiket = "SELECT * FROM tiket WHERE subjek LIKE '%$cari%' AND status LIKE '%$cari_status%' ORDER BY id DESC"; // edit
                                } else {
                                $cek_tiket = "SELECT * FROM tiket  ORDER BY id DESC"; // edit
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
                                $new_query = $cek_tiket." LIMIT $starting_position, $records_per_page";
                                $new_query = $conn->query($new_query);
                                // end paging config
                                while ($data_tiket = $new_query->fetch_assoc()) {
                                    if ($data_tiket['status'] == "Pending") {
                                        $label = "warning";
                                    } else if ($data_tiket['status'] == "Closed") {
                                        $label = "danger";
                                    } else if ($data_tiket['status'] == "Waiting") {
                                        $label = "info";    
                                    } else if ($data_tiket['status'] == "Responded") {
                                        $label = "success";       
                                    }
                        ?>
                            <tr>
                                <td><?php echo tanggal_indo($data_tiket['date']); ?>, <?php echo $data_tiket['time']; ?></td>
                                <td><?php echo $data_tiket['user']; ?></td>
                                <td><?php echo time_elapsed_string($data_tiket['update_terakhir']); ?></td>
                                <td><?php echo $data_tiket['subjek']; ?></td>
                                <td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_tiket['status']; ?></span></td>
                                <td align="center">
                                    <a href="<?php echo $config['web']['url'];?>admin-dashboard/ajax/tiket/reply?id=<?php echo $data_tiket['id']; ?>" class="btn btn-sm btn-primary"><i aria-hidden="true" class="fa fa-reply" title="Reply"></i></a>
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/tiket/tutup?id=<?php echo $data_tiket['id']; ?>')" class="btn btn-sm btn-warning"><i aria-hidden="true" class="fa fa-close" title="Tutup"></i></a>
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/tiket/hapus?id=<?php echo $data_tiket['id']; ?>')" class="btn btn-sm btn-danger"><i aria-hidden="true" class="fa fa-trash" title="Tutup"></i></a>
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
                        if (isset($_GET['search'])) {
                            $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                        } else {
                            $cari_urut =  10;
                        }  
                        if (isset($_GET['search'])) {
                            $cari = $conn->real_escape_string(filter($_GET['search']));
                            $cari_status = $conn->real_escape_string(filter($_GET['status']));
                            $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                        } else {
                            $self = $_SERVER['PHP_SELF'];
                        }
                        $cek_tiket = $conn->query($cek_tiket);
                        $total_records = mysqli_num_rows($cek_tiket);
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
                                    $cari = $conn->real_escape_string(filter($_GET['search']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$cari_urut."&status=".$cari_status."&search=".$cari."'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$cari_urut."&status=".$cari_status."&search=".$cari."'><</a></li>";
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
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."&tampil=".$cari_urut."&status=".$cari_status."&search=".$cari."'>".$i."</a></li>";
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
                                    $cari = $conn->real_escape_string(filter($_GET['search']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$cari_urut."&status=".$cari_status."&search=".$cari."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$cari_urut."&status=".$cari_status."&search=".$cari."'>>></a></li>";
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
                        <h4 class="modal-title mt-0" id="myModalLabel"><i class="dripicons-ticket"></i> Tiket</h4>
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
include '../lib/footer_admin.php';
?>