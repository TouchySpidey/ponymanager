<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Ordini', ['orders_manager'], ['qrcode.min']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div>
			<div id="ordersFilters" class="d-flex filter-orders-by-type pick-one-of-these">
				<div class="actionable pickable filter-tab ml-auto" type="dismissed">Dimesse</div>
				<div class="actionable pickable filter-tab" type="takeaway">TakeAway</div>
				<div class="actionable pickable filter-tab" type="delivery">Delivery</div>
				<div class="actionable pickable filter-tab selected" type="all">Mostra tutto</div>
			</div>
		</div>
		<div>
			<div class="d-flex">
				<div class="scroll-on-left" id="scrollTimeFilter">
					<div>
						<?php foreach ($shifts as $shift) { ?>
							<?php
							$ora_apertura = explode(':', $shift['from'])[0];
							$minuto_apertura = explode(':', $shift['from'])[1];
							$ora_chiusura = explode(':', $shift['to'])[0];
							$minuto_chiusura = explode(':', $shift['to'])[1];
							if ($ora_apertura > $ora_chiusura) {
								# night shift over midnight
								$ora_chiusura += 24;
							}
							?>
							<?php while ($ora_apertura < $ora_chiusura || ($ora_apertura == $ora_chiusura && $minuto_apertura <= $minuto_chiusura)) { ?>
								<div class="timetable-row d-flex pickable" data-time="<?= $ora_apertura ?>:<?= $minuto_apertura ?>">
									<div class="order-n delivery n-consegne delivery-only"></div>
									<div class="order-n delivery n-pizze"></div>
									<div class="time"><?= $ora_apertura ?>:<?= $minuto_apertura ?></div>
								</div>
								<?php
								$minuto_apertura += 10;
								if ($minuto_apertura >= 60) {
									$minuto_apertura -= 60;
									$ora_apertura++;
									$ora_apertura = str_pad($ora_apertura, 2, '0');
									$minuto_apertura = str_pad($minuto_apertura, 2, '0');
									if ($ora_apertura >= 24) {
										$ora_apertura -= 24;
										$ora_chiusura -= 24;
										$ora_chiusura = str_pad($ora_chiusura, 2, '0');
									}
								}
								?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<div class="flex-1 v-flex">
					<div id="Gmap" class="flex-1"></div>
				</div>
				<div id="boxOrdini">
					<div class="info-cliente">
						<div class="d-flex">
							<div class="ml-auto mt-auto mb-auto">
								<input type="checkbox" class="js-deliverable" id="selectAllVisibleOrders" />
							</div>
						</div>
					</div>
					<div id="listaOrdini">
						<div class="info-cliente" id="ghostDelivery">
							<div class="d-flex">
								<div class="mt-auto mb-auto panner btn gred">
									<i class="mdi mdi-map-marker"></i>
								</div>
								<div class="mt-auto mb-auto opener btn gblue" onclick="selectOrder(this.getAttribute('id-order'))">
									<i class="mdi mdi-open-in-new"></i>
								</div>
								<div class="info-container">
									<div class="d-flex">
										<div>
											<div class="nome-cliente"></div>
											<div class="indirizzo-cliente"></div>
										</div>
									</div>
								</div><!-- info-container -->
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" onchange="mayDisableMaster()" /></div>
								</div>
							</div>
						</div>
					</div>
					<div id="ordersControl">
						<div class="d-flex">
							<div class="btn-group dropdown-opener">
								<button type="button" class="btn orange dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="mdi mdi-bike-fast"></i> Pony <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<?php foreach ($ponies as $pony) { ?>
										<li id_pony="<?= $pony['id_pony'] ?>" onclick="selectPony(this.getAttribute('id_pony'))"><a href="#"><?= $pony['name'] ?></a></li>
									<?php } ?>
								</ul>
							</div>
							<div class="btn-group dropdown-opener">
								<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="mdi mdi-printer"></i> Stampa <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li onclick="kitchenPrintSelected()"><a href="#">Cucina</a></li>
									<li onclick="ponyPrintSelected()"><a href="#">Pony</a></li>
									<!-- <li><a href="#">Cliente</a></li> -->
								</ul>
							</div>
							<div class="btn gblue ml-auto" onclick="dismissSelected()"><i class="mdi mdi-close"></i> Dimetti</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button onclick="promptNewOrder()" class="fab blue"><i class="mdi mdi-plus"></i></button>
	</div>


	<div id="addOrderModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content order-modal-content v-flex">
			<div class="modal-topbar">
				<div class="d-flex">
					<div onclick="closeModal(this)" class="action-container actionable"><i class="mdi mdi-arrow-left"></i></div>
					<div class="ml-auto action-container actionable" onclick="delete_order()"><i class="mdi mdi-delete"></i></div>
				</div>
			</div>
			<div id="addOrder" class="flex-1 v-flex">
				<div id="orderTabs" class="d-flex tabs-container scroll-x">
					<div class="actionable tab" data-tab="consegna">
						<span class="tab-icon"><i class="mdi mdi-mailbox"></i></span>
						<span>Consegna</span>
					</div>
					<div class="actionable tab" data-tab="cliente">
						<span class="tab-icon"><i class="mdi mdi-account"></i></span>
						<span>Cliente</span>
					</div>
					<div class="actionable tab" data-tab="menu">
						<span class="tab-icon"><i class="mdi mdi-format-list-checkbox"></i></span>
						<span>Ordine</span>
					</div>
					<div class="actionable tab" data-tab="pagamento">
						<span class="tab-icon"><i class="mdi mdi-credit-card-outline"></i></span>
						<span>Pagamento</span>
					</div>
					<div class="actionable tab" data-tab="stampa" style="display: none;">
						<span class="tab-icon"><i class="mdi mdi-printer"></i></span>
						<span>Stampa</span>
					</div>
				</div>
				<div class="d-flex scroll-x flex-1" id="tabSwitcher">
					<div class="flex-1">
						<div>
							<div tab="elimina" order-component>
								<div class="btn red-800" onclick="delete_order()"><i class="mdi mdi-close"></i> Elimina</div>
							</div>
							<div tab="stampa" order-component>
								<div class="btn gblue" onclick="kitchenPrint()">cucina</div>
								<div class="btn gblue" onclick="ponyPrint()">pony</div>
								<div class="btn gblue" onclick="customerPrint()">cliente</div>
							</div>
							<div tab="consegna" order-component>
								<div class="d-flex">
									<div class="flex-1">
										<div>
											<div class="d-flex">
												<div class="flex-1 bros-16-between">
													<div class="general-heading">Tipologia di ordine</div>
													<div class="pick-one-of-these">
														<div id="deliveryOrder" class="actionable md-checkbox-lg pickable">
															<div>Delivery</div>
															<div class="order-pricing">1,50€</div>
														</div>
														<div id="takeawayOrder" class="actionable md-checkbox-lg pickable">
															<div>Takeaway</div>
															<div class="order-pricing">Gratis</div>
														</div>
													</div>
												</div>
												<div class="flex-1 bros-16-between">
													<div class="general-heading">Note del cliente</div>
													<div>
														<textarea id="order-notes" style="height: 144px;" class="md-input w100p" placeholder="Appunti e informazioni aggiuntive"></textarea>
													</div>
												</div>
											</div>
											<div class="delivery-only">
												<div class="general-heading">Seleziona il pony</div>
												<div id="pony" class="list-block-container pick-one-of-these">
													<div class="actionable md-checkbox-lg pickable js-pony" data-id_pony="">Non selezionato</div>
													<?php foreach ($ponies as $pony) { ?>
														<div class="actionable md-checkbox-lg pickable js-pony" data-id_pony="<?= $pony['id_pony'] ?>"><?= $pony['name'] ?></div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div id="timetable" class="ml-auto">
										<div class="general-heading">Orario</div>
										<div id="scrollTimeTable" class="pick-one-of-these">
											<?php foreach ($shifts as $shift) { ?>
												<?php
												$ora_apertura = explode(':', $shift['from'])[0];
												$minuto_apertura = explode(':', $shift['from'])[1];
												$ora_chiusura = explode(':', $shift['to'])[0];
												$minuto_chiusura = explode(':', $shift['to'])[1];
												if ($ora_apertura > $ora_chiusura) {
													# night shift over midnight
													$ora_chiusura += 24;
												}
												?>
												<?php while ($ora_apertura < $ora_chiusura || ($ora_apertura == $ora_chiusura && $minuto_apertura <= $minuto_chiusura)) { ?>
													<div class="timetable-row d-flex pickable" data-time="<?= $ora_apertura ?>:<?= $minuto_apertura ?>">
														<div class="order-n delivery n-consegne delivery-only"></div>
														<div class="order-n delivery n-pizze"></div>
														<div class="order-n takeaway n-pizze"></div>
														<div class="time"><?= $ora_apertura ?>:<?= $minuto_apertura ?></div>
													</div>
													<?php
													$minuto_apertura += 10;
													if ($minuto_apertura >= 60) {
														$minuto_apertura -= 60;
														$ora_apertura++;
														$ora_apertura = str_pad($ora_apertura, 2, '0');
														$minuto_apertura = str_pad($minuto_apertura, 2, '0');
														if ($ora_apertura >= 24) {
															$ora_apertura -= 24;
															$ora_chiusura -= 24;
															$ora_chiusura = str_pad($ora_chiusura, 2, '0');
														}
													}
													?>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<div tab="cliente" order-component>
								<div class="d-flex">
									<div>
										<div class="input-block" style="padding: 16px;">
											<input autocomplete="off" class="md-input" id="finder" type="text" placeholder="Cerca">
										</div>
										<form id="deliveryToForm">
											<input type="hidden" name="id_delivery" />
											<div id="deliveryTo">
												<input type="hidden" name="id_customer" />
												<input type="hidden" name="delivery_date" />
												<div class="input-block">
													<div><label>Nome e cognome</label></div>
													<input class="md-input" type="text" name="name" placeholder="Nome e cognome" />
												</div>
												<div class="input-block">
													<div><label>Telefono</label></div>
													<div><input class="md-input" type="text" name="telephone" placeholder="Telefono"></div>
												</div>
												<div class="delivery-only">
													<div class="input-block">
														<div><label>Città</label></div>
														<div><input class="md-input" type="text" name="city" placeholder="Città"></div>
													</div>
													<div class="input-block">
														<div><label>Indirizzo</label></div>
														<div><input class="md-input" type="text" name="address" placeholder="Indirizzo"></div>
													</div>
													<div class="input-block">
														<div><label>Nome sul campanello</label></div>
														<div><input class="md-input" type="text" name="doorbell" placeholder="Nome sul campanello"></div>
													</div>
												</div>
												<div class="d-flex">
													<div id="overwriteCustomer" class="btn ml-auto orange"><i class="mdi mdi-pencil"></i> Aggiorna</div>
													<div id="saveNewCustomer" class="btn ml-auto green"><i class="mdi mdi-plus"></i> Nuovo</div>
												</div>
											</div>
										</form>
									</div>
									<div id="customersBox" class="mr-auto">
										<div>
											<div class="info-cliente parent-to-be-hovered" onclick="select_customer(false)">
												<div class="d-flex">
													<div class="show-on-parent-hover">
														<div class="open-customer-container d-flex">
															<div class="open-customer mt-auto mb-auto">
																<i class="mdi mdi-chevron-left"></i>
															</div>
														</div>
													</div>
													<div class="info-container ml-auto text-right">
														<div class="nome-cliente">Nuovo Cliente</div>
													</div>
												</div>
											</div>
										</div>
										<div id="resultsFound">
											<div class="info-cliente parent-to-be-hovered" hidden onclick="select_customer(this.getAttribute('data-id'))">
												<div class="d-flex">
													<div class="show-on-parent-hover">
														<div class="open-customer-container d-flex">
															<div class="open-customer mt-auto mb-auto">
																<i class="mdi mdi-chevron-left"></i>
															</div>
														</div>
													</div>
													<div class="info-container ml-auto text-right">
														<div class="nome-cliente"></div>
														<div class="indirizzo-cliente"></div>
														<div class="telefono-cliente"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div tab="menu" order-component>
								<div class="d-flex">
									<div id="categorieContainer">
										<?php foreach ($pizzas_categories as $category) { ?>
											<div class="categoria" data-category="<?= $category ?>"><?= $category ?></div>
										<?php } ?>
									</div>
									<div id="elencoPiatti" class="flex-1">
										<div class="flex-wrap">
											<?php foreach ($pizzas as $pizza) { ?>
												<div class="piatto" data-elenco="<?= $pizza['category'] ?>" data-id_pizza="<?= $pizza['id_pizza'] ?>">
													<div class="orange-200 -colorful"><?= $pizza['name'] ?></div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<div tab="pagamento" order-component>
								<div class="d-flex">
									<div>
										<div id="calcolatrice">
											<div class="d-flex" id="importi">
												<div id="totale"></div>
												<div id="inputImporto">0</div>
												<div id="restoCalcolato"></div>
											</div>
											<div id="tastiera">
												<div class="d-flex">
													<div tasto data-function="7">7</div>
													<div tasto data-function="8">8</div>
													<div tasto data-function="9">9</div>
												</div>
												<div class="d-flex">
													<div tasto data-function="4">4</div>
													<div tasto data-function="5">5</div>
													<div tasto data-function="6">6</div>
												</div>
												<div class="d-flex">
													<div tasto data-function="1">1</div>
													<div tasto data-function="2">2</div>
													<div tasto data-function="3">3</div>
												</div>
												<div class="d-flex">
													<div tasto data-function="<"><i class="mdi mdi-backspace-outline"></i></div>
													<div tasto data-function="0">0</div>
													<div tasto data-function="C">C</div>
												</div>
											</div>
										</div>
									</div>
									<div id="paymentMethods" class="ml-auto mr-auto mt-auto mb-auto list-block-container pick-one-of-these">
										<div class="actionable list-block pickable" data-id_payment="">Non pagato</div>
										<?php foreach ($payment_methods as $payment_method) { ?>
											<div class="actionable list-block pickable" data-id_payment="<?= $payment_method['id_payment'] ?>"><?= $payment_method['description'] ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ml-auto v-flex" id="riepilogoOrdine">
						<div id="totaleOrdine" class="d-flex"><div class="tot-label">Totale: </div><div tot-text class="ml-auto"></div></div>
						<div id="scrollOrdine" class="flex-1">
							<div class="item delivery-only">
								<div class="d-flex">
									<div main>Delivery</div>
									<div price class="ml-auto mt-auto mb-auto">1,50</div>
								</div>
							</div>
							<div id="listaPizze">
								<div class="actionable item" id="ghostOrderItem" onclick="select_pizza(this)">
									<div class="d-flex">
										<div quantity></div>
										<div main></div>
										<div price class="ml-auto mt-auto mb-auto"></div>
									</div>
									<div metapizza>
										<div pizza-omaggio class="hidden">
											<div class="green">Omaggio</div>
										</div>
									</div>
									<div modifiche>
										<div ingrediente id="ghostPizzaIngredient"></div>
										<div aggiunta id="ghostPizzaAddition"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex">
							<button class="ml-auto btn blue" id="sendOrder"><i class="mdi mdi-check"></i> Salva</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="editPizzaComposition" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="d-flex">
						<div id="categorieIngredientiContainer" class="pick-one-of-these">
							<div class="categoria pickable" id="assigned" onclick="show_assigned()">Assegnati</div>
							<div class="categoria pickable" id="pizzanote" onclick="show_notes()">Note</div>
							<?php foreach ($ingredients_categories as $category) { ?>
								<div class="categoria pickable" data-category="<?= $category ?>" onclick="select_ingredients_category('<?= $category ?>')"><?= $category ?></div>
							<?php } ?>
						</div>
						<div id="elencoIngredienti" class="flex-1">
							<div class="flex-wrap">
								<div class="notes-container">
									<textarea id="pizza-notes" class="md-input" placeholder="Note"></textarea>
								</div>
								<?php foreach ($ingredients as $ingredient) { ?>
									<div class="ingrediente" data-elenco="<?= $ingredient['category'] ?>" data-id_ingredient="<?= $ingredient['id_ingredient'] ?>">
										<div class="d-flex light-green-200 -colorful">
											<div selecting-mark class="mr-auto mt-auto mb-auto"><i class="mdi mdi-check-bold"></i></div><div ingredient-full-name><?= $ingredient['name'] ?></div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div id="pizzeContext" order-context class="mt-auto">
						<div class="flex-wrap">
							<div class="context-function gblue" data-function="omaggio">OMAGGIO</div>
							<div class="context-function orange" data-function="meno">—</div>
							<div class="context-function green" data-function="più"><i class="mdi mdi-plus"></i></div>
							<div class="context-function blue" data-function="duplica"><i class="mdi mdi-content-copy"></i></div>
							<div class="context-function red-800 ml-auto" data-function="elimina"><i class="mdi mdi-close"></i></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="ghostCustomerPrint">
	</div>
	<div id="ghostPonyPrint">
		<div class="data"></div>
		<div class="label">Orario</div>
		<div class="value" time=""></div>
		<div class="label">Cliente</div>
		<div class="value" customer=""></div>
		<div class="label">Indirizzo</div>
		<div class="value" address=""></div>
		<div class="label">Campanello</div>
		<div class="value" doorbell=""></div>
		<div class="notes">
			<div class="label">Note</div>
			<div class="value" text-notes=""></div>
		</div>
		<div id="qrcode_printable"></div>
		<div pizze-container=""></div>
	</div>
	<div id="ghostKitchenPrint">
		<div class="data"></div>
		<div class="label">Ordine</div>
		<div order-type="" takeaway="" class="value" style="display: none;">TakeAway</div>
		<div order-type="" delivery="" class="value">Domicilio</div>
		<div class="label">Orario</div>
		<div class="value" time=""></div>
		<div class="label">Cliente</div>
		<div class="value" customer=""></div>
		<div pizze-container="">
			<div id="ghostPizza">
				<div class="stackable-stuff">
					<div class="pizza-heading"><span pizza-quantity=""></span> <span pizza-name=""></span></div>
					<div pizza-ingredient="" id="kitchenIngredient"></div>
					<div pizza-ingredient="" without id="kitchenWithout"><i class="mdi mdi-minus"></i> <span without-name></span></div>
					<div pizza-aggiunta="" id="kitchenAddition"><i class="mdi mdi-plus-thick"></i> <span addition-name></span></div>
				</div>
				<div class="notes">
					<div class="label">Note</div>
					<div class="value" text-notes=""></div>
				</div>
			</div>
		</div>
	</div>
	<div id="printable"></div>
	<script>
	let ponies = JSON.parse(`<?= JSON_encode($ponies) ?>`);
	let ingredients = JSON.parse(`<?= JSON_encode($ingredients) ?>`);
	let ingredients_categories = JSON.parse(`<?= JSON_encode($ingredients_categories) ?>`);
	let pizzas = JSON.parse(`<?= JSON_encode($pizzas) ?>`);
	let pizzas_categories = JSON.parse(`<?= JSON_encode($pizzas_categories) ?>`);
	</script>
	<?= import_js('orders_manager') ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_PUBLIC_API ?>&callback=initMap&libraries=&v=weekly" async></script>
</body>
</html>
