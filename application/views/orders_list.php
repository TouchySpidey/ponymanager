<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Analitica', ['orders_list']) ?>
<body style="background-color: #e8eaed;">
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="d-flex">
			<div id="spy" class="v-flex">
				<heightfix>
					<div class="scroller visible" tab="#tab_storico_ordini">Storico ordini</div>
					<div class="scroller" tab="#tab_analisi_per_servizio">Analisi per servizio</div>
					<div class="scroller" tab="#tab_metodi_di_pagamento">Metodi di pagamento</div>
					<div class="scroller" tab="#tab_analisi_ingredienti">Analisi ingredienti</div>
					<div class="scroller" tab="#tab_analisi_stagionalità">Analisi stagionalità</div>
					<div class="scroller" tab="">Analisi Geografica</div>
				</heightfix>
			</div>
			<div class="tabs-container flex-1 card" style="margin: 24px;">
				<div class="tab" id="tab_storico_ordini">
					<div class="general-heading">Storico ordini</div>
					<div class="v-flex ml-auto mr-auto">
						<table class="bordered-table">
							<?php foreach ($ordini_per_data as $data => $_ordini) { ?>
								<tr class="grey-300">
									<td class="date-rowcell" colspan="99"><?= date('l d/m/Y', strtotime($data)) ?></td>
									<td class="total-rowcell"><?= number_format($_ordini['total'], 2, ',', '.') ?></td>
								</tr>
								<?php foreach ($_ordini['list'] as $ordine) { ?>
									<tr class="actionable js-orders-list-item">
										<td>
											<?php if ($ordine['is_delivery']) { ?>
												<i class="mdi mdi-store"></i>
											<?php } else { ?>
												<i class="mdi mdi-map-marker"></i>
											<?php } ?>
										</td>
										<td><?= $ordine['delivery_time'] ?></td>
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
							<?php } ?>
						</table>
					</div>
				</div>
				<div class="tab" id="tab_analisi_per_servizio">
					<div class="general-heading">Analisi per servizio</div>
					<div id="chart_delivery_type"></div>
				</div>
				<div class="tab" id="tab_metodi_di_pagamento">
					<div class="general-heading">Metodi di pagamento</div>
					<div id="chart_payment_type"></div>
				</div>
				<div class="tab" id="tab_analisi_ingredienti">
					<div class="general-heading">Analisi Ingredienti</div>
					<!-- <div id="chart_delivery_type"></div> -->
				</div>
				<div class="tab" id="tab_analisi_stagionalità">
					<div class="general-heading">Analisi Stagionalità</div>
					<div id="chart_weekdays"></div>
					<div id="chart_months"></div>
				</div>
			</div>
		</div>
		<div></div>
	</div>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script>
	let chart_weekdays_data = <?= json_encode($chart_weekdays) ?>;
	let chart_months_data = <?= json_encode($chart_months) ?>;
	let chart_delivery_type_data = <?= json_encode($chart_delivery_type) ?>;
	let chart_payment_type_data = <?= json_encode($chart_payment_type) ?>;
	</script>
	<?= import_js('orders_list') ?>
</body>
</html>
