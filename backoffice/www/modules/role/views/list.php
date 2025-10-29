
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="role">
                <thead>
                    <tr>
                        <th>ID Peran</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->id_role; ?></td>
                        <td><?php echo $data->description; ?></td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID Peran</th>
                        <th>Deskripsi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
