<?php
ob_start();
session_start();
if (!isset($_SESSION["id"])){ ?>
   <script>
    window.location.href = "../index.php";
   </script>
<?php }
$conn = new mysqli("localhost" , 'root', '' , 'portarum');

function generateUUID()
{
  return sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    random_int(0, 0xffff),
    random_int(0, 0xffff),
    random_int(0, 0xffff),
    random_int(0, 0x0fff) | 0x4000,
    random_int(0, 0x3fff) | 0x8000,
    random_int(0, 0xffff),
    random_int(0, 0xffff),
    random_int(0, 0xffff)
  );
}

$uuid = generateUUID();


function formatTanggal($date) {
    $bulan = [
        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
        5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
        9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
    ];

    $dt = new DateTime($date);

    return $dt->format('d') . ' ' . $bulan[(int)$dt->format('m')] . ' ' . $dt->format('y');
}

function formatTanggalRead($date) {
    $bulan = [
        1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
        5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
        9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
    ];

    $dt = new DateTime($date);

    return $dt->format('d') . ' ' . $bulan[(int)$dt->format('m')] . ' ' . $dt->format('Y');
}


function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return $diff . ' detik yang lalu';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit yang lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam yang lalu';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' hari yang lalu';
    } elseif ($diff < 2419200) {
        return floor($diff / 604800) . ' minggu yang lalu';
    } elseif ($diff < 29030400) {
        return floor($diff / 2419200) . ' bulan yang lalu';
    } else {
        return date('d M Y', $time);
    }
}

function waktuLalu($datetime) {
    $now = new DateTime();
    $time = new DateTime($datetime);
    $diff = $now->diff($time);

    if ($diff->y > 0) {
        return $diff->y . ' tahun yang lalu';
    } elseif ($diff->m > 0) {
        return $diff->m . ' bulan yang lalu';
    } elseif ($diff->d > 0) {
        return $diff->d . ' hari yang lalu';
    } elseif ($diff->h > 0) {
        return $diff->h . ' jam yang lalu';
    } elseif ($diff->i > 0) {
        return $diff->i . ' menit yang lalu';
    } else {
        return 'baru saja';
    }
}
?>