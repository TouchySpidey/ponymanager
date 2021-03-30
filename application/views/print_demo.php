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
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?= site_url() ?>frontend/css/print.css?v=<?= VERSION ?>" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" />
	<script>
	let site_url = '<?= site_url() ?>';
	</script>
</head>
<body>
	<div id="printable">
		<div>
			<div class="data">29/03/2021</div>
			<div class="label">Ordine</div>
			<div order-type="" takeaway="" class="value" style="display: none;">TakeAway</div>
			<div order-type="" delivery="" class="value">Domicilio</div>
			<div class="label">Orario</div>
			<div class="value" time="">19:30</div>
			<div class="label">Cliente</div>
			<div class="value" customer="">GLORIA LUNIAN</div>
			<div pizze-container="">
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Vegetariana</span></div>
					<div pizza-ingredient="">Zucchine</div>
					<div pizza-ingredient="">Melanzane</div>
					<div pizza-ingredient="" without><i class="mdi mdi-minus"></i> <span>Peperoni</span></div>
					<div pizza-ingredient="">Cipolla</div>
					<div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name>Champignon</span></div>
				</div>
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Diavola</span></div>
					<div pizza-ingredient="">Salamino piccante</div>
					<div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name>Patatine fritte</span></div>
				</div>
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Heineken 33c</span></div>
				</div>
			</div>
		</div>
		<div>
			<div class="data">29/03/2021</div>
			<div order-type="" takeaway="" class="value" style="display: none;">TakeAway</div>
			<div style="text-align: center" order-type="" delivery="" class="value">Domicilio</div>
			<div class="label">Orario: <span class="value">19:30</span></div>
			<div class="label">Cliente: <span class="value">GLORIA LUNIAN</span></div>
			<div pizze-container="">
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Vegetariana</span></div>
					<div pizza-ingredient="">Zucchine</div>
					<div pizza-ingredient="">Melanzane</div>
					<div pizza-ingredient=""><span style="text-decoration: line-through">Peperoni</span></div>
					<div pizza-ingredient="">Cipolla</div>
					<div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name>Champignon</span></div>
				</div>
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Diavola</span></div>
					<div pizza-ingredient="">Salamino piccante</div>
					<div pizza-aggiunta=""><i class="mdi mdi-plus-thick"></i> <span addition-name>Patatine fritte</span></div>
				</div>
				<div>
					<div class="pizza-heading"><span pizza-quantity="">1</span> <span pizza-name="">Heineken 33c</span></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
