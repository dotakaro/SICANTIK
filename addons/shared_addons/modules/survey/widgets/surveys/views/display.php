<?php
$isOpened = true;
$currentDate = date('Y-m-d');
if(strtotime($currentDate) > strtotime($close_date) || strtotime($currentDate) < strtotime($open_date)){
    $isOpened = false;
}
?>
<h4><?php echo $description; ?></h4>
<!-- If Survey is open and user has not already voted in this poll -->
<?php //if ($active AND $isOpened AND ! $already_voted): ?>
<?php if ($active AND $isOpened): ?>
	<?php echo anchor('survey/participate/'.$slug, lang('survey:participate'), 'class="button"');?>
<?php endif; ?>
<?php //else: ?>
    &nbsp;
    <?php echo anchor('survey/view_results/'.$slug, lang('survey:view_results'), 'class="button"');?>
