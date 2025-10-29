<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en"> 		   <![endif]-->

<head>
	<meta charset="utf-8">

	<!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $template['title'].' - '.lang('cp:admin_title') ?></title>

	<base href="<?php echo base_url(); ?>" />

	<!-- Mobile viewport optimized -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- CSS. No need to specify the media attribute unless specifically targeting a media type, leaving blank implies media=all -->
	<?php echo Asset::css('plugins.css'); ?>
	<?php echo Asset::css('workless/workless.css'); ?>
	<?php echo Asset::css('workless/application.css'); ?>
	<?php echo Asset::css('workless/responsive.css'); ?>
        <?php
        $vars = $this->load->_ci_cached_vars;
        if ($vars['lang']['direction']=='rtl'){
            echo Asset::css('workless/rtl/rtl.css');
        }
        ?>
	<!-- End CSS-->

	<!-- Load up some favicons -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-114x114-precomposed.png">
    <style type="text/css">
        .pagination li{
            list-style:none;
            display:inline;
            margin-right:5px;
            margin-left:5px;
        }
        .pagination .page-numbers:hover, .pagination .page-numbers.current {
            background: none repeat scroll 0 0 #929292;
            color: #FFFFFF;
        }
        .pagination .page-numbers {
            background: none repeat scroll 0 0 #D4D3D3;
            border-radius: 2px;
            color: #505050;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            line-height: 150%;
            padding: 2px 7px;
        }
        a {
            color: #000000;
            text-decoration: none;
            transition: all 0.2s ease 0s;
        }
        a {
            vertical-align: baseline;
        }
    </style>
	<!-- metadata needs to load before some stuff -->
	<?php file_partial('metadata'); ?>

</head>

<body>

	<div id="container">

		<section id="content">
			
			<header class="hide-on-ckeditor-maximize">
			<?php file_partial('header'); ?>
			</header>

			<div id="content-body">
				<?php file_partial('notices'); ?>
				<?php echo $template['body']; ?>
			</div>

		</section>

	</div>

	<footer class="clearfix">
		<div class="wrapper">
			<p class="credits">Copyright &copy; <?php echo date('Y'); ?> Batra &nbsp; <span>Rendered in {elapsed_time} sec. using {memory_usage}.</span></p>

			<ul id="lang">
				<form action="<?php echo current_url(); ?>" id="change_language" method="get">
					<select class="chzn" name="lang" onchange="this.form.submit();">
						<?php foreach(config_item('supported_languages') as $key => $lang): ?>
							<option value="<?php echo $key; ?>" <?php echo CURRENT_LANGUAGE == $key ? ' selected="selected" ' : ''; ?>>
								 <?php echo $lang['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</form>
			</ul>
		</div>
	</footer>

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

</body>
</html>