<html>
<head>
	<title><?= $title ?></title>
	<link rel="manifest" href="<?= site_url() ?>manifest.json">
	<meta name="theme-color" content="#141414"/>
	<link rel="apple-touch-startup-image" href="<?= site_url() ?>frontend/images/icons/icon-128.png">
	<link rel="apple-touch-icon" href="<?= site_url() ?>frontend/images/icons/icon-128.png">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-title" content="Data Quality">
	<link rel="shortcut icon" href="<?= site_url() ?>frontend/images/icons/icon-128.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="<?= site_url() ?>frontend/js/qrcode.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?= site_url() ?>frontend/css/print.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" />
	<script>
	let site_url = '<?= site_url() ?>';
	</script>
</head>
<body>
	<div id="printable"><div>
		<div class="data">29/03/2021</div>
		<div class="label">Orario</div>
		<div class="value" time="">13:00</div>
		<div class="label">Cliente</div>
		<div class="value" customer="">LEONARDO CESCA</div>
		<div class="label">Indirizzo</div>
		<div class="value" address="">Mogliano, Via f barbiero</div>
		<div class="label">Campanello</div>
		<div class="value" doorbell="">Cesca</div>
		<div class="d-flex" id="qrcode_printable"></div>
		<div pizze-container=""><div>
				<div class="pizza-heading"><span pizza-quantity="">1.0</span> <span pizza-name="">Diavola</span></div>



			</div><div>
				<div class="pizza-heading"><span pizza-quantity="">1.0</span> <span pizza-name="">Prosciutto e funghi</span></div>



			<div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name="">Speck</span></div><div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name="">Senza Glutine</span></div></div><div>
				<div class="pizza-heading"><span pizza-quantity="">1.0</span> <span pizza-name="">Quattro formaggi</span></div>



			<div pizza-ingredient="" without="" class="without"><i class="mdi mdi-minus"></i> <span without-name="">Gorgonzola</span></div><div pizza-ingredient="" without="" class="without"><i class="mdi mdi-minus"></i> <span without-name="">Brie</span></div></div></div>
	</div></div>
</body>
</html>
