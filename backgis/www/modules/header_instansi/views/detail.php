<style>
    .label-wrc2{
        margin-left: 20px;
        width: 200px;
        font-weight: bold;
    }
</style>
<div id="content" style="width: 800px;">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <script type="text/javascript" charset="utf-8">
            function popup_link(site, targetDiv){
                $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
            }               
            $(document).ready(function() {
                $('#form').validate();
                $("#tabs").tabs();
            });
             
        </script>
        <div class="entry">
            <div id="tabs">
                
                <center>
                    <img src="<?php echo base_url().'uploads/logo/'.$logo->value; ?>" width="100" height="100"></img>
                </center>
            </div>
        </div>
    </div>
    <br style="clear: both;" />
</div>
