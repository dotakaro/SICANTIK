<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Data Pemohon</legend>
                <?php
                    $attr = array(
                        'class' => 'kasir',
                        'id' => 'kasir'
                    );
                    echo form_open('kasir/save', $attr);
                    echo form_hidden('id', $id);
                    $img_print = array(
                        'src' => 'assets/images/icon/clipboard-2.png',
                        'alt' => 'Print',
                        'title' => 'Print',
                        'border' => '0',
                    );                    
                ?>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <label for="name_paralel">No Pendaftaran</label>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <label for="name_paralel"><?php echo $no_pendaftaran; ?></label>
                    </div>
                    <br style="clear: both">
                    <div id="leftRail">
                        <label for="name_paralel">Nama Pemohon</label>
                    </div>
                    <div id="rightRail">
                        <label for="name_paralel"><?php echo $nama_pendaftar; ?></label>
                    </div>
                    <br style="clear: both">
					<div id="leftRail">
                        <label for="name_paralel"class="bg-grid">Nama Perusahaan</label>
                    </div>
                    <div id="rightRail">
                        <label for="name_paralel"class="bg-grid"><?php echo $namaperusahaan; ?></label>
                    </div>
                    <br style="clear: both">
                    <div id="leftRail">
                        <label for="name_paralel">No Surat</label>
                    </div>
                    <div id="rightRail">
                        <label for="name_paralel"><?php echo $no_surat; ?></label>
                    </div>
                    <br style="clear: both">
                    <div id="leftRail" class="bg-grid">
                        <label for="name_paralel">Jenis Perizinan</label>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <label for="name_paralel"><?php echo $jenis; ?></label>
                    </div>
                    <br style="clear: both">
					<div id="leftRail" class="bg-grid">
                        <label for="name_paralel">Alamat Izin</label>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <label for="name_paralel"><?php echo $a_izin; ?></label>
                    </div>
                    <br style="clear: both">
                    <div id="leftRail">
                        <label for="name_paralel">Biaya Retribusi</label>
                    </div>
                    <div id="rightRail">
                        <label for="name_paralel">
                        <?php
                        if($retribusi)
                        {
                            echo "Rp. " . $this->terbilang->nominal($retribusi);
                            echo form_hidden('retribusi', $retribusi);
                        }
                        else
                        {
                            echo "Rp. 0";
                            echo form_hidden('retribusi', '0');
                        }
                        ?></label>
                    </div>
                    <br style="clear: both">
                    <div id="leftRail">
                        <label for="name_paralel"></label>
                    </div>
                    <div id="rightRail">
                        <?php
                            if(intval($status) === 0) {
                            ?>
                        <div id="money">
                             <!-- tambahan -->

                             <table border="0" align="right">

                             <tr><td>
                              <input type="image" src="<?php echo base_url();?>assets/images/icon/money.png"
                                     alt="Bayar Retribusi"  name="retribusi" onClick="return confirm('Apakah anda yakin saudara <?php echo $nama_pendaftar ?> sudah membayar biaya retribusi?')">
                                  


                             </td>
                             <td align="right">
                            <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('kasir'); ?>">
                                           <?php echo img($img_edit); ?></a>
                            </td>
                             </tr>
                             </table>
                        </div>
                            <?php
                            }  else {
                                ?>
                        <div id="print">
                             <!-- tambahan -->
                             <table border="0" align="right">
                              <tr>
								  <td>
								  <input type="image" src="<?php echo base_url();?>assets/images/icon/money.png"
										 alt="Bayar Ulang"  name="retribusi" onClick="return confirm('Apakah anda yakin saudara <?php echo $nama_pendaftar ?> sudah membayar biaya retribusi?')">
									  


								 </td>
                                  <td>
                            <a class="page-help" href="<?php echo site_url('kasir/cetak'."/".$id) ?>"
                                    >
                            <?php echo img($img_print); ?></a>
                                   </td>
                                   <td>
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('kasir'); ?>">
                                           <?php echo img($img_edit); ?></a>
                                   </td>
                              </tr>
                              </table>



                        </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <?php
                    echo form_close();
                ?>
            </fieldset>
        </div>
    </div>
    <br style="clear: both;" />
</div>
