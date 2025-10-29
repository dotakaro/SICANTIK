<?php
class perubahan_tinjauan extends WRC_AdminCont {
    private $_status_entry_tinjauan = 0;//Entry Hasil Tinjauan;
    private $_status_penjadwalan = 0; //Penjadwalan Tinjauan

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->pegawai = new tmpegawai();
        $this->survey = new trtanggal_survey();

        /*$this->permohonan = NULL;
        $this->pegawai = NULL;
        $this->survey = NULL;
        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '11' || $list_auth->id_role === '12') {
                $enabled = TRUE;
                $this->permohonan = new tmpermohonan();
                $this->pegawai = new tmpegawai();
                $this->survey = new trtanggal_survey();
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

	public function edit($tim_teknis_id = NULL) {

        $current_unitkerja = null;
        $mapping_unitkerja = array();

        $this->tim_teknis = new tim_teknis();
        $this->trtanggal_survey = new trtanggal_survey();
        $p_tim_teknis = $this->tim_teknis->get_by_id($tim_teknis_id);
        if(count($p_tim_teknis)>0):
            $trtanggal_survey_id = $p_tim_teknis->trtanggal_survey_id;
            //$p_daftar = $this->permohonan->get_by_id($id_daftar);
            $p_daftar = $this->permohonan->where_related_trtanggal_survey('id',$trtanggal_survey_id)->get();
            $permohonanUnitKerjaId = $p_daftar->trunitkerja_id;

            $id_daftar = $p_daftar->id;
            $p_pemohon = $p_daftar->tmpemohon->get();
            $p_jenis = $p_daftar->trjenis_permohonan->get();
            $p_izin = $p_daftar->trperizinan->get();
            
            $p_kelompok = $p_daftar->trperizinan->trkelompok_perizinan->get();
            $p_kelurahan = $p_pemohon->trkelurahan->get();
            $p_kecamatan = $p_kelurahan->trkecamatan->get();
            $p_kabupaten = $p_kecamatan->trkabupaten->get();
            $p_prov = $p_kabupaten->trpropinsi->get();

            $trperizinan_id = $p_izin->id;

            $this->permohonan->tmsurat_rekomendasi->get();
            $data['tim_teknis_id'] = $tim_teknis_id;
            $data['trunitkerja_id'] = $p_tim_teknis->trunitkerja_id;

            $data['id_daftar'] = $id_daftar;
            $data['permohonan'] = $p_daftar;
            $data['waktu_awal'] = $this->lib_date->get_datetime_now();
            $data['no_daftar'] = $p_daftar->pendaftaran_id;
            $data['nama_pemohon'] = $p_pemohon->n_pemohon;
            $data['alamat_pemohon'] = $p_pemohon->a_pemohon . ', ' . $p_kelurahan->n_kelurahan . ', ' . $p_kecamatan->n_kecamatan . ', ' .
                                    $p_kabupaten->n_kabupaten . ', ' . $p_prov->n_propinsi;
            $data['jenis_izin'] = $p_izin->n_perizinan;
            $data['nama_jenis'] = $p_jenis->n_permohonan;
            $data['nama_kelompok'] = $p_kelompok->n_kelompok;
            $data['list'] = $p_izin->trproperty->order_by('c_parent_order asc, c_order asc')->get();
            
            $data['list_daftar'] = $p_daftar->tmproperty_jenisperizinan
                    ->where('tim_teknis_id',$tim_teknis_id)
                    ->where_in('entry_flag',array(2,3))->get();

            $data['list_klasifikasi'] = $p_daftar->tmproperty_klasifikasi->get();
            $data['list_prasarana'] = $p_daftar->tmproperty_prasarana->get();
                    $data['no_surat'] = $this->permohonan->tmsurat_rekomendasi->no_surat;
            $data['tgl_surat'] = $this->permohonan->tmsurat_rekomendasi->tgl_surat;
            $data['deskripsi'] = $this->permohonan->tmsurat_rekomendasi->deskripsi;
                    $data['arr_izin_rekomendasi']=$this->__get_izin_dengan_rekomendasi();
            $js = "
                    $(document).ready(function() {
                        $(\"#tabs\").tabs();
                        $(\"#tgl_surat\").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'yy-mm-dd',
                            closeText: 'X'
                        });
                    } );
                ";

            $this->template->set_metadata_javascript($js);
            $data['from'] = 'survey/result';
            $data['id'] = $id_daftar;
            $entry = new tmproperty_jenisperizinan();
            $entry->where_related($p_daftar)->get();
            //if($entry->id) $save = 'updateresult';
            $save = 'saveresult';
            $data['save_method'] = $save;


            //if(!$this->__is_administrator()){
                #### Ambil Unit Kerja dari user yang login ####
                $this->load->model('pengguna/user');

                $this->user = new user();
                $user_id = $this->session_info['id_auth'];
                $current_user = $this->user->get_by_id($user_id);
                $current_unitkerja = $current_user->tmpegawai->trunitkerja->get();
                ################################################

                ### Ambil Setting Property Tim Teknis ###
                $this->load->model('property_tim_teknis/property_teknis_header');
                $this->property_teknis_header = new property_teknis_header();
                $property_header = $this->property_teknis_header
                    ->where('trperizinan_id', $trperizinan_id)
                    ->where('trunitkerja_id', $permohonanUnitKerjaId)
                    ->get();
                foreach($property_header->property_teknis_detail as $key=>$detail){
                    $mapping_unitkerja[$detail->trproperty_id] = $detail->trunitkerja_id;
                }
                #########################################
            //}
            
            $data['current_unitkerja'] = $current_unitkerja;
            $data['mapping_unitkerja'] = $mapping_unitkerja;

            $this->load->vars($data);

            $this->session_info['page_name'] = "Data Entry Hasil Tinjauan";

            /*foreach($data['list'] as $list){
                echo $list->n_property.'-'.$list->c_type.'<br>';
            }*/
            $this->template->build('tinjauan_edit', $this->session_info);
        endif;
    }
}
?>