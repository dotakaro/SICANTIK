<html>
<head>
    <style type="text/css">
        *{
            font-family:Arial;
        }
        h2{
            text-align:center;
        }
        table{
            border:1px solid black;
            border-collapse: collapse;
            width:100%;
        }
        table tr td,table tr td.head{
            border:1px solid black;
            border-collapse: collapse;
            text-align: center;
        }
        table tr td.head{
            font-weight: bold;
        }
        table thead th{
            background-color:#AAAAAA;
        }
        table tbody tr:nth-child(even){
            background-color:#CCCCCC;
        }

        table.no-border{
            border:none;
            width:100%;
        }
        table.no-border tr td{
            border:none;
            border-collapse: collapse;
            text-align: left;
        }

        .hidden-report{
            display:none;
        }
    </style>
</head>
<body>
<?php if(!empty($list)){?>
    <h2 style="text-align: center;"><?php echo $reportTitle;?></h2>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="tbl_result">
        <tr>
        <?php
        foreach($list[0] as $field=>$value){
            echo '<td class="head">'.$field.'</td>';
        }
        ?>
        </tr>
        <?php
        foreach ($list as $index=>$data){
            echo '<tr>';
            foreach($data as $field=>$value){
                echo '<td>'.$value.'</td>';
            }
            echo '</tr>';
        }
        ?>
    </table>
<?php }?>
</body>