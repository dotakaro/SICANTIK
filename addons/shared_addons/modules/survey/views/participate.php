<style>
    ol.survey > li {
        list-style: inside decimal !important;
        margin-bottom:10px;
    }
    ol.survey {
        list-style: inside decimal !important;
    }
    ul.survey > li{
        list-style: inside none !important;
        margin-left:20px !important;
        margin-top:5px;
    }
</style>

<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2><?php echo $survey['description'];?></h2>
    </div>

    <?php echo form_open(site_url('survey/save_result'), 'class="crud"'); ?>
    <div class="block-content">
        <ol class="survey">
            <li>
                Nama
            <?php
                echo '<ul class="survey">';
                echo '<li>'.form_input('participant[nama]',null,'required').'</li>';
                echo '</ul>';
            ?>
            </li>
            <li>
                Umur
            <?php
            echo '<ul class="survey">';
            echo '<li>'.form_input('participant[umur]',null,'required').'</li>';
            echo '</ul>';
            ?>
            </li>
            <li>
                Jenis Kelamin
                <?php
                echo '<ul class="survey">';
                $optGender = array(
                    'laki-laki'=>'Laki-Laki',
                    'perempuan'=>'Perempuan',
                );
                foreach($optGender as $keyOption=>$option){
                    echo '<li>'.form_radio('participant[jenis_kelamin]', $keyOption, false, 'required').$option.'</li>';
                }
                echo '</ul>';
                ?>
            </li>
            <li>
                Pendidikan Terakhir
                <?php
                echo '<ul class="survey">';
                $optEducation = array(
                    'SD ke bawah'=>'SD ke bawah',
                    'SLTP'=>'SLTP',
                    'SLTA'=>'SLTA',
                    'S-1'=>'S-1',
                    'S-2 ke atas'=>'S-2 ke atas',
                );
                foreach($optEducation as $keyOption=>$option){
                    echo '<li>'.form_radio('participant[pendidikan_terakhir]', $keyOption, false, 'required').$option.'</li>';
                }
                echo '</ul>';
                ?>
            </li>
            <li>
                Pekerjaan Utama
                <?php
                echo '<ul class="survey">';
                $optJob = array(
                    'PNS/TNI/Polri'=>'PNS/TNI/Polri',
                    'Pegawai Swasta'=>'Pegawai Swasta',
                    'Wiraswasta'=>'Wiraswasta',
                    'Pelajar/Mahasiswa'=>'Pelajar/Mahasiswa',
                    'Lainnya'=>'Lainnya',
                );
                foreach($optJob as $keyOption=>$option){
                    echo '<li>'.form_radio('participant[pekerjaan_utama]', $keyOption, false, 'required').$option.'</li>';
                }
                echo '</ul>';
                ?>
            </li>
        </ol>
    </div>

    <div class="block-content">
        <?php echo form_hidden('survey[id]', $survey['id']);?>
<!--        --><?php //echo "<pre>";print_r($survey);exit();?>
        <?php if(isset($survey['questions']) && !empty($survey['questions'])):?>
        <ol class="survey">
            <?php foreach($survey['questions'] as $key=>$question):?>
                <li>
                    <?php echo $question['question_desc'];?>
                    <?php switch($question['question_type']){
                        case 'option':
                            if(!empty($question['options'])){
                                echo '<ul class="survey">';
                                foreach($question['options'] as $keyOption=>$option){
                                    echo '<li>'.form_radio('answer['.$key.'][answer]', $option['option_desc'], false, 'required').$option['option_desc'].'</li>';
                                }
                                echo '</ul>';
                                echo form_hidden('answer['.$key.'][survey_question_id]', $question['id']);
                            }
                            break;
                        default:
                            echo '<ul class="survey">';
                            echo '<li>'.form_input('answer['.$key.'][answer]',null,'required').'</li>';
                            echo '</ul>';
                            echo form_hidden('answer['.$key.'][survey_question_id]', $question['id']);
                            break;
                    }?>
                </li>
            <?php endforeach;?>
        </ol>
        <?php endif;?>
<!--        --><?php //if(!$already_voted){?>
            <?php echo form_submit('btn_submit', 'Submit', 'class="button"');?>
<!--        --><?php //}else{?>
            &nbsp;
            <?php echo anchor('survey/view_results/'.$survey['slug'], lang('survey:view_results'), 'class="button"');?>
<!--        --><?php //}?>
    </div>
    <?php echo form_close(); ?>
</div>