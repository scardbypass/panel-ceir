<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';
?>

<!--Title-->
<title>Bonus Member</title>
<meta name="description" content="Bonus Member Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>."/>

<!--OG2-->
<meta content="Bonus Member <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Bonus Member Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>." property="og:description"/>
<meta content="Bonus Member <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Bonus Member <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Bonus Member Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
    <div class="col-md-6">
        <div class="card-box">
            <center>                                
                <h1 class="m-t-0 text-uppercase text-center header-title"><i class="mdi mdi-earth mr1 text-primary"></i> <b>Bonus Member</b></h1>
            </center><br/>
            <div class="card">
                <div class="card-body table-responsive">
                    <center>
                    <h2 class="text-uppercase text-center header-title"><b>Produk Digital Bisnis Publisher</b></h2>
                    </center>
                    <hr>
                    <p>
                        <b>Materi FREE rasa PREMIUM.</b> Khusus untuk blogger, youtuber, dan publisher dunia bisnis online.
                    </p>
                    <ul>
                        <li>15000 artikel SEO. Membahas tips trik dan tutorial. Cocok untuk jangka panjang (Topik teknologi, bisnis, kesehatan, dan pendidikan)</li>
                        <li>500+ template blogger premium + license</li>
                        <li>100+ theme dan plugin wordpress premium + license</li>
                        <li>Berbagai ebook tips dan trik google adsense 2021</li>
                        <li>Berbagai trik SEO website/blog dan Youtube organik sesuai algoritma update</li>
                    </ul>
                    <p>
                        Materi dan file premium di update setiap bulan. Cek update di link di bawah, semoga berguna untuk aktivitas bisnis online di bidang blogging, youtube, publisher, jasa writer, dll.
                    </p>
                    <b>Catatan</b>
                    <ul>
                        <li>Disertakan panduan lengkap untuk pemula</li>
                        <li>Bersumber dari grup facebook Adsense Secret Indonesia</li>
                    </ul>
                    <b>Akses Bonus</b>
                    <ul>
                        <li>File host mediafire: <a href="" target="_blank"><b>Unduh</b></a>
                            <input type="text" value="https://www.mediafire.com/folder/y6y6a6aoz735o">
                        </li>
                        <li>Join grup facebook: <a href="https://www.facebook.com/groups/forumadsenseindonesia" target="_blank"><b>Adsense Secret Indonesia</b></a></li>
                        <li>Official fanpage: <a href="https://www.facebook.com/fanspageadsenseindonesia" target="_blank"><b>Adsense Secret Indonesia</b></a></li>
                    </ul>
                    <b>Penting</b>
                    <ul>
                        <li>Tidak untuk diperjual belikan. <b>Sharing Is Caring. Best regards!</b></li>
                    </ul>
                </div>
            </div>
        </div> <!-- card-box -->
    </div> <!-- /col-md-6 -->
    <div class="col-md-6">
        <div class="card-box">
            <center>                                
                <h1 class="m-t-0 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-rss text-primary"></i> <b>Blog <?php echo $data['short_title']; ?></b></h1>
            </center><br/>

            <div class="card">
                <div class="card-body table-responsive">
                    <center>
                    <h2 class="text-uppercase text-center header-title"><b>Artikel Terbaru <?php echo $data['short_title']; ?></b></h2>
                    </center>
                    <hr>
                    <p>
                        <?php echo $data['deskripsi_web']; ?>
                    </p>
                    <span>
                        <div id='postsexternal'>
                            <script type='text/javaScript'>
                                var rcp_numposts=8;
                                var rcp_snippet_length=150;
                                var rcp_info='no';
                                var rcp_comment='';
                                var rcp_disable='T?t Nh?n xét';
                                function recent_posts(json){var dw='';a=location.href;y=a.indexOf('?m=0');dw+='<ul>';for(var i=0;i<rcp_numposts;i++){var entry=json.feed.entry[i];var rcp_posttitle=entry.title.$t;if('content'in entry){var rcp_get_snippet=entry.content.$t}else{if('summary'in entry){var rcp_get_snippet=entry.summary.$t}else{var rcp_get_snippet="";}};rcp_get_snippet=rcp_get_snippet.replace(/<[^>]*>/g,"");if(rcp_get_snippet.length<rcp_snippet_length){var rcp_snippet=rcp_get_snippet}else{rcp_get_snippet=rcp_get_snippet.substring(0,rcp_snippet_length);var space=rcp_get_snippet.lastIndexOf(" ");rcp_snippet=rcp_get_snippet.substring(0,space)+"&#133;";};for(var j=0;j<entry.link.length;j++){if('thr$total'in entry){var rcp_commentsNum=entry.thr$total.$t+' '+rcp_comment}else{rcp_commentsNum=rcp_disable};if(entry.link[j].rel=='alternate'){var rcp_posturl=entry.link[j].href;if(y!=-1){rcp_posturl=rcp_posturl+'?m=0'}var rcp_postdate=entry.published.$t;if('media$thumbnail'in entry){var rcp_thumb=entry.media$thumbnail.url}else{rcp_thumb="https:/kitadigital.id/assets/images/kitadigital/kitadigital72.png"};}};dw+='<li>';dw+='<img alt="'+rcp_posttitle+'" src="'+rcp_thumb+'"/>';dw+='<div><a href="'+rcp_posturl+'" rel="dofollow">'+rcp_posttitle+'</a></div>';if(rcp_info=='yes'){dw+='<span>'+rcp_postdate.substring(8,10)+'/'+rcp_postdate.substring(5,7)+'/'+rcp_postdate.substring(0,4)+' - '+rcp_commentsNum+'</span>';};dw+='<div style="clear:both"></div></li>';};dw+='</ul>';document.getElementById('postsexternal').innerHTML=dw;};document.write('<script type=\"text/javascript\" src=\"='+rcp_numposts+'&callback=recent_posts\"><\/script>');
                            </script>
                        </div>
                        <div class="text-center">
                            <a href="https://kitadigital.my.id" class="btn btn-xs btn-primary"> <i class="mdi mdi-arrow-left-bold-box-outline"></i> Lainnya</a>
                        </div>
                    </span>

                </div>
            </div>
            
        </div> <!-- card-box -->
    </div> <!-- /col-md-6 -->
</div> <!-- /row -->

<?php
require '../lib/footer.php';
?>               