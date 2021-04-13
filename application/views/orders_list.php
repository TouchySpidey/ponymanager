<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Ordini', ['orders_manager'], ['qrcode.min']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div>Lista ordini</div>
	</div>
	<?= import_js('orders_list') ?>
</body>
</html>
