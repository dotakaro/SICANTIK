<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of testproperty class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Test extends WRC_AdminCont {
    
    public function __construct() {
        parent::__construct();
        $this->rel = new xpost();
        $this->level = NULL;
        $this->tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
    }

    public function index() {
        $lists = $this->rel->get();
        $anak = NULL;
        $induk = NULL;
        foreach ($lists as $list) {
            $list->induk->get();
            $list->xpost->get();

            if($list->induk->count() === 0) {
                echo $list->catatan;
                echo "<br />";
                $this->child_check($list->xpost);
            }
        }
        echo "---------------- ";
        echo "<br />";
    }

    private function child_check($node = NULL) {
        if($node->count() === 0) {
            return FALSE;
        }

        foreach ($node as $list) {
            $this->level++;
            echo str_repeat(str_repeat("&nbsp;", 5), $this->level) . " " . $list->catatan;
            echo "<br />";
            $var = $list->xpost;
            $this->child_check($var);
            $this->level--;
            $this->tab = "";
        }
    }
    
    public function tXML(){
        $this->load->view('cXML');
    }

    public function test1() {
        $perizinan = new trperizinan();
        $perizinan->get_by_id('1');

        $lists = $perizinan->trproperty->where('c_type', 2)->order_by('c_parent_order', 'asc')->get();

        foreach ($lists as $list) {
            echo $list->n_property;
            echo "<br />";
            $children = $perizinan->trproperty->where_join_field($perizinan, 'c_parent', $list->id)->include_join_fields()->order_by('c_order', "asc")->get();
            foreach ($children as $child) {
                if($list->id !== $child->id) {

                    $prop = $perizinan->trproperty->tmproperty_jenisperizinan->get();
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $child->n_property . " : ..........";
                    if($child->join_c_retribusi_id === '1') {
                        echo "lalalala";
                    } 
                    echo "<br />";
                }
            }
        }
    }

    public function test2() {
        $permohonan = new tmpermohonan();
        $permohonan->where('id', 51);
        $list = $permohonan->get();

        $pemohon = $list->tmpemohon->get();
        $perizinan = $list->trperizinan->get();
        
        echo $pemohon->n_pemohon;
        echo "<br />";
        echo $perizinan->n_perizinan;
        echo "<br />";
        $property = $perizinan->trproperty->where('id', 10)->get();
        echo $property->n_property . " ";
        $value = $property->tmproperty_jenisperizinan->where('pendaftaran_id',$permohonan->pendaftaran_id)->get();
        foreach ($value as $list_value) {
            echo $list_value->v_tinjauan;
            echo "<br />";
        }
        $property = $perizinan->trproperty->where('id', 31)->get();
        echo $property->n_property . " ";
        $value = $property->tmproperty_jenisperizinan->where('pendaftaran_id',$permohonan->pendaftaran_id)->get();
        foreach ($value as $list_value) {
            echo $list_value->v_tinjauan;
            echo "<br />";
        }
        $property = $perizinan->trproperty->where('id', 32)->get();
        echo $property->n_property . " ";
        $value = $property->tmproperty_jenisperizinan->where('pendaftaran_id',$permohonan->pendaftaran_id)->get();
        foreach ($value as $list_value) {
            echo $list_value->v_tinjauan;
            echo "<br />";
        }
        echo "<br />";
        echo "Prasana";
        echo "<br />";

        $property = $perizinan->trproperty->where('id', 29)->get();
        $i = NULL;
        foreach ($property as $prop) {
            echo $prop->n_property;
            echo "<br />";
        }

        $sarana_list = $permohonan->tmproperty_prasarana->get();
        $property = $perizinan->trproperty->where('id', 29)->get();
        $trkoefesientarifretribusi = $property->trkoefesientarifretribusi->get();

        foreach ($trkoefesientarifretribusi as $ret) {

            foreach ($sarana_list as $saranas) {
            }

            $id_kof = $ret->id;
            $jenis[] = array(
                'jenis_prop' => $ret->kategori
            );
            $lvl1 = $ret->trkoefisienretribusilev1->get();
            echo $id_kof . " *** " . $sarana->id;

            $rel_1 = new trkoefesientarifretribusi_trkoefisienretribusilev1();
            $rel_1->where('trkoefisienretribusilev1_id', $lvl1->id);
            $rel_1->get();

            $new_rel = new tmprasarana_trkoefisien();
            $new_rel->where('trkoefesientarifretribusi_id', $rel_1->trkoefesientarifretribusi);
//            $new_rel->where('tmproperty_prasarana_id', $sarana->id);
            $new_rel->get();

            echo " &&&" . $new_rel->id;
            $nilai = NULL;
            if($new_rel->tmproperty_prasarana_id) {
                $entry_daftar_prasarana = new tmproperty_prasarana();
                $entry_daftar_prasarana->get_by_id($new_rel->tmproperty_prasarana_id);
                $nilai = $entry_daftar_prasarana->v_tinjauan;
            }
            echo " ---".$nilai."<br />";
            $jenis_prasarana[] = array(
                'jenis_sarana' => $lvl1->kategori,
                'nilai' => $nilai
            );
        }

        
    }

}

// This is the end of testproperty class

