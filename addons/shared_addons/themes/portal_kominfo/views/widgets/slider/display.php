<?php if(count($images) != 0): ?>
    <div class="slider-wrapper theme-<?php echo $options['theme']; ?>" id="slider-<?php echo $slider->id; ?>-wrapper" >
        <div id="slider-<?php echo $slider->id; ?>" class="nivoSlider" >
        	<?php foreach($images as $image): ?>
				<img src="<?php echo $image->path; ?>" title="<?php echo $image->alt_attribute; ?>" rel="{{ url:site }}<?php echo ($options['controlNavThumbs'] === 'true') ? 'files/thumb/'.$image->id.'/70/50' : null; ?>" <?php echo ($options['captions'] === 'true') ? 'title="'.$image->name.'"' : null; ?> />
            <?php endforeach; ?>
		</div>
    </div>
    

	<script type="text/javascript">
	$(document).ready(function(){
		$('#slider-<?php echo $slider->id; ?>').nivoSlider({
			<?php echo ($options['effect']) ? 'effect: "'.$options['effect'].'",' : null; ?>
			<?php echo ($options['animSpeed']) ? 'animSpeed: "'.$options['animSpeed'].'",' : null; ?>
			<?php echo ($options['pauseTime']) ? 'pauseTime: "'.$options['pauseTime'].'",' : null; ?>
			<?php echo ($options['directionNav']) ? 'directionNav: '.$options['directionNav'].',' : null; ?>
			<?php echo ($options['directionNavHide']) ? 'directionNavHide: '.$options['directionNavHide'].',' : null; ?>
			<?php echo ($options['controlNav']) ? 'controlNav: '.$options['controlNav'].',' : null; ?>
			<?php echo ($options['controlNavThumbs']) ? 'controlNavThumbs: '.$options['controlNavThumbs'].',' : null; ?>
			<?php echo ($options['controlNavThumbs']) ? 'controlNavThumbsFromRel: '.$options['controlNavThumbs'].',' : null; ?>
			<?php echo ($options['keyboardNav']) ? 'keyboardNav: '.$options['keyboardNav'].',' : null; ?>
			<?php echo ($options['pauseOnHover']) ? 'pauseOnHover: '.$options['pauseOnHover'].',' : null; ?>
			<?php echo ($options['manualAdvance']) ? 'manualAdvance: '.$options['manualAdvance'].',' : null; ?>
			<?php echo ($options['slices']) ? 'slices: '.$options['slices'].',' : null; ?>
			<?php echo ($options['boxCols']) ? 'boxCols: '.$options['boxCols'].',' : null; ?>
			<?php echo ($options['boxRows']) ? 'boxRows: '.$options['boxRows'].',' : null; ?>
		});
	});
	</script>

<?php else: ?>
	<div class="slider-wrapper">Slider contains no images.</div>
<?php endif; ?>