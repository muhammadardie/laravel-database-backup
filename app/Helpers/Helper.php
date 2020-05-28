<?php
namespace App\Helpers;

use DB;

class Helper
{
    /**
    * Number Format for 'backend' and 'frontend'
    * @author moko
    *
    * @param $input number(without decimal) / string('$("input[name='number_1']")')
    * @param $output string (view|db|js|validation|clear_formatted_js|view_discount|dynamic_var_jquery)
    * @param $len_decimal integer
    * @param $in_thousand (, or .)
    * @return any format (number formatted, js, pattern, dll)
    */
    public static function number_formats($input, $output = 'view', $len_decimal = 2, $in_thousand = '.', $ignore_decimal = false)
    {
        $ls_in_thousand = array('', '.', ',');

        // check decimal length
        if (!is_int($len_decimal)) {
            die('length decimal is not number');
        }

        // check in_thousand pada array()
        if (!in_array($in_thousand, $ls_in_thousand)) {
            die('symbol in_thousand not found');
        }


        $symbol_decimal = '';

        // check $in_thousand
        // then, set symbol decimal
        if ($in_thousand == '.') {
            $symbol_decimal = ',';
        } elseif ($in_thousand == ',') {
            $symbol_decimal = '.';
        }

        if ($output == 'view') {
            return number_format($input, $len_decimal, $symbol_decimal, $in_thousand);
        } elseif ($output == 'db') {
            
            $tmp_symbol_decimal = str_replace($symbol_decimal, '#', $input); // # = temporary symbol; ex output: 1.234#76
            $reset_in_thousand  = str_replace($in_thousand, '', $tmp_symbol_decimal); // ex output: 1234#76
            $reset_tmp          = str_replace('#', '.', $reset_in_thousand); // ex output: 1234.76

            // fix reset
            // ex: 123457.33
            $reset              = number_format($reset_tmp, $len_decimal, '.', '');
            
            return $reset;

        } elseif ($output == 'js') {

            $js = "{$input}.number(true, $len_decimal, '$symbol_decimal', '$in_thousand');";
            return $js;

        } elseif ($output == 'validation') {

            $decimal_pattern = '';
            if ($len_decimal > 0) {

                if($ignore_decimal == false){
                    // set decimal pattern
                    // 123,123.44 => .44 is decimal
                    $decimal_pattern = "(\\$symbol_decimal\d+)"; // decimal is required
                }else if ($ignore_decimal == true){
                    if($symbol_decimal == '') $symbol_decimal = '.';
                    $decimal_pattern = "((\\$symbol_decimal\d+)?)"; // ? == decimal is optional
                }
            }

            // 123,234.233 => (123)(,234)(.223)
            return "/^(\d{1,3})($in_thousand\d{3})*$decimal_pattern$/"; // * == Zero or more

        }
        elseif ($output == 'clear_formatted_js') {

            return "replace(/\\{$in_thousand}/g, '').replace(/\\{$symbol_decimal}/g, '.');";

        } elseif ($output == 'view_currency') {

            return 'Rp. ' . number_format($input, $len_decimal, $symbol_decimal, $in_thousand);

        } elseif ($output == 'view_discount') {

            return number_format($input, $len_decimal, $symbol_decimal, $in_thousand) . ' %';

        } elseif ($output == 'dynamic_var_jquery') {

            return "number(true, $len_decimal, '$symbol_decimal', '$in_thousand');";

        }
    }


    /**
    * Date Format for 'backend' and 'frontend'
    * @author moko
    *
    * @param $input date | string('$("input[name='date_pickers']")')
    * @param $output string date (view|db|js|format)
    * @param $opt_js array
    * @param $date_db ;date format for DB
    * @param $date_php ;date format for PHP
    * @return any format (date view, date for input(js), date_php)
    */
    public static function date_formats($input, $output = 'view', $opt_js = array('format'=>'dd-mm-yyyy','autoclose'=>true), $date_db = 'Y-m-d', $date_php = 'd-m-Y')
    {
        if ($output == 'view') {

            $reset = date($date_php, strtotime($input));
            return (empty($input) ? '' : $reset);

        } elseif ($output == 'db') {

            $reset = date($date_db, strtotime($input));
            return (empty($input) ? '' : $reset);

        } elseif ($output == 'js') {
            // parsing date format from php to javascript
            if($date_php == 'Y-m-d') $opt_js['format'] = 'yyyy-mm-dd';
            if($date_php == 'Y-d-m') $opt_js['format'] = 'yyyy-dd-mm';
            if($date_php == 'd-m-Y') $opt_js['format'] = 'dd-mm-yyyy';

            $opt_datepicker = json_encode($opt_js);

            $js = "{$input}.datepicker(
                        {$opt_datepicker}
                    );";
            return $js;
        } elseif ($output == 'date_php') {
            return $date_php;
        }
    }

    public static function terbilang($x){
        $_this = new self;
        $x = abs($x);
        $angka = array("", "SATU", "DUA", "TIGA", "EMPAT", "LIMA",
        "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS");
        $temp = "";
        if ($x <12) {
            $temp = " ". $angka[$x];
        } else if ($x <20) {
            $temp = $_this->terbilang($x - 10). " BELAS";
        } else if ($x <100) {
            $temp = $_this->terbilang($x/10)." PULUH". $_this->terbilang($x % 10);
        } else if ($x <200) {
            $temp = " SERATUS" . $_this->terbilang($x - 100);
        } else if ($x <1000) {
            $temp = $_this->terbilang($x/100) . " RATUS" . $_this->terbilang($x % 100);
        } else if ($x <2000) {
            $temp = " SERIBU" . $_this->terbilang($x - 1000);
        } else if ($x <1000000) {
            $temp = $_this->terbilang($x/1000) . " RIBU" . $_this->terbilang($x % 1000);
        } else if ($x <1000000000) {
            $temp = $_this->terbilang($x/1000000) . " JUTA" . $_this->terbilang($x % 1000000);
        } else if ($x <1000000000000) {
            $temp = $_this->terbilang($x/1000000000) . " MILYAR" . $_this->terbilang(fmod($x,1000000000));
        } else if ($x <1000000000000000) {
            $temp = $_this->terbilang($x/1000000000000) . " TRILYUN" . $_this->terbilang(fmod($x,1000000000000));
        }     
            return $temp;
    }

    public static function tglIndo($tanggal){
        if($tanggal != null){
            $bulan = array (
                1 =>   'Januari',
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
            $pecahkan = explode('-', $tanggal);

            // check if $tanggal has jam menit detik
            if ( preg_match('/\s/',$tanggal)){
                $waktu    = explode(' ', $tanggal);
                $tanggal  = $waktu[0]; // tanggal bulan tahun
                $jam      = $waktu[1]; // jam menit detik
                $pecahkanWaktu = explode('-', $tanggal);

                return $pecahkanWaktu[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkanWaktu[0].' '. $jam;
            } else {
                return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
            }
        } else {
            return null;
        }
    }

    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}