<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Lib_date
 * This class created for helping take date data from mysql
 *
 * @author Dichi
 * 
 *
 */
class Lib_date {

    private $lang;

    public function __construct() {
        $this->CI = & get_instance();

        if ($this->CI->session->userdata('lang') === 'en') {
            $this->lang = "en";
        } else {
            $this->lang = "id";
        }
    }

    private function get_date() {
        $this->year = substr($this->mysql_date, 0, 4);
        $this->month = substr($this->mysql_date, 5, 2);
        $this->date = substr($this->mysql_date, 8, 2);
    }

    public function get_day($date = NULL) {

        $hari = array(
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        );
        list($yr, $mn, $dt) = explode('-', $date);
        $now = getdate(mktime(0, 0, 0, $mn, $dt, $yr));
        $i = $now['wday'];
        return $hari[$i];
    }

    public function get_datetime_now() {
        $date_now = date('Y-m-d G:i:s');
        return $date_now;
    }

    public function get_date_now() {
        $date_now = date('Y-m-d G:i:s');
        return $date_now;
    }

    public function set_date($date, $length = NULL) {
        if ($length === NULL)
            $length = 1;
        $day = 86400 * $length;
        $timestamp = strtotime($date);
        $date_value = date('Y-m-d', $timestamp + $day);
        return $date_value;
    }

    public function set_month_roman($month = NULL) {
        switch ($month) {
            case '1' :
                $month_text = 'I';
                break;
            case '2' :
                $month_text = 'II';
                break;
            case '3' :
                $month_text = 'III';
                break;
            case '4' :
                $month_text = 'IV';
                break;
            case '5' :
                $month_text = 'V';
                break;
            case '6' :
                $month_text = 'VI';
                break;
            case '7' :
                $month_text = 'VII';
                break;
            case '8' :
                $month_text = 'VIII';
                break;
            case '9' :
                $month_text = 'IX';
                break;
            case '10' :
                $month_text = 'X';
                break;
            case '11' :
                $month_text = 'XI';
                break;
            case '12' :
            default :
                $month_text = 'XII';
                break;
        }

        return $month_text;
    }

    public function set_month_name($month = NULL, $lang = NULL) {
        if ($lang === 'en') {
            switch ($month) {
                case '1' :
                    $month_text = 'January';
                    break;
                case '2' :
                    $month_text = 'February';
                    break;
                case '3' :
                    $month_text = 'March';
                    break;
                case '4' :
                    $month_text = 'April';
                    break;
                case '5' :
                    $month_text = 'May';
                    break;
                case '6' :
                    $month_text = 'June';
                    break;
                case '7' :
                    $month_text = 'July';
                    break;
                case '8' :
                    $month_text = 'August';
                    break;
                case '9' :
                    $month_text = 'September';
                    break;
                case '10' :
                    $month_text = 'October';
                    break;
                case '11' :
                    $month_text = 'November';
                    break;
                case '12' :
                default :
                    $month_text = 'December';
                    break;
            }
        } else if ($lang === "id") {
            switch ($month) {
                case '1' :
                    $month_text = 'Januari';
                    break;
                case '2' :
                    $month_text = 'Februari';
                    break;
                case '3' :
                    $month_text = 'Maret';
                    break;
                case '4' :
                    $month_text = 'April';
                    break;
                case '5' :
                    $month_text = 'Mei';
                    break;
                case '6' :
                    $month_text = 'Juni';
                    break;
                case '7' :
                    $month_text = 'Juli';
                    break;
                case '8' :
                    $month_text = 'Agustus';
                    break;
                case '9' :
                    $month_text = 'September';
                    break;
                case '10' :
                    $month_text = 'Oktober';
                    break;
                case '11' :
                    $month_text = 'November';
                    break;
                case '12' :
                default :
                    $month_text = 'Desember';
                    break;
            }
        }

        return $month_text;
    }

    public function date_range($date_begin = NULL, $date_end = NULL) {

        if ($this->lang === 'en') {
            $to = " to ";
        } else {
            $to = " sampai ";
        }

        $this->mysql_date = $date_begin;
        $this->get_date();

        $year_begin = $this->year;
        $month_begin = $this->month;
        $date_begin = $this->date;

        $this->mysql_date = $date_end;
        $this->get_date();

        $year_end = $this->year;
        $month_end = $this->month;
        $date_end = $this->date;

        if ($date_begin === $date_end && $month_begin === $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin . ' ' . $this->set_month_name($month_begin, $this->lang) . ' '
                    . $year_begin;
        } else if ($date_begin !== $date_end && $month_begin === $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin . $to . $date_end . ' ' .
                    $this->set_month_name($month_begin, $this->lang) . ' '
                    . $year_begin;
        } else if ($date_begin !== $date_end && $month_begin !== $month_end &&
                $year_begin === $year_end) {
            $date_range = $date_begin .
                    ' ' . $this->set_month_name($month_begin, $this->lang) .
                    $to . $date_end . ' ' .
                    $this->set_month_name($month_end, $this->lang) . ' '
                    . $year_begin;
        } else {
            $date_range = $date_begin .
                    ' ' . $this->set_month_name($month_begin, $this->lang) . ' ' .
                    $year_begin . $to . $date_end . ' ' .
                    $this->set_month_name($month_end, $this->lang) . ' '
                    . $year_end;
        }

        return $date_range;
    }

    public function mysql_to_human($mysql_date = NULL, $format = NULL, $lang = 'id') {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        if ($this->mysql_date === NULL || $this->mysql_date === '0000-00-00') {
            return "Tanggal belum diset.";
        } else if ($format === NULL) {
            return $this->date . " " . $this->set_month_name($this->month, $lang) . " " . $this->year;
        } else {
            return $this->date . "-" . $this->month . "-" . $this->year;
        }
    }

    public function mysql_get_date($mysql_date = NULL, $type = NULL) {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        if ($type === 'date') {
            return $this->date;
        } else if ($type === 'month') {
            return $this->set_month_name($this->month, $this->lang);
        } else {
            return $this->year;
        }
    }
	
	/**
	* Added By Indra
	* Untuk keperluan konversi masa berlaku dari tahun, bulan ke hari
	*
	**/
	public function convert_to_day($numeric_value=NULL,$satuan=''){
		$return=0;
		$days_in_year=365;
		$days_in_month=30;
		if(!is_null($numeric_value)&&($satuan!=''||!is_null($satuan))){
			switch($satuan){
				case 'bulan':
					$return=$numeric_value*$days_in_month;
					break;
				case 'tahun':
					$return=$numeric_value*$days_in_year;
					break;
				default:
					break;
			}
		}
		return $return;
	}
	
	/**
     * @Author Indra Halim
	 * function untuk memodifikasi tanggal dengan parameter operator serta tipenya, hasilnya disimpan dan dapat dimodifikasi lagi
     * @param string $value Nilai yang akan digunakan untuk mengubah tanggal, dapat berupa nilai positif maupun negatif misal +1, -2
     * @param string $modType Tipe pengubahan, dapat berupa: "day","week","month","year"
     */
    public function modDate($date,$value,$modType=''){
     	$new_date="";
		$dateFormat="Y-m-d";
		$modifier="";
		if($date!=''&&$value!=''){
			$modType=strtolower($modType);
			
			switch($modType){
				case 'hari':
					$modifier="day";
					break;
				case 'bulan':
					$modifier="month";
					break;
				case 'minggu':
					$modifier="week";
					break;
				default:
					$modifier="year";
					break;
			}
			
	        $dateAdded = strtotime($date . "$value $modifier");
			$new_date=date($dateFormat,$dateAdded);
		}
		return $new_date;
    }

}
