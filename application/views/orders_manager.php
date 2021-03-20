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
			<div class="w3-container" style="padding: 8px;">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<form autocomplete="off" method="POST" action="<?= site_url() ?>orders/add_order" id="addOrder">
					<input autocomplete="off" name="id_customer" type="hidden" style="display:none;">
					<div id="orderTabs" class="d-flex tabs-container">
						<div class="tab" data-tab="menu">Pizze</div>
						<div class="tab" data-tab="consegna">Consegna</div>
					</div>
					<div class="d-flex">
						<div class="v-flex flex-1">
							<div>
								<div id="menuComponent" order-component>
									<div class="d-flex">
										<div>
											<div class="input-block">
												<div><label>Nome sul campanello</label></div>
												<div><input class="md-input" id="finder" type="text" name="name" placeholder="nome e cognome"></div>
											</div>
											<div class="input-block">
												<div><label>Indirizzo</label></div>
												<div><input class="md-input" type="text" name="address" placeholder="indirizzo"></div>
											</div>
											<div class="input-block">
												<div><label>Telefono</label></div>
												<div><input class="md-input" type="text" name="telephone" placeholder="telefono"></div>
											</div>
										</div>
										<div id="customersBox" class="mr-auto ml-auto">
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
								<div id="pizzeComponent" order-component>
									<div id="categorieContainer" class="flex-wrap">
										<?php foreach ($pizzas_categories as $category) { ?>
											<div class="categoria" data-category="<?= $category ?>"><?= $category ?></div>
										<?php } ?>
									</div>
									<div id="elencoPiatti">
										<div class="flex-wrap">
											<?php foreach ($pizzas as $pizza) { ?>
												<div class="piatto" data-elenco="<?= $pizza['category'] ?>"><?= $pizza['name'] ?></div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<div id="contexts">
								<div id="pizzeContext" order-context>
									<div class="flex-wrap">
										<div class="context-function green" data-function="omaggio">OMAGGIO</div>
										<div class="context-function" data-function="bianca">BIANCA</div>
										<div class="context-function red" data-function="rossa">ROSSA</div>
										<div class="context-function orange" data-function="meno">—</div>
										<div class="context-function blue" data-function="più"><i class="mdi mdi-plus"></i></div>
									</div>
								</div>
							</div>
							<div class="d-flex">
								<div class="context-function green-200"><i class="mdi mdi-gift"></i> Sconto</div>
								<div class="ml-auto mr-auto context-function"><i class="mdi mdi-close"></i></div>
							</div>
						</div>
						<div class="ml-auto" id="riepilogoOrdine">
							<div id="totaleOrdine">€30</div>
							<div id="listaPizze">
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Capricciosa</div>
									</div>
									<div ingrediente>prosciutto</div>
									<div ingrediente>funghi</div>
									<div ingrediente>carciofini</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
									<div tag>Bianca</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Bomba</div>
									</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Capricciosa</div>
									</div>
									<div ingrediente>prosciutto</div>
									<div ingrediente>funghi</div>
									<div ingrediente>carciofini</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
									<div tag>Bianca</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Bomba</div>
									</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Capricciosa</div>
									</div>
									<div ingrediente>prosciutto</div>
									<div ingrediente>funghi</div>
									<div ingrediente>carciofini</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
									<div tag>Bianca</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Bomba</div>
									</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Capricciosa</div>
									</div>
									<div ingrediente>prosciutto</div>
									<div ingrediente>funghi</div>
									<div ingrediente>carciofini</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
									<div tag>Bianca</div>
								</div>
								<div class="item">
									<div class="d-flex">
										<div quantity>1</div>
										<div main>Bomba</div>
									</div>
									<div aggiunta>Salsiccia</div>
									<div impasto>Kamut</div>
									<div dimensione>Maxi</div>
								</div>
							</div>
							<div class="d-flex">
								<!-- <button class="btn indigo"><i class="mdi mdi-printer"></i> Stampa</button> -->
								<button class="ml-auto btn blue"><i class="mdi mdi-check"></i> Invia</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="printable">
		<div>Ciao Jahir</div>
		<div>Prova a stampare</div>
		<div>e dimmi se funziona...</div>
		<div>Se funziona mandami una foto magari</div>
	</div>
	<?= import_js('orders_manager') ?>
</body>
</html>
