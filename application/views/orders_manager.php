<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Finder', ['finder_page']) ?>
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
			<div id="customersBox" class="ml-auto">
				<div>
					<div><input class="md-input" type="text" id="finder" placeholder="Cerca cliente" /></div>
					<div id="resultsFound" class="ml-auto">
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
		<button onclick="openNewCustomerModal()" class="fab-extended blue"><i class="mdi mdi-plus"></i> AGGIUNGI CLIENTE</button>
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
									<div class="input-block">
										<div><label>Nome sul campanello</label></div>
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
								</div>
								<div id="pizzeComponent" order-component>
									<div id="categorieContainer" class="flex-wrap">
										<div class="categoria" data-category="basic">Pizze basic</div>
										<div class="categoria" data-category="speciali">Pizze speciali</div>
										<div class="categoria" data-category="calzoni">Calzoni</div>
										<div class="categoria" data-category="altro">Altro</div>
									</div>
									<div id="elencoPiatti">
										<div class="elenco-piatti" data-elenco="basic">
											<div class="flex-wrap">
												<div class="piatto">Margherita</div>
												<div class="piatto">Diavola</div>
												<div class="piatto">Capricciosa</div>
												<div class="piatto">Prosciutto e funghi</div>
												<div class="piatto">Bresaola rucola e grana</div>
												<div class="piatto">Patatosa</div>
												<div class="piatto">Viennese</div>
											</div>
										</div>
										<div class="elenco-piatti" data-elenco="speciali">
											<div class="flex-wrap">
												<div class="piatto">Bomba</div>
												<div class="piatto">Berlino</div>
												<div class="piatto">Casalinga</div>
												<div class="piatto">Campestre</div>
												<div class="piatto">Diavolo Acquasanta</div>
												<div class="piatto">Estiva</div>
												<div class="piatto">Rustica</div>
											</div>
										</div>
										<div class="elenco-piatti" data-elenco="calzoni">
											<div class="flex-wrap">
												<div class="piatto">Classico</div>
												<div class="piatto">Positano</div>
											</div>
										</div>
										<div class="elenco-piatti" data-elenco="altro">
											<div class="flex-wrap">
												<div class="piatto">Pizza nutella</div>
												<div class="piatto">Baklava</div>
											</div>
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
								<button class="btn indigo"><i class="mdi mdi-printer"></i> Stampa</button>
								<button class="ml-auto btn blue"><i class="mdi mdi-check"></i> Salva</button>
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
	<script>
	$('#finder').val('paolo');
	$('#finder').trigger('input');
</script>
</body>
</html>
