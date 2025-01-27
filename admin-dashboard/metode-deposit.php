<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';

if (isset($_POST['tambah'])) {
    $tipe = $conn->real_escape_string(filter($_POST['tipe']));
    $provider = $conn->real_escape_string(trim($_POST['provider']));
    $jalur = $conn->real_escape_string(trim($_POST['jalur']));
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $rate = $conn->real_escape_string($_POST['rate']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);
    $tujuan = $conn->real_escape_string($_POST['tujuan']);

    if (!$tipe || !$provider || !$jalur || !$nama || !$rate || !$keterangan || !$tujuan) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Harap Mengisi Semua Input');                               
    } else {
        if ($conn->query("INSERT INTO metode_depo VALUES ('', '$tipe', '$provider', '$jalur', '$nama', '$rate', '$keterangan', '$tujuan')") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Metode Deposit Baru Telah Berhasil Ditambahkan <br />
                Tipe: '.$tipe.' <br />
                Provider: '.$provider.' <br />
                Jalur: '.$jalur.' <br />
                Nama: '.$nama.' <br />
                Rate: '.$rate.' <br />
                Keterangan: '.$keterangan.' <br />
                Tujuan: '.$tujuan.' <br />
                ');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'Pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['edit'])) {
    $post_id = $conn->real_escape_string($_POST['id']);
    $tipe = $conn->real_escape_string(filter($_POST['tipe']));
    $provider = $conn->real_escape_string(trim($_POST['provider']));
    $jalur = $conn->real_escape_string(trim($_POST['jalur']));
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $rate = $conn->real_escape_string($_POST['rate']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);
    $tujuan = $conn->real_escape_string($_POST['tujuan']);

    $cek_id = $conn->query("SELECT * FROM metode_depo WHERE id = '$post_id'");       

    if ($cek_id->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'Pesan' => 'Data Tidak Di Temukan');                                   
    } else {
        if ($conn->query("UPDATE metode_depo SET tipe = '$tipe', provider = '$provider', jalur = '$jalur',  nama = '$nama', rate = '$rate', keterangan = '$keterangan',  tujuan = '$tujuan' WHERE id = '$post_id'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Metode Deposit Baru Telah Berhasil Diubah <br />
                Tipe: '.$tipe.' <br /> 
                Provider: '.$provider.' <br />
                Jalur: '.$jalur.' <br />
                Nama: '.$nama.' <br />
                Rate: '.$rate.' <br />
                Keterangan: '.$keterangan.' <br />
                Tujuan: '.$tujuan.' <br />   
                ');                    
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'Pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['delete'])) {
    $post_id = $conn->real_escape_string($_POST['id']);
    $cek_layanan = $conn->query("SELECT * FROM metode_depo WHERE id = '$post_id'");
    if ($cek_layanan->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'Pesan' => 'Data Tidak Di Temukan');
    } else {
        if ($conn->query("DELETE FROM metode_depo WHERE id = '$post_id'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Data Berhasil Di Hapus');
        }
    }
}

?>  
 
<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen Data Metode Deposit Pembayaran</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title m-t-0" id="myModalLabel"><i class="mdi mdi-newspaper"></i> Tambah Metode</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" method="POST">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tipe</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="tipe">
                                        <option value="">Pilih Salah Satu</option>
                                        <option value="Pulsa">Pulsa</option>
                                        <option value="Bank">Bank</option>
                                        <option value="EMoney">EMoney</option>
                                        <option value="EPayment">EPayment</option>
                                        <option value="ECurrency">ECurrency</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Provider</label>
                                <div class="col-md-10">
                                    <input type="text" name="provider" class="form-control" placeholder="BNI / GOPAY / TSEL, dll">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jalur</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="jalur">
                                        <option value="">Pilih Jalur</option>
                                        <option value="Auto">Auto</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Provider</label>
                                <div class="col-md-10">
                                    <input type="text" name="nama" class="form-control" placeholder="Gopay Transfer, dll">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Rate</label>
                                <div class="col-md-10">
                                    <input type="text" name="rate" class="form-control" placeholder="0.82 / 1 / 1.2, dll">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Keterangan</label>
                                <div class="col-md-10">
                                    <input type="text" name="keterangan" class="form-control" placeholder="ON / OFF">
                                </div>
                            </div>                                         
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tujuan</label>
                                <div class="col-md-10">
                                    <input type="text" name="tujuan" class="form-control" placeholder="085381259307 A/n Adi Gunawan, dll">
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
                <button data-toggle="modal" data-target="#addModal" class="btn btn-info btn-bordred waves-effect waves-light m-b-30"><i class="mdi mdi-wallet"></i> Tambah Metode</button>
                <a class="btn btn-danger btn-bordred waves-effect waves-light m-b-30" href="../get/del-all-deposit-error">DELL DEPO ERROR</a>
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-list"></i>Metode Deposit </b></h4>
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
                            <label>Cari Metode Deposit</label>
                            <input type="text" class="form-control" name="search" placeholder="Cari Metode Deposit" value="">
                        </div>
                        <div class="form-group col-lg-4">
                            <label>Submit</label>
                            <button type="submit" class="btn btn-block btn-dark">Filter</button><br/>

                            <!--UPDATE STATUS METODE DEPOSIT-->
                            <center>
                                <b>METODE DEPOSIT</b><br/>
                                <a class="btn btn-success" href="../get/upd-all-metode-depo-on">ON ALL</a>
                                <a class="btn btn-danger" href="../get/upd-all-metode-depo-off">OFF ALL</a>
                            </center>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipe</th>
                                <th>Provider</th>
                                <th>Jalur</th>
                                <th>Nama</th>
                                <th>Rate</th>
                                <th>Keterangan</th>
                                <th>Tujuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $cek_metode = "SELECT * FROM metode_depo WHERE nama LIKE '%$search%' ORDER BY id ASC"; // edit
                                    } else {
                                    $cek_metode = "SELECT * FROM metode_depo ORDER BY id ASC"; // edit
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
                                    $new_query = $cek_metode." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    // end paging config
                                    while ($data_method = $new_query->fetch_assoc()) {
                            ?>
                            <tr> 
                                <td><?php echo $data_method['id']; ?></td>
                                <td><?php echo $data_method['tipe']; ?></td>
                                <td><?php echo $data_method['provider']; ?></td>
                                <td><?php echo $data_method['jalur']; ?></td>
                                <td><?php echo $data_method['nama']; ?></td>
                                <td><?php echo $data_method['rate']; ?></td>
                                <td><?php echo $data_method['keterangan']; ?></td>
                                <td style="min-width: 200px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $data_method['tujuan']; ?>" id="tujuan-<?php echo $data_method['id']; ?>" readonly="">
                                        <button data-toggle="tooltip" title="Copy Target" class="btn btn-xs btn-primary" type="button" onclick="copy_to_clipboard('tujuan-<?php echo $data_method['id']; ?>')"><i class="mdi mdi-content-copy"></i></button>
                                    </div>
                                </td>
                                <td align="center">
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/metode-deposit/edit?data=<?php echo $data_method['id']; ?>')" class="btn btn-sm btn-warning"><i aria-hidden="true" class="fa fa-pencil" title="Edit"></i></a>
                                    <a href="javascript:;" onclick="users('<?php echo $config['web']['url'];?>admin-dashboard/ajax/metode-deposit/delete?data=<?php echo $data_method['id']; ?>')" class="btn btn-sm btn-danger"><i aria-hidden="true" class="fa fa-trash" title="Hapus"></i></a>
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
                        $cek_metode = $conn->query($cek_metode);
                        $total_records = mysqli_num_rows($cek_metode);
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
    function copy_to_clipboard(element) {
        var copyText = document.getElementById(element);
        copyText.select();
        document.execCommand("copy");
    }
</script>

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
                        <h4 class="modal-title mt-0" id="myModalLabel"><i class="mdi mdi-wallet"></i> Metode Deposit</h4>
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