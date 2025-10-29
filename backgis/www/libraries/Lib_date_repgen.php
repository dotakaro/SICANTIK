<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Lib_date
 * This class created for helping take date data from mysql
 *
 * @author Indra Halim
 * 
 *
 */
class Lib_date_repgen {

    private $lang;
	private $_bulanHijriah = array(
		1 => "Muharram", "Shofar", "Robi\'ul Awwal", "Robi\'uts Tsani",
			"Jumadil Ula", "Jumadil Akhiroh", "Rojab", "Sya'ban",
			"Romadhon", "Syawwal", "Dzulqo\'dah", "Dzulhijjah"
	);

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

	/**
	* ModifiedAutor Indra Halim
	* Modified 26 May 2013
	*/
    public function mysql_to_human($mysql_date = NULL, $format = NULL, $lang = 'id') {
        $this->mysql_date = $mysql_date;
        $this->get_date();
        if ($this->mysql_date === NULL || $this->mysql_date === '0000-00-00') {
            return "";
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
	* @Author Indra Halim
	* Created 26 May 2013
	*/
	public function get_tgl_sekarang(){
		$date_now = date('Y-m-d');
		$tgl_skrg=$this->mysql_to_human($date_now);
		return $tgl_skrg;
	}
	
	public function gregorian_to_hijriah($GYear, $GMonth, $GDay) {
		$y = $GYear;
		$m = $GMonth;
		$d = $GDay;
		$jd = GregoriantoJD($m, $d, $y);
		$l = $jd - 1948440 + 10632;
		$n = (int) (( $l - 1 ) / 10631);
		$l = $l - 10631 * $n + 354;
		$j = ( (int) (( 10985 - $l ) / 5316)) * ( (int) (( 50 * $l) / 17719)) + (
		(int) ( $l / 5670 )) * ( (int) (( 43 * $l ) / 15238 ));
		$l = $l - ( (int) (( 30 - $j ) / 15 )) * ( (int) (( 17719 * $j ) / 50)) - (
		(int) ( $j / 16 )) * ( (int) (( 15238 * $j ) / 43 )) + 29;
		$m = (int) (( 24 * $l ) / 709 );
		$d = $l - (int) (( 709 * $m ) / 24);
		$y = 30 * $n + $j - 30;
		 
		$Hijriah['year'] = $y;
		$Hijriah['month'] = $this->_bulanHijriah[$m];
		$Hijriah['day'] = $d;
		 
		return $Hijriah;
	}
	
	public function mysql_to_hijriah($mysql_date=null){
		$return ="";
		$temp_date=array();
		
		if($mysql_date != null){
			$temp_date=explode("-",$mysql_date);
			$hijriah=$this->gregorian_to_hijriah($temp_date[0],$temp_date[1],$temp_date[2]);
			$return=$hijriah['day']." ".$hijriah['month']." ".$hijriah['year'];
		}
		return $return;
	}

}
