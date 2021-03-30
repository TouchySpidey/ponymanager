<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Ordini', ['orders_manager']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="d-flex">
			<div id="boxOrdini">
				<div class="d-flex" id="switchOrdini">
					<div id="toPrepping" class="btn orange-700">In preparazione</div>
					<div id="toDelivering" class="btn light-blue">In transito</div>
				</div>
				<div id="pendingOrders">
					<div class="info-cliente">
						<div class="info-container">
							<div class="d-flex">
								<div>
									<div class="nome-cliente">GLORIA LUNIAN</div>
									<div class="pizze-ordinate">4 pizze</div>
									<div class="indirizzo-cliente">VIA ISTRIA 32</div>
									<div>1 birra</div>
								</div>
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" /></div>
								</div>
							</div>
						</div><!-- info-container -->
					</div>
					<div class="info-cliente">
						<div class="info-container">
							<div class="d-flex">
								<div>
									<div class="nome-cliente">LEONARDO CESCA</div>
									<div class="pizze-ordinate">8 pizze</div>
									<div class="indirizzo-cliente">VIA LAZZARIS 34</div>
								</div>
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" /></div>
								</div>
							</div>
						</div><!-- info-container -->
					</div>
					<div class="info-cliente">
						<div class="info-container">
							<div class="d-flex">
								<div>
									<div class="nome-cliente">MICOL PAGOTTO</div>
									<div class="pizze-ordinate">3 pizze</div>
									<div class="indirizzo-cliente">VIA GORIZIA 10</div>
								</div>
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" /></div>
								</div>
							</div>
						</div><!-- info-container -->
					</div>
				</div>

				<div id="sentOrders">
					<div class="info-cliente">
						<div class="info-container">
							<div class="d-flex">
								<div>
									<div class="nome-cliente">JAHIR ISLAM</div>
									<div class="pizze-ordinate">8 pizze</div>
									<div class="indirizzo-cliente">VIA EMILIA 11</div>
									<div>1 birra</div>
								</div>
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" /></div>
								</div>
							</div>
						</div><!-- info-container -->
					</div>
					<div class="info-cliente">
						<div class="info-container">
							<div class="d-flex">
								<div>
									<div class="nome-cliente">LAURA ERCOLANO</div>
									<div class="pizze-ordinate">2 pizze</div>
									<div class="indirizzo-cliente">VIA EMILIA 11</div>
								</div>
								<div class="d-flex ml-auto">
									<div class="mt-auto mb-auto"><input type="checkbox" class="js-deliverable" /></div>
								</div>
							</div>
						</div><!-- info-container -->
					</div>
				</div>
				<div class="d-flex">
					<div class="btn indigo-600 ml-auto mr-auto" onclick="window.print()">Stampa!</div>
				</div>
			</div>
		</div>
		<button onclick="openNewOrderModal()" class="fab blue"><i class="mdi mdi-plus"></i></button>
	</div>


	<div id="addCustomerModal" class="w3-modal modal-container">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content">
			<div class="w3-container">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<form autocomplete="off" method="POST" action="<?= site_url() ?>customers/add_customer" id="addCustomer">
					<input autocomplete="off" name="hidden" type="text" style="display:none;">
					<div class="input-block">
						<div><label>Nome Cognome</label></div>
						<div><input class="md-input" type="text" name="name" placeholder="nome e cognome"></div>
					</div>
					<div class="input-block">
						<div><label>Indirizzo</label></div>
						<div><input class="md-input" type="text" name="address" placeholder="indirizzo"></div>
					</div>
					<div class="input-block">
						<div><label>Telefono</label></div>
						<div><input class="md-input" type="text" name="telephone" placeholder="telefono"></div>
					</div>
					<button class="btn blue">Salva</button>
				</form>
			</div>
		</div>
	</div>


	<div id="addOrderModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content order-modal-content">
			<div class="w3-container">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div id="addOrder">
					<div id="orderTabs" class="d-flex tabs-container scroll-x">
						<div class="tab" data-tab="consegna">Consegna</div>
						<div class="tab" data-tab="cliente">Cliente</div>
						<div class="tab" data-tab="menu">Ordini</div>
						<div class="tab" data-tab="pagamento">Pagamento</div>
						<div class="tab" data-tab="stampa">Stampa</div>
					</div>
					<div class="d-flex scroll-x" id="tabSwitcher">
						<div class="flex-1">
							<div>
								<div tab="stampa" order-component>
									<div class="btn gblue" onclick="printOpenOrder()">cucina</div>
									<div class="btn gblue">pony</div>
									<div class="btn gblue">cliente</div>
								</div>
								<div tab="consegna" order-component>
									<div class="d-flex">
										<div class="flex-1">
											<div>
												<div class="general-heading">
													Tipologia di ordine
												</div>
												<div class="pick-one-of-these">
													<div id="deliveryOrder" class="md-checkbox-lg pickable">
														<div>Delivery</div>
														<div class="order-pricing">1,50€</div>
													</div>
													<div id="takeawayOrder" class="md-checkbox-lg pickable">
														<div>Takeaway</div>
														<div class="order-pricing">Gratis</div>
													</div>
												</div>
												<div class="delivery-only">
													<div class="general-heading">Seleziona il pony</div>
													<div id="pony" class="list-block-container pick-one-of-these">
														<div class="list-block pickable" data-id_pony="">Non selezionato</div>
														<?php foreach ($ponies as $pony) { ?>
															<div class="list-block pickable" data-id_pony="<?= $pony['id_pony'] ?>"><?= $pony['name'] ?></div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
										<div id="timetable" class="ml-auto">
											<div class="general-heading">Orario</div>
											<?php
											$ora_apertura = 10;
											$minuto_apertura = 00;
											$ora_chiusura = 15;
											$minuto_chiusura = 00;
											?>
											<div id="scrollTimeTable" class="pick-one-of-these">
												<?php foreach (range($ora_apertura, $ora_chiusura) as $ora) { ?>
													<?php foreach (range(0, 50, 10) as $minuto) { ?>
														<div class="timetable-row d-flex pickable">
															<div class="order-n delivery n-consegne delivery-only"></div>
															<div class="order-n delivery n-pizze"></div>
															<div class="order-n takeaway n-pizze"></div>
															<div class="time"><?= str_pad($ora, 2, '0') ?>:<?= str_pad($minuto, 2, '0') ?></div>
														</div>
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
															<div><label>Nome sul campanello</label></div>
															<div><input class="md-input" type="text" name="doorbell" placeholder="Nome sul campanello"></div>
														</div>
														<div class="input-block">
															<div><label>Città</label></div>
															<div><input class="md-input" type="text" name="city" placeholder="Città"></div>
														</div>
														<div class="input-block">
															<div><label>Indirizzo</label></div>
															<div><input class="md-input" type="text" name="address" placeholder="iIndirizzo"></div>
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
												<div class="info-cliente parent-to-be-hovered" hidden onclick="select_customer(this)">
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
									<div id="categorieContainer" class="flex-wrap">
										<?php foreach ($pizzas_categories as $category) { ?>
											<div class="categoria" data-category="<?= $category ?>"><?= $category ?></div>
										<?php } ?>
									</div>
									<div id="elencoPiatti">
										<div class="flex-wrap">
											<?php foreach ($pizzas as $pizza) { ?>
												<div class="piatto" data-elenco="<?= $pizza['category'] ?>" data-id_pizza="<?= $pizza['id_pizza'] ?>"><?= $pizza['name'] ?></div>
											<?php } ?>
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
											<div class="list-block pickable" data-id_payment="">Non pagato</div>
											<?php foreach ($payment_methods as $payment_method) { ?>
												<div class="list-block pickable" data-id_payment="<?= $payment_method['id_payment'] ?>"><?= $payment_method['description'] ?></div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="d-flex">
								<div class="context-function green-200"><i class="mdi mdi-gift"></i> Sconto</div>
							</div> -->
						</div>
						<div class="ml-auto" id="riepilogoOrdine">
							<div id="totaleOrdine">€30</div>
							<div id="scrollOrdine">
								<div class="item delivery-only">
									<div class="d-flex">
										<div main>Delivery</div>
										<div price class="ml-auto">1,50</div>
									</div>
								</div>
								<div id="listaPizze">
									<div class="item" id="ghostOrderItem" onclick="select_pizza(this)">
										<div class="d-flex">
											<div quantity>1</div>
											<div main>Capricciosa</div>
											<div price class="ml-auto"></div>
										</div>
										<div metapizza>
											<div pizza-omaggio class="hidden">
												<div class="green">Omaggio</div>
											</div>
											<div pizza-bencotta class="hidden">
												<div class="amber">Ben cotta</div>
											</div>
											<div pizza-rossa class="hidden">
												<div class="red">Rossa</div>
											</div>
											<div pizza-bianca class="hidden">
												<div class="white">Bianca</div>
											</div>
										</div>
										<div modifiche>
											<div ingrediente id="ghostPizzaIngredient">prosciutto</div>
											<div aggiunta id="ghostPizzaAddition">Salsiccia</div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex">
								<!-- <button class="btn indigo"><i class="mdi mdi-printer"></i> Stampa</button> -->
								<button class="ml-auto btn blue" id="sendOrder"><i class="mdi mdi-check"></i> Invia</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="editPizzaComposition" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content">
			<div class="w3-container">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div>
					<div id="categorieIngredientiContainer" class="flex-wrap">
						<?php foreach ($ingredients_categories as $category) { ?>
							<div class="categoria" data-category="<?= $category ?>"><?= $category ?></div>
						<?php } ?>
					</div>
					<div id="elencoIngredienti">
						<div class="flex-wrap">
							<?php foreach ($ingredients as $ingredient) { ?>
								<div class="ingrediente" data-elenco="<?= $ingredient['category'] ?>" data-id_ingredient="<?= $ingredient['id_ingredient'] ?>">
									<div class="d-flex">
										<div selecting-mark class="mr-auto mt-auto mb-auto"><i class="mdi mdi-check-bold"></i></div><div ingredient-full-name><?= $ingredient['name'] ?></div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div id="pizzeContext" order-context>
					<div class="flex-wrap">
						<div class="context-function green" data-function="omaggio">OMAGGIO</div>
						<div class="context-function" data-function="bianca">BIANCA</div>
						<div class="context-function red" data-function="rossa">ROSSA</div>
						<div class="context-function amber" data-function="bencotta">Ben Cotta</div>
					</div>
					<div class="flex-wrap">
						<div class="context-function orange" data-function="meno">—</div>
						<div class="context-function blue" data-function="più"><i class="mdi mdi-plus"></i></div>
						<div class="context-function blue" data-function="duplica"><i class="mdi mdi-content-copy"></i></div>
						<div class="context-function red-800 ml-auto" data-function="elimina"><i class="mdi mdi-close"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="ghostCustomerPrint">
	</div>
	<div id="ghostRiderPrint">
	</div>
	<div id="ghostKitchenPrint">
		<div class="label">Ordine</div>
		<div order-type takeaway class="value">TakeAway</div>
		<div order-type delivery class="value">Domicilio</div>
		<div class="label">Orario</div>
		<div class="value" time></div>
		<div class="label">Cliente</div>
		<div class="value" customer></div>
		<div pizze-container>
			<div id="ghostPizza">
				<div pizza-quantity></div>
				<div pizza-name></div>
				<div pizza-bianca>Bianca</div>
				<div pizza-rossa>Rossa</div>
				<div pizza-bencotta>Ben cotta</div>
				<div pizza-ingredient id="kitchenIngredient"></div>
				<div pizza-aggiunta id="kitchenAddition"></div>
			</div>
		</div>
	</div>
	<div id="printable">
		<div>Stampa prova prova ciao</div>
	</div>

	<script>
	let ingredients = JSON.parse(`<?= JSON_encode($ingredients) ?>`);
	let ingredients_categories = JSON.parse(`<?= JSON_encode($ingredients_categories) ?>`);
	let pizzas = JSON.parse(`<?= JSON_encode($pizzas) ?>`);
	let pizzas_categories = JSON.parse(`<?= JSON_encode($pizzas_categories) ?>`);
	</script>
	<?= import_js('orders_manager') ?>
</body>
</html>
