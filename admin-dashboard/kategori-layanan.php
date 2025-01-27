<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';

if (isset($_POST['tambah'])) {
    $nama = $conn->real_escape_string(filter($_POST['nama']));
    $kode = $conn->real_escape_string(trim($_POST['kode']));
    $tipe = $conn->real_escape_string(trim($_POST['tipe']));

    if (!$nama || !$kode || !$tipe) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Harap Mengisi Semua Input');                               
    } else {
        if ($conn->query("INSERT INTO kategori_layanan VALUES ('', '$nama', '$kode', '$tipe')") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Kategori Baru Telah Berhasil Ditambahkan <br />
                Nama: '.$nama.' <br />
                Kategori : '.$kode.' <br />
                Tipe: '.$tipe.' <br />
                ');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['edit'])) {
    $get_id = $conn->real_escape_string(filter($_GET['id_kategori']));
    $nama = $conn->real_escape_string(filter($_POST['nama']));
    $kode = $conn->real_escape_string(trim($_POST['kode']));
    $tipe = $conn->real_escape_string(trim($_POST['tipe']));

    $cek_id = $conn->query("SELECT * FROM kategori_layanan WHERE id = '$get_id'");       

    if ($cek_id->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');                                   
    } else {
        if ($conn->query("UPDATE kategori_layanan SET nama = '$nama', kode = '$kode', tipe = '$tipe' WHERE id = '$get_id'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => '
                Kategori Telah Berhasil Diubah <br />
                Nama: '.$nama.' <br />
                Kategori : '.$kode.' <br />
                Tipe: '.$tipe.' <br /> 
                ');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['delete'])) {
    $get_id = $conn->real_escape_string(filter($_GET['id_kategori']));
    $cek_kategori = $conn->query("SELECT * FROM kategori_layanan WHERE id = '$get_id'");
    if ($cek_kategori->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    } else {
        if ($conn->query("DELETE FROM kategori_layanan WHERE id = '$get_id'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Kategori Berhasil Di Hapus');
        }
    }
}

?>
   
<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen Data Kategori Produk dan Layanan</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title m-t-0" id="myModalLabel"><i class="mdi mdi-newspaper"></i> Tambah Layanan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" method="POST">
                            <div class="form-group">
                                <label class="col-md-10 control-label">Nama Kategori</label>
                                <div class="col-md-10">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Kategori">
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="col-md-10 control-label">Kode Kategori</label>
                                <div class="col-md-10">
                                    <input type="text" name="kode" class="form-control" placeholder="Kode Kategori">
                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tipe</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="tipe">
                                        <option value="">Pilih Salah Satu...</option>
                                        <option value="Sosial Media">SOSIAL MEDIA</option>
                                        <option value="SOSMED">PRODUK DIGITAL</option>
                                        <option value="PULSA">PULSA</option>
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
                <button data-toggle="modal" data-target="#addModal" class="btn btn-info btn-bordred waves-effect waves-light m-b-30"><i aria-hidden="true" class="fa fa-plus"></i> Tambah Kategori</button> 
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-list"></i> Kelola Kategori</b></h4>               
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
                            <label>Cari Kategori</label>
                            <input type="text" class="form-control" name="search" placeholder="Cari Kategori" value="">
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
                                <th>Nama Kategori</th>
                                <th>Kode Kategori</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['search'])) {
                                    $search = $conn->real_escape_string(filter($_GET['search']));
                                    $cek_kategori = "SELECT * FROM kategori_layanan WHERE nama LIKE '%$search%' ORDER BY id ASC"; // edit
                                    } else {
                                    $cek_kategori = "SELECT * FROM kategori_layanan ORDER BY id ASC"; // edit
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
                                    $new_query = $cek_kategori." LIMIT $starting_position, $records_per_page";
                                    $new_query = $conn->query($new_query);
                                    // end paging config
                                    while ($data_kategori = $new_query->fetch_assoc()) {
                            ?>
                            <tr> 
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id_kategori=<?php echo $data_kategori['id']; ?>" class="form-inline" role="form" method="POST">
                                    <td><input type="text" class="form-control" style="width: 300px;" name="nama" value="<?php echo $data_kategori['nama']; ?>"></td>
                                    <td><input type="text" class="form-control" style="width: 300px;" name="kode" value="<?php echo $data_kategori['kode']; ?>"></td>
                                    <td>
                                        <select class="form-control" style="width: 300px;" name="tipe">
                                            <option value="<?php echo $data_kategori['tipe']; ?>"><?php echo $data_kategori['tipe']; ?></option>
                                            <option value="Sosial Media">SOSIAL MEDIA</option>
                                            <option value="PULSA">PULSA</option>
                                        </select>
                                    </td>
                                    <td align="center">
                                        <button data-toggle="tooltip" title="Update" type="submit" name="edit" class="btn btn-sm btn-bordred btn-warning"><i aria-hidden="true" class="fa fa-edit" title="Edit"></i></button>
                                        <button data-toggle="tooltip" title="Hapus"type="submit" name="delete" class="btn btn-sm btn-bordred btn-danger"><i aria-hidden="true" class="fa fa-trash" title="Hapus"></i></button>
                                    </td> 
                                </from>                                
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
                        $cek_kategori = $conn->query($cek_kategori);
                        $total_records = mysqli_num_rows($cek_kategori);
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