<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller untuk menampilkan Report bertipe report standalone di Report Generator
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Report_display extends WRC_AdminCont
{

    public function __construct() {
        parent::__construct();
        $this->load->model('Report_generator_model');
        $this->Report_generator_model=new Report_generator_model();
        $this->_jasper_folder=realpath(BASEPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."jasper");
    }

    public function index(){
        $this->session_info['page_name'] = "Report Display";
        $data = array();
        $optReportCode = array();
        $getReportGenerator = $this->db->where('del_flag',0)
            ->where('display_type','standalone')->order_by('report_code','ASC')
            ->order_by('short_desc','ASC')->get('report_generators');
        if($getReportGenerator->num_rows() > 0){
            $optReportCode[' '] = "------Pilih salah satu------";
            foreach($getReportGenerator->result() as $reportGeneratorData){
                $optReportCode[$reportGeneratorData->id] = $reportGeneratorData->report_code.' - '.$reportGeneratorData->short_desc;
            }
        }
        $data['opsiReportCode'] = $optReportCode;
        $this->load->vars($data);
        $this->template->build('report_display_index', $this->session_info);
    }

    public function show($displayFormat = 'html'){
        $this->load->library('Lib_date_repgen');
        $DB2 = $this->load->database('report_display', true);//Load Konfigurasi Database Report Display

        $this->_load_additional_model();

        $this->load->model('Report_filter_model');
        $this->Report_filter_model = new Report_filter_model();

        $filters = $this->input->post('filter');
        if(!empty($filters)){
            foreach($filters as $filterKey=>$filterVal){
                if(is_array($filterVal)){//Jika value adalah array, gabungkan dengan tanda koma (,)
                    $$filterKey = implode(',',$filterVal);
                }else{
                    $$filterKey = $filterVal;
                }
            }
//            extract($filters);
        }

        $reportGeneratorId = $this->input->post('report_generator_id');
        $this->Report_generator_model = new Report_generator_model();
        $reportGeneratorData=$this->Report_generator_model->get_data($reportGeneratorId);
        /*$reportGeneratorData=$this->Report_generator_model->get_data($reportGeneratorId);
        $reportGroupData=$this->Report_group_data_model->get_all($reportGeneratorId);
        $reportSubreport=$this->Report_subreport_model->get_all($reportGeneratorId);*/
        $groupQueries=$this->Report_generator_model->getQueries($reportGeneratorId);
        $results = array();

        if(!empty($groupQueries)) {
            $group_data = $groupQueries[0];
            $group_data_code = $group_data['report_group_code'];
            $type = $group_data['type'];
            $direct_query = $group_data['direct_query'];
            $group_query = $group_data['group_query'];
            $use_direct_query = $group_data['use_direct_query'];

            if ($use_direct_query == 1) {//Jika  tipenya tabel atau multiple record
                $str_query = $direct_query;
            } else {
                $str_query = $group_query;
            }

            ob_start();
            error_reporting(0);

            $multipleQueries = explode(';', $str_query);
            $records = array();
            $sql_query = '';
            $query = new stdClass();
            if(!empty($multipleQueries)){
                $DB2->trans_start();
                foreach($multipleQueries as $str_query){
                    eval("\$sql_query = \"$str_query;\";");
                    echo $sql_query;
                    $query = $DB2->query($sql_query);
                }
                $DB2->trans_complete();
                $records = $query->result_array();
            }

            ob_end_clean();

            if (!empty($records)) {
                switch($type){
                    case 'T':
                    case 'F':
                        foreach ($records as $rowIndex=>$record) {
                            foreach ($record as $field => $value) {
                                $value = stripslashes($value);
                                ##Konversi ke tanggal Bahasa Indonesia##
                                if ($this->_isValidDate($value)) {
                                    $value = $this->lib_date_repgen->mysql_to_human($value);
                                }

                                ##############
                                $results[$rowIndex][$field] = $value;
                            }
                        }
                        break;
                    /*case 'P'://Jika tipe datanya Property Form
                        foreach ($records as $index => $value) {
                            $fields = array_keys($value);
                            if (count($fields) > 1) {//Ambil value 1 sebagai nama param, dan value 2 sebagai nilainya
                                $property_param_name = strtolower(str_replace(' ', '', $group_data_code . '_' . $value[$fields[0]]));
                                $property_value = $value[$fields[1]];
                                $singular_data[$group_data_code][$property_param_name] = $property_value;

                                if ($this->_isValidDate($property_value)) {
                                    $day = $this->lib_date_repgen->get_day($property_value);
                                    $tgl_hijriah = $this->lib_date_repgen->mysql_to_hijriah($property_value);
                                    $value = $this->lib_date_repgen->mysql_to_human($property_value);

                                    $singular_data[$group_data_code][$property_param_name . '_hijriah'] = $tgl_hijriah;
                                    $singular_data[$group_data_code][$property_param_name . '_day'] = $day;
                                }
                                if (is_numeric($property_value)) {//Jika value adalah numeric, maka sediakan terbilang dari value tersebut
                                    $terbilang = $this->terbilang->terbilang($property_value);
                                    $singular_data[$group_data_code][$property_param_name . '_terbilang'] = $terbilang;
                                }

                            } else {//Jika tidak ada 2 field, maka keluar dari looping
                                break;
                            }
                        }
                        break;*/
                }
            }
        }

        $data = array();
        $exportFileName = str_replace(' ','_',$reportGeneratorData->report_code).'_'.date('Ymd_His');
        $reportTitle = $reportGeneratorData->short_desc;

        $data['list'] = $results;
        $data['displayFormat'] = $displayFormat;
        $data['reportGeneratorId'] = $reportGeneratorId;
        $data['reportTitle'] = $reportTitle;
        $data['hiddenInputs'] = $_POST;

        switch($displayFormat){
            case 'excel':
                $this->load->library('phpexcel/PHPExcel');
                $objPHPExcel = new PHPExcel();

                //BEGIN - Generate Excel
                $objPHPExcel->getProperties()
                    ->setCreator('SICANTIK')
                    ->setLastModifiedBy("")
                    ->setTitle("Report Display")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Report Display, generated by SICANTIK")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Report");

                //FORMAT KOLOM
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcelSheet = $objPHPExcel->getActiveSheet();
                $sheet = $objPHPExcel->setActiveSheetIndex(0);

                if(!empty($results)){
                    //BEGIN - Generate Header Baris Pertama
                    $row = 1;
                    $col = 0;
                    foreach($results[0] as $field=>$value){
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
                        $col++;
                    }
                    //END - Generate Header Baris Pertama

                    //BEGIN - Generate Data Report
                    $row = 2;
                    foreach ($results as $index=>$data){
                        $col = 0;
                        foreach($data as $field=>$value){
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                            $col++;
                        }
                        $row++;
                    }
                    //END - Generate Data Report
                }

                //Center header
                $first_letter = PHPExcel_Cell::stringFromColumnIndex(0);
                $last_letter = PHPExcel_Cell::stringFromColumnIndex(count($results[0])-1);
                $header_range = "{$first_letter}1:{$last_letter}1";
                $objPHPExcelSheet->getStyle($header_range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                //Bold header
                $objPHPExcelSheet->getStyle($header_range)->getFont()->setBold(true);

                //Download excel
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //Excel2007

                // We'll be outputting an excel file
                header('Content-type: application/vnd.ms-excel');

                // It will be called file.xls
                header('Content-Disposition: attachment; filename="'.$exportFileName.'.xls"');

                // Write file to the browser
                $objWriter->save('php://output');
                //END - Generate Excel
                exit();
                break;
            case 'pdf':
//                ini_set('memory_limit','-1'); // boost the memory limit if it's low <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $this->load->library('mpdf/mpdf');
                $pdf = new mPDF('utf-8','A4-L',9,'arial');

                $html = $this->load->view('report_display_pdf', $data, true); // render the view into HTML
//                $pdf->debug = true;
                $pdf->showImageErrors = true;
//                $pdf->simpleTables  = true;
                $pdf->cacheTables = true;
                $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
                $pdf->WriteHTML($html); // write the HTML into the PDF
                $pdf->Output($exportFileName.'.pdf', 'D'); // save to file because we can
                break;
            default:
                $this->load->view('report_display_show', $data);
                break;
        }
    }

    function ajax_load_filter(){
        $this->load->model('Report_filter_model');
        $this->Report_filter_model = new Report_filter_model();
        $reportGeneratorId = $this->input->post('report_generator_id');
        $filters = $this->Report_filter_model->get_all_by_report_generator($reportGeneratorId);
        echo json_encode($filters);
    }

    private function _load_additional_model(){
        $this->load->model('Report_group_data_model');
        $this->Report_group_data_model=new Report_group_data_model();

        $this->load->model('Report_table_model');
        $this->Report_table_model=new Report_table_model();

        $this->load->model('Report_field_model');
        $this->Report_field_model=new Report_field_model();

        $this->load->model('Report_condition_model');
        $this->Report_condition_model=new Report_condition_model();

        $this->load->model('Report_subreport_model');
        $this->Report_subreport_model=new Report_subreport_model();
    }

    private function _isValidDateTime($dateTime)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }

        return false;
    }

    private function _isValidDate($date)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }

        return false;
    }

}
?>