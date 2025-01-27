<?php
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';
require '../lib/header.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string(trim(filter($_GET['id'])));
    if (!$id) {
        header("Location: ".$config['web']['url']);
    } else {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $back = $_SERVER['HTTP_REFERER'];
        } else {
            $back = $config['web']['url'];
    }

    $check_news = $conn->query("SELECT * FROM berita WHERE id = '$id'");
    $data_news = $check_news->fetch_assoc();

    if (mysqli_num_rows($check_news) == 0)
        header("Location: ".$config['web']['url']);
?>

<!--Title-->
<title>Berita & Informasi</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i class=" mdi mdi-newspaper "></i><b> <?= $data_news['subjek']; ?></b></h4><hr/>
                <span>
                    <?= nl2br($data_news['konten']); ?><hr/>
                    <small>Ditulis oleh <?php echo $data['short_title']; ?>. Tanggal <?php echo tanggal_indo($data_news['date']); ?></small>
                </span>
                <div class="text-center">
                    <a href="<?= $back; ?>" class="btn btn-xs btn-primary"> <i class="mdi mdi-arrow-left-bold-box-outline"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i class=" mdi mdi-newspaper "></i> <b>BERITA & INFORMASI</h4></b></h4><hr/>
                <div class="table-responsive" style="display: inline-grid;">
                    <table class="able table-striped table-hovered nowrap mb-0">
                        <tbody>
                            <?php $check_news = $conn->query("SELECT * FROM berita ORDER BY id DESC LIMIT 14"); ?>
                            <?php while ($data_news = $check_news->fetch_assoc()) { ?>
                                <?php
                                if ($data_news['tipe'] == "INFORMASI") $btn = "info";
                                if ($data_news['tipe'] == "PERINGATAN") $btn = "warning";
                                if ($data_news['tipe'] == "PENTING") $btn = "danger";
                                if ($data_news['tipe'] == "LAYANAN") $btn = "success";
                                if ($data_news['tipe'] == "PERBAIKAN") $btn = "primary";
                                ?>
                                <tr>
                                    <td width="20">
                                        <a href="<?= $config['web']['url']; ?>user/news?id=<?= $data_news['id']; ?>" class="btn btn-xs btn-<?= $btn; ?>"><i class="fas fa-info-circle"></i>
                                        </a>
                                    </td>
                                    <td style="padding-left: 5px;">
                                        <a href="<?= $config['web']['url']; ?>user/news?id=<?= $data_news['id']; ?>" class="text-dark"><?= $data_news['subjek']; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
} else {
    ?>

<!--Title-->
<title>Berita & Informasi</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i class=" mdi mdi-newspaper "></i> <b>INFORMASI</h4></b></h4><hr/>
                <div class="table-responsive" style="display: inline-grid;">
                    <table class="able table-striped table-hovered nowrap mb-0">
                        <tbody>
                            <?php $check_news = $conn->query("SELECT * FROM berita ORDER BY id DESC LIMIT 14"); ?>
                            <?php while ($data_news = $check_news->fetch_assoc()) { ?>
                                <?php
                                if ($data_news['tipe'] == "INFORMASI") $btn = "info";
                                if ($data_news['tipe'] == "PERINGATAN") $btn = "warning";
                                if ($data_news['tipe'] == "PENTING") $btn = "danger";
                                if ($data_news['tipe'] == "LAYANAN") $btn = "success";
                                if ($data_news['tipe'] == "PERBAIKAN") $btn = "primary";
                                ?>
                                <tr>
                                    <td width="20">
                                        <a href="<?= $config['web']['url']; ?>user/news?id=<?= $data_news['id']; ?>" class="btn btn-xs btn-<?= $btn; ?>"><i class="fas fa-info-circle"></i>
                                        </a>
                                    </td>
                                    <td style="padding-left: 5px;">
                                        <a href="<?= $config['web']['url']; ?>user/news?id=<?= $data_news['id']; ?>" class="text-dark"><?= $data_news['subjek']; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <a href="<?= $config['web']['url']; ?>" class="btn btn-xs btn-primary"> <i class="mdi mdi-arrow-left-bold-box-outline"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-rss "></i> <b>ARTIKEL</b></h4><hr/>
                <span>
                    <div id='postsexternal'>
                        <script type='text/javaScript'>
                            var rcp_numposts=7;
                            var rcp_snippet_length=150;
                            var rcp_info='no';
                            var rcp_comment='';
                            var rcp_disable='T?t Nh?n xét';
                            function recent_posts(json){var dw='';a=location.href;y=a.indexOf('?m=0');dw+='<ul>';for(var i=0;i<rcp_numposts;i++){var entry=json.feed.entry[i];var rcp_posttitle=entry.title.$t;if('content'in entry){var rcp_get_snippet=entry.content.$t}else{if('summary'in entry){var rcp_get_snippet=entry.summary.$t}else{var rcp_get_snippet="";}};rcp_get_snippet=rcp_get_snippet.replace(/<[^>]*>/g,"");if(rcp_get_snippet.length<rcp_snippet_length){var rcp_snippet=rcp_get_snippet}else{rcp_get_snippet=rcp_get_snippet.substring(0,rcp_snippet_length);var space=rcp_get_snippet.lastIndexOf(" ");rcp_snippet=rcp_get_snippet.substring(0,space)+"&#133;";};for(var j=0;j<entry.link.length;j++){if('thr$total'in entry){var rcp_commentsNum=entry.thr$total.$t+' '+rcp_comment}else{rcp_commentsNum=rcp_disable};if(entry.link[j].rel=='alternate'){var rcp_posturl=entry.link[j].href;if(y!=-1){rcp_posturl=rcp_posturl+'?m=0'}var rcp_postdate=entry.published.$t;if('media$thumbnail'in entry){var rcp_thumb=entry.media$thumbnail.url}else{rcp_thumb="https:/kitadigital.id/assets/images/kitadigital/kitadigital72.png"};}};dw+='<li>';dw+='<img alt="'+rcp_posttitle+'" src="'+rcp_thumb+'"/>';dw+='<div><a href="'+rcp_posturl+'" rel="dofollow">'+rcp_posttitle+'</a></div>';if(rcp_info=='yes'){dw+='<span>'+rcp_postdate.substring(8,10)+'/'+rcp_postdate.substring(5,7)+'/'+rcp_postdate.substring(0,4)+' - '+rcp_commentsNum+'</span>';};dw+='<div style="clear:both"></div></li>';};dw+='</ul>';document.getElementById('postsexternal').innerHTML=dw;};document.write('<script type=\"text/javascript\" src=\"'+rcp_numposts+'&callback=recent_posts\"><\/script>');
                        </script>
                    </div>
                </span>
            </div>
        </div>
    </div>
   
</div>

<?php
    }

require '../lib/footer.php';