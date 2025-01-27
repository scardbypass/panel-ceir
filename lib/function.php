<?php

function tanggal_indo($date)
{
    $bulan = array (1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

function filter($data){

$filter = stripslashes(strip_tags(htmlspecialchars(htmlentities($data,ENT_QUOTES))));

return $filter;

}

function acak($length) {
	$str = "";
	$karakter = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max_karakter = count($karakter) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max_karakter);
		$str .= $karakter[$rand];
	}
	return $str;
}

function acak_nomor($length) {
	$str = "";
	$karakter = array_merge(range('0','9'));
	$max_karakter = count($karakter) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max_karakter);
		$str .= $karakter[$rand];
	}
	return $str;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'Tahun',
        'm' => 'Bulan',
        'w' => 'Minggu',
        'd' => 'Hari',
        'h' => 'Jam',
        'i' => 'Menit',
        's' => 'Detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' Yang Lalu' : 'Baru Saja';
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } elseif (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}

function infojson($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
                return $data;
}

function Show($tabel, $limit) {
        global $conn;
        $CallData = mysqli_query($conn, "SELECT * FROM ".$tabel." WHERE ".$limit);
        $ThisData = mysqli_fetch_assoc($CallData);
        return $ThisData;
}

function followers_count($data){
    $id = file_get_contents("https://instagram.com/web/search/topsearch/?query=".$data);
    $id = json_decode($id, true);
    $count = $id['users'][0]['user']['follower_count'];
    return $count;
}

function likes_count($data){
    $id = file_get_contents("".$data."?&__a=1");
    $id = json_decode($id, true);
    $count = $id['graphql']['shortcode_media']['edge_media_preview_like']['count'];
    return $count;
}

function views_count($data){
    $id = file_get_contents("".$data."?&__a=1");
    $id = json_decode($id, true);
    $count = $id['graphql']['shortcode_media']['video_view_count'];
    return $count;
}

function daftar($data) {
    global $conn;

    $nama = $conn->real_escape_string(filter(trim($data['nama'])));
    $email = $conn->real_escape_string(filter(trim($data['email'])));
    $no_hp = $conn->real_escape_string(filter(trim($data['no_hp'])));
    $username = filter($data['username']);
    $password = $conn->real_escape_string(trim($data['password']));
    $password2 = $conn->real_escape_string(trim($data['password2']));
    $cek_nama = $conn->query("SELECT * FROM users WHERE nama = '$nama'");
    $cek_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $cek_username = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if (!$nama || !$email || !$username || !$password) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Nama Lengkap<br/> - Email Aktif<br/> - Username Login<br/> - Password Akun<br/> - Konfirmasi Password');
    return false;
    } else if ($cek_username->num_rows > 0) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Username <strong>'.$username.' </strong> telah terdaftar'); 
    return false;
    } else if ($cek_email->num_rows > 0) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Email <strong> '.$email.' </strong> telah terdaftar'); 
    return false;
    } elseif (strlen($username) < 5) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Username Minimal 5 Karakter Tanpa Spasi');
    return false;
    } elseif (strlen($password) < 5) {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Password minimal 5 Karakter Tanpa Spasi');
    return false;
    } else if ($password <> $password2){
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Konfirmasi password tidak sesuai');
        return false;
    }

    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $api_key =  acak(32);
    $terdaftar = date("Y-m-d H:i:s");

    $conn->query("INSERT INTO users VALUES (NULL, '$nama', '$email', '$no_hp', '$username', '$hash_pass', '0', '0', 'Member', 'Aktif', '$api_key', 'Pendaftaran Gratis', '$terdaftar', '0','')");
    return mysqli_affected_rows($conn);
}

?>