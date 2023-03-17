<?php

function convert_date($value) {
		return date('d M Y - H:i:s', strtotime($value));
	}

function format_uang ($angka) {
    return number_format($angka, 0, ',', '.');
}

function terbilang ($angka) {
    $angka = abs($angka);
    $baca  = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $terbilang = '';

    if ($angka < 12) { // 0 - 11
        $terbilang = ' ' . $baca[$angka];
    } elseif ($angka < 20) { // 12 - 19
        $terbilang = terbilang($angka -10) . ' belas';
    } elseif ($angka < 100) { // 20 - 99
        $terbilang = terbilang($angka / 10) . ' puluh' . terbilang($angka % 10);
    } elseif ($angka < 200) { // 100 - 199
        $terbilang = ' seratus' . terbilang($angka -100);
    } elseif ($angka < 1000) { // 200 - 999
        $terbilang = terbilang($angka / 100) . ' ratus' . terbilang($angka % 100);
    } elseif ($angka < 2000) { // 1.000 - 1.999
        $terbilang = ' seribu' . terbilang($angka -1000);
    } elseif ($angka < 1000000) { // 2.000 - 999.999
        $terbilang = terbilang($angka / 1000) . ' ribu' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) { // 1000000 - 999.999.990
        $terbilang = terbilang($angka / 1000000) . ' juta' . terbilang($angka % 1000000);
    }

    return $terbilang;
}

function en_date($dt, $show_day = true)
{
    $dayName  = array(
        'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    );
    $monthName = array(1 =>
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    );

    $year   = substr($dt, 0, 4);
    $month   = $monthName[(int) substr($dt, 5, 2)];
    $date = substr($dt, 8, 2);
    $text    = '';

    if ($show_day) {
        $order_day = date('w', mktime(0,0,0, substr($dt, 5, 2), $date, $year));
        $day        = $dayName[$order_day];
        $text       .= "$day, $date $month $year";
    } else {
        $text       .= "$date $month $year";
    }
    
    return $text; 
}

function tambah_nol_didepan($value, $threshold = null)
{
    return sprintf("%0". $threshold . "s", $value);
}

?>