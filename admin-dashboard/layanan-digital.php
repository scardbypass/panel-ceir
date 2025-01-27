<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';
// $conn->query("INSERT INTO layanan_digital VALUES (null, '$sid', '$pid', '$layanan', '$operator', '$harga', '$harga_api', '$profit', '$status', '$provider',null,null)   

$messsage = "";
if (isset($_POST['tambah'])) {
    $sid = $conn->real_escape_string(filter($_POST['sid']));
    $pid = $conn->real_escape_string(trim($_POST['pid']));
    $operator = $conn->real_escape_string(trim($_POST['operator']));
    $layanan = $conn->real_escape_string($_POST['layanan']);
    $harga = $conn->real_escape_string($_POST['harga']);
    $harga_api = $conn->real_escape_string($_POST['harga_api']);
    $profit = $conn->real_escape_string(filter($_POST['profit']));
    $status = $conn->real_escape_string(trim($_POST['status']));
    $provider = $conn->real_escape_string(trim($_POST['provider']));     


   
  

    if (!$sid || !$pid || !$operator || !$layanan || !$harga || !$harga_api || !$profit || !$status || !$provider) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Harap Mengisi Semua Input'); 
    } else {
      $results =  $conn->query("INSERT INTO `layanan_digital` (`id`, `service_id`, `provider_id`, `operator`, `layanan`, `harga`, `harga_api`, `profit`, `status`, `provider`, `tipe`, `catatan`) VALUES (NULL,'$sid', '$pid', '$operator', '$layanan', '$harga', '$harga_api', '$profit', '$status','$provider', 'Digital', NULL)");
        if ($results == true) {
            $messsage = "Berhasil menambah data baru!";
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Layanan Baru Telah Berhasil Ditambahkan <br />
                Service ID: '.$sid.' <br />
                Operator: '.$operator.' <br />
                Layanan: '.$layanan.' <br />
                Harga Web: '.$harga.' <br />
                Harga Api: '.$harga_api.' <br />
                Profit: '.$profit.' <br />
                Status: '.$status.' <br />
                Provider: '.$provider.' <br />
                ');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['edit'])) {
    $sid = $conn->real_escape_string(filter($_POST['sid']));
    $pid = $conn->real_escape_string(trim($_POST['pid']));
    $operator = $conn->real_escape_string(trim($_POST['operator']));
    $layanan = $conn->real_escape_string($_POST['layanan']);
    $harga = $conn->real_escape_string($_POST['harga']);
    $harga_api = $conn->real_escape_string($_POST['harga_api']);
    $profit = $conn->real_escape_string(filter($_POST['profit']));
    $status = $conn->real_escape_string(trim($_POST['status']));
    $provider = $conn->real_escape_string(trim($_POST['provider']));   

    $cek_id = $conn->query("SELECT * FROM layanan_digital WHERE service_id = '$sid'");       

    if ($cek_id->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');                                   
    } else {
        if ($conn->query("UPDATE layanan_digital SET service_id = '$sid', provider_id = '$pid', layanan = '$layanan', operator = '$operator', harga = '$harga', harga_api = '$harga_api', profit = '$profit', status = '$status', provider_id = '$pid', provider = '$provider' WHERE service_id = '$sid'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Layanan Telah Berhasil Diubah <br />
                Service ID: '.$sid.' <br />
                Operator: '.$operator.' <br />
                Layanan: '.$layanan.' <br />
                Harga Web: '.$harga.' <br />
                Harga Api: '.$harga_api.' <br />
                Profit: '.$profit.' <br />
                Status: '.$status.' <br />
                Provider: '.$provider.' <br />
                ');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['delete'])) {
    $post_id = $conn->real_escape_string($_POST['id']);
    $cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE service_id = '$post_id'");
    if ($cek_layanan->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    } else {
        if ($conn->query("DELETE FROM layanan_digital WHERE service_id = '$post_id'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Layanan Berhasil Di Hapus');
        }
    }
}

?>   


<!-- Page-Title -->
<div class="row">
    <div class="col-md-12 mt-5">
        <br /><h2 class="text-center">Manajemen Data dan Harga Layanan Digital</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->



<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title m-t-0" id="myModalLabel"><i class="mdi mdi-newspaper"></i> Tambah Produk</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" method="POST">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Service ID</label>
                                <div class="col-md-10">
                                    <input type="text" name="sid" class="form-control" placeholder="Service ID">
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="col-md-2 control-label">Provider ID</label>
                                <div class="col-md-10">
                                    <input type="text" name="pid" class="form-control" placeholder="Provider ID">
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="col-md-2 control-label">Operator</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="operator">
                                        <option value="">Pilih Salah Satu...</option>
                                        <?php
                                        $cek_kategori = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Digital' ORDER BY nama ASC");
                                        while ($data_kategori = $cek_kategori->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $data_kategori['kode']; ?>"><?php echo $data_kategori['nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Layanan</label>
                                <div class="col-md-10">
                                    <input type="text" name="layanan" class="form-control" placeholder="Layanan">
                                </div>
                            </div>                                         
                            <div class="form-group">
                                <label class="col-md-2 control-label">Harga Web</label>
                                <div class="col-md-10">
                                    <input type="text" name="harga" class="form-control" placeholder="Harga Web
                                    ">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Harga Api</label>
                                <div class="col-md-10">
                                    <input type="text" name="harga_api" class="form-control" placeholder="Harga Api
                                    ">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Profit</label>
                                <div class="col-md-10">
                                    <input type="text" name="profit" class="form-control" placeholder="Keuntungan
                                    ">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="status">
                                        <option value="">Pilih Salah Satu...</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Gangguan">Gangguan</option>
                                    </select>
                                </div>
                            </div>      
                            <div class="form-group">
                                <label class="col-md-2 control-label">Provider</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="provider">
                                        <option value="">Pilih Salah Satu...</option>
                                        <?php
                                        $cek_provider = $conn->query("SELECT * FROM provider ORDER BY id ASC");
                                        while ($data_provider = $cek_provider->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $data_provider['code']; ?>"><?php echo $data_provider['code']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>                                                                        
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-danger btn-bordred waves-effect"><i aria-hidden="true" class="fa fa-refresh"></i> Reset</button>
                                <button type="submit" class="btn btn-info btn-bordred waves-effect w-md waves-light" name="tambah"><i aria-hidden="true" class="fa fa-plus"></i> Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <button data-toggle="modal" data-target="#addModal" class="btn btn-info btn-bordred waves-effect waves-light m-b-30"><i aria-hidden="true" class="fa fa-plus"></i> Tambah Produk</button> 
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-list"></i> Produk Digital</b></h4>                             
                <form>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label>Tampilkan Beberapa</label>
                            <select class="form-control" name="tampil">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Cari Layanan</label>
                            <input type="text" class="form-control" name="search" placeholder="Cari Layanan" value="">
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
                                <th>Service ID</th>
                                <th>Provider ID</th>
                                <th>Operator</th>
                                <th>Layanan</th>
                                <th>Harga <i class="mdi mdi-television-guide text-success"></i></th>
                                <th>Harga <i class="mdi mdi-shuffle-variant text-danger"></i></th>
                                <th>Profit <i class="mdi mdi-coin text-warning"></i></th>
                                <th>Status</th>
                                <th>Provider</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $cek_layanan = "SELECT * FROM layanan_digital WHERE layanan LIKE '%$search%' ORDER BY id ASC"; // edit
                                    } else {
                                    $cek_layanan = "SELECT * FROM layanan_digital ORDER BY id ASC"; // edit
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
                                    $new_query = $cek_layanan." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    // end paging config
                                    while ($data_layanan = $new_query->fetch_assoc()) {
                            ?>
                            <tr> 
                                <td><?php echo $data_layanan['service_id']; ?></td>
                                <td><?php echo $data_layanan['provider_id']; ?></td>
                                <td><?php echo $data_layanan['operator']; ?></td>
                                <td><?php echo $data_layanan['layanan']; ?></td>
                                <td><?php echo $data_layanan['harga']; ?></td>
                                <td><?php echo $data_layanan['harga_api']; ?></td>
                                <td><?php echo $data_layanan['profit']; ?></td>
                                <td><?php echo $data_layanan['status']; ?></td>
                                <td><?php echo $data_layanan['provider']; ?></td>
                                <td align="center">
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/layanan-digital/edit?id_layanan=<?php echo $data_layanan['service_id']; ?>')" class="btn btn-sm btn-warning"><i aria-hidden="true" class="fa fa-pencil" title="Edit"></i></a>
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/layanan-digital/delete?id_layanan=<?php echo $data_layanan['service_id']; ?>')" class="btn btn-sm btn-danger"><i aria-hidden="true" class="fa fa-trash" title="Hapus"></i></a>
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
                            $search = $conn->real_escape_string(filter($_GET['search']));
                            $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                        } else {
                            $self = $_SERVER['PHP_SELF'];
                        }
                        $cek_layanan = $conn->query($cek_layanan);
                        $total_records = mysqli_num_rows($cek_layanan);
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
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=1&tampil=".$cari_urut."&search=".$search."'><<</a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$previous."&tampil=".$cari_urut."&search=".$search."'><</a></li>";
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
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    if($i==$current_page) {
                                        echo "<li class='active page-item'><a class='page-link' href='#'>".$i."</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$i."&tampil=".$cari_urut."&search=".$search."'>".$i."</a></li>";
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
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$next."&tampil=".$cari_urut."&search=".$search."'>></a></li>";
                                    echo "<li class='page-item'><a class='page-link' href='".$self."?halaman=".$total_pages."&tampil=".$cari_urut."&search=".$search."'>>></a></li>";
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
                        <h4 class="modal-title mt-0" id="myModalLabel"><i aria-hidden="true" class="fa fa-list"></i> Layanan</h4>
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
require '../lib/footer_admin.php';
?>