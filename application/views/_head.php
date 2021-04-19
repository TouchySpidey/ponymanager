<head>
	<title><?= $title ?></title>
	<link rel="manifest" href="<?= site_url() ?>manifest.json">
	<meta name="theme-color" content="#141414"/>
	<link rel="apple-touch-startup-image" href="<?= site_url() ?>frontend/images/icons/128.png">
	<link rel="apple-touch-icon" href="<?= site_url() ?>frontend/images/icons/128.png">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-title" content="Data Quality">
	<link rel="shortcut icon" href="<?= site_url() ?>frontend/images/icons/128.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" media="print" href="<?= site_url() ?>frontend/css/print.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/bootstrap.css" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/w3.css" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/normalize.css" />
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/material-colors.css" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/main.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/global_mobile.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/global_desktop.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" />
	<script src="<?= site_url() ?>frontend/js/main.js?v=<?= VERSION ?>"></script>
	<?php foreach ($extra_css as $css) { ?>
		<link rel="stylesheet" media="screen" href="<?= site_url() ?>frontend/css/<?= $css ?>.css?v=<?= VERSION ?>" />
	<?php } ?>
	<?php foreach ($extra_js as $js) { ?>
		<script src="<?= site_url() ?>frontend/js/<?= $js ?>.js?v=<?= VERSION ?>"></script>
	<?php } ?>
	<script>
	let site_url = '<?= site_url() ?>'; // has trailing slash
	<?php if (defined('_COMPANY_URI')) { ?>let company_url_suffix = '/company/<?= _COMPANY_URI ?>';<?php } ?>
	</script>
</head>
