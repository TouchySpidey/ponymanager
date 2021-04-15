<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Ordini', ['orders_manager'], ['qrcode.min']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div>Lista</div>
		<div class="d-flex">
			<table>
				<?php foreach ($ordini as $ordine) { ?>
					<tr class="actionable js-orders-list-item">
						<td>
							<?php if ($ordine['is_delivery']) { ?>
								<i class="mdi mdi-store"></i>
							<?php } else { ?>
								<i class="mdi mdi-map-marker"></i>
							<?php } ?>
						</td>
						<td><?= $ordine['delivery_time'] ?></td>
						<td><?= date('l', strtotime($ordine['delivery_datetime'])) ?></td>
						<td><?= $ordine['name'] ?></td>
						<td><?= $ordine['delivery_time'] ?></td>
						<td><?= $ordine['travel_duration'] ?></td>
						<td>€<?= number_format($ordine['total_price'], 2, ',', '.') ?></td>
					</tr>
					<tr>
						<td colspan="999">
							<div class="collapse">
								<table>
									<?php foreach ($ordine['rows'] as $row) { ?>
										<tr>
											<td><?= $row['n'] ?> &times; <?= $pizzas[$row['id_piatto']]['name'] ?></td>
										</tr>
									<?php } ?>
								</table>
							</div>
						</td>
					</tr>
				<?php } ?>
			</table>
			<div id="chart_weekdays"></div>
		</div>
		<div id="chart_delivery_type"></div>
		<div>Analisi per pizza</div>
		<div>Analisi per ingrediente</div>
		<div>Analisi per zona</div>
		<div>Analisi per cliente</div>
		<div>Analisi per pagamento</div>
		<div></div>
	</div>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script>
	let chart_weekdays_data = <?= json_encode($chart_weekdays) ?>;
	let chart_delivery_type_data = <?= json_encode($chart_delivery_type) ?>;
	console.log(chart_delivery_type_data);
	</script>
	<?= import_js('orders_list') ?>
</body>
</html>
