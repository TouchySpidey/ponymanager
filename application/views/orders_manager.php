<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Ordini', ['orders_manager', 'pizze_ingredienti'], ['qrcode.min']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="d-flex">
			<div class="ml-auto">
				<div id="ordersFilters" class="mdc-tab-bar" role="tablist">
					<div class="mdc-tab-scroller">
						<div class="mdc-tab-scroller__scroll-area">
							<div class="mdc-tab-scroller__scroll-content">
								<button class="filter-tab mdc-tab" role="tab" aria-selected="true" tabindex="0" type="dismissed">
									<span class="mdc-tab__content">
										<span class="mdc-tab__icon material-icons" aria-hidden="true">cancel</span>
										<span class="mdc-tab__text-label">Dimesse</span>
									</span>
									<span class="mdc-tab-indicator">
										<span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
									</span>
									<span class="mdc-tab__ripple"></span>
								</button>
								<button class="filter-tab mdc-tab" role="tab" aria-selected="false" tabindex="-1" type="takeaway">
									<span class="mdc-tab__content">
										<span class="mdc-tab__icon material-icons" aria-hidden="true">store</span>
										<span class="mdc-tab__text-label">TakeAway</span>
									</span>
									<span class="mdc-tab-indicator">
										<span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
									</span>
									<span class="mdc-tab__ripple"></span>
								</button>
								<button class="filter-tab mdc-tab" role="tab" aria-selected="false" tabindex="-1" type="delivery">
									<span class="mdc-tab__content">
										<span class="mdc-tab__icon material-icons" aria-hidden="true">moped</span>
										<span class="mdc-tab__text-label">Delivery</span>
									</span>
									<span class="mdc-tab-indicator">
										<span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
									</span>
									<span class="mdc-tab__ripple"></span>
								</button>
								<button class="filter-tab mdc-tab mdc-tab--active" role="tab" aria-selected="true" tabindex="-1" type="all">
									<span class="mdc-tab__content">
										<span class="mdc-tab__icon material-icons" aria-hidden="true">apps</span>
										<span class="mdc-tab__text-label">Mostra tutto</span>
									</span>
									<span class="mdc-tab-indicator mdc-tab-indicator--active">
										<span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
									</span>
									<span class="mdc-tab__ripple"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
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
									<div class="order-n delivery n-consegne"></div>
									<div class="order-n delivery n-pizze"></div>
									<div class="time"><?= $ora_apertura ?>:<?= $minuto_apertura ?></div>
								</div>
								<?php
								$minuto_apertura += 5;
								if ($minuto_apertura >= 60) {
									$minuto_apertura -= 60;
									$ora_apertura++;
									if ($ora_apertura >= 24) {
										$ora_apertura -= 24;
										$ora_chiusura -= 24;
										$ora_chiusura = str_pad($ora_chiusura, 2, '0', STR_PAD_LEFT);
									}
								}
								$ora_apertura = str_pad($ora_apertura, 2, '0', STR_PAD_LEFT);
								$minuto_apertura = str_pad($minuto_apertura, 2, '0', STR_PAD_LEFT);
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

							<!-- <div class="input-block" style="padding: 16px;">
								<input autocomplete="off" class="md-input" id="gfinder" type="text" placeholder="CercaGoogle">
							</div> -->
				</div>
			</div>
		</div>
		<button onclick="promptNewOrder()" class="fab blue mdc-fab">
			<span class="mdc-fab__ripple"></span>
			<span class="mdc-fab__icon material-icons">add</span>
		</button>
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
								<!-- <div class="btn gblue" onclick="customerPrint()">cliente</div> -->
							</div>
							<div tab="consegna" order-component>
								<div class="d-flex">
									<div class="flex-1">
										<div>
											<div class="d-flex">
												<div class="flex-1 bros-16-between">
													<div class="general-heading">Tipo di servizio</div>
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
														<div class="order-n delivery n-consegne"></div>
														<div class="order-n delivery n-pizze"></div>
														<div class="order-n takeaway n-pizze"></div>
														<div class="time"><?= $ora_apertura ?>:<?= $minuto_apertura ?></div>
													</div>
													<?php
													$minuto_apertura += 5;
													if ($minuto_apertura >= 60) {
														$minuto_apertura -= 60;
														$ora_apertura++;
														if ($ora_apertura >= 24) {
															$ora_apertura -= 24;
															$ora_chiusura -= 24;
															$ora_chiusura = str_pad($ora_chiusura, 2, '0', STR_PAD_LEFT);
														}
													}
													$ora_apertura = str_pad($ora_apertura, 2, '0', STR_PAD_LEFT);
													$minuto_apertura = str_pad($minuto_apertura, 2, '0', STR_PAD_LEFT);
													?>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<div tab="cliente" order-component>
								<div class="delivery-only">
									<div class="d-flex">
										<div class="flex-1">
											<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon mt-6">
												<i aria-hidden="true" class="material-icons mdc-text-field__icon">search</i>
												<input id="finder" class="mdc-text-field__input" autocorrect="off" autocomplete="off" spellcheck="false" maxlength="524288">
												<div class="mdc-notched-outline mdc-notched-outline--upgraded">
													<div class="mdc-notched-outline__leading"></div>
													<div class="mdc-notched-outline__notch" style="">
														<label for="demo-mdc-text-field" class="mdc-floating-label" style="">Cerca cliente</label>
													</div>
													<div class="mdc-notched-outline__trailing"></div>
												</div>
											</div>
											<div>
												<div class="mdc-data-table" id="clientiTrovatiDT">
													<div class="mdc-data-table__table-container">
														<table class="mdc-data-table__table" aria-label="Dessert calories">
															<thead>
																<tr class="mdc-data-table__header-row">
																	<th class="options_cell mdc-data-table__header-cell" role="columnheader" scope="col"></th>
																	<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Cliente</th>
																	<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Indirizzo</th>
																	<th class="options_cell mdc-data-table__header-cell" role="columnheader" scope="col"></th>
																</tr>
															</thead>
															<tbody class="mdc-data-table__content" id="customersBody">
																<tr class="mdc-data-table__row customer-row" id="ghostTableRow">
																	<td class="mdc-data-table__cell" col_name="option" scope="row">
																		<div class="material-icons mdc-icon-button edit_customer">edit</div>
																	</td>
																	<td class="mdc-data-table__cell" col_name="date">
																		<div>
																			<div class="nome-cliente">Nome</div>
																			<div class="telefono-cliente">Telefono</div>
																		</div>
																	</td>
																	<td class="mdc-data-table__cell" col_name="title">
																		<div>
																			<div class="indirizzo-cliente">Indirizzo</div>
																			<div class="campanello-cliente">Campanello</div>
																		</div>
																	</td>
																	<td class="mdc-data-table__cell text-right" col_name="option" scope="row">
																		<div class="material-icons mdc-icon-button select_customer">chevron_right</div>
																	</td>
																</tr>
																<tr class="mdc-data-table__row" id="newCustomerRow">
																	<td colspan="4" class="mdc-data-table__cell new_customer text-center" col_name="option" scope="row">
																		<button class="js-rippable mdc-button" __open_dialog="#newCustomerDialog">
																			<div class="mdc-button__ripple"></div>
																			<i class="material-icons mdc-button__icon" aria-hidden="true">person_add</i>
																			<span class="mdc-button__label">Nuovo Cliente</span>
																		</button>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>

													<div class="mdc-data-table__progress-indicator">
														<div class="mdc-data-table__scrim"></div>
														<div class="mdc-linear-progress mdc-linear-progress--indeterminate mdc-data-table__linear-progress" role="progressbar" aria-label="Data is being loaded...">
															<div class="mdc-linear-progress__buffer">
																<div class="mdc-linear-progress__buffer-bar"></div>
																<div class="mdc-linear-progress__buffer-dots"></div>
															</div>
															<div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar">
																<span class="mdc-linear-progress__bar-inner"></span>
															</div>
															<div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar">
																<span class="mdc-linear-progress__bar-inner"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div style="margin-right: 16px;">
											<div class="mdc-card mdc-card--outlined" id="deliveryTo">
												<div class="mdc-card-wrapper__text-section">
													<div class="text-center card__title">Cliente selezionato</div>
													<div class="nome-cliente card__subhead">Nome</div>
													<div class="telefono-cliente text-right card__subhead">Telefono</div>
												</div>
												<div class="mdc-card-wrapper__text-section">
													<div class="indirizzo-cliente card__subhead">Indirizzo</div>
													<div class="campanello-cliente text-right card__subhead">Campanello</div>
												</div>
												<div class="mdc-card__actions">
													<div class="mdc-card__action-buttons">
														<button onclick="openDeliveryInfoDialog()" class="js-rippable mdc-button mdc-card__action mdc-card__action--button">
															<div class="mdc-button__ripple"></div>
															<i class="material-icons mdc-button__icon" aria-hidden="true">edit</i>
															<span class="mdc-button__label">Modifica</span>
														</button>
													</div>
													<div class="mdc-card__action-icons">
														<button onclick="select_customer(false)" class="material-icons mdc-icon-button mdc-card__action mdc-card__action--icon" title="More options">close</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="takeaway-only">
									<div class="d-flex" style="margin-top: 24px;">
										<div style="padding: 12px">
											<label class="mdc-text-field mdc-text-field--outlined" id="takeawayInfoName">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<span class="mdc-floating-label">Nome</span>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input type="text" class="mdc-text-field__input" />
											</label>
										</div>
										<div style="padding: 12px">
											<label class="mdc-text-field mdc-text-field--outlined" id="takeawayInfoPhone">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<span class="mdc-floating-label">Telefono</span>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input type="text" class="mdc-text-field__input" />
											</label>
										</div>
									</div>
								</div>
							</div>
							<div tab="menu" order-component>
								<div class="d-flex">
									<div id="categorieContainer">
										<div class="categoria finder-container">
											<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon mt-6">
												<i aria-hidden="true" class="material-icons mdc-text-field__icon">search</i>
												<input id="pizzaFinder" class="mdc-text-field__input" autocorrect="off" autocomplete="off" spellcheck="false" maxlength="524288">
												<div class="mdc-notched-outline mdc-notched-outline--upgraded">
													<div class="mdc-notched-outline__leading"></div>
													<div class="mdc-notched-outline__notch" style="">
														<label for="demo-mdc-text-field" class="mdc-floating-label" style="">Cerca</label>
													</div>
													<div class="mdc-notched-outline__trailing"></div>
												</div>
											</div>
										</div>
										<?php foreach ($pizzas_categories as $category) { ?>
											<div class="categoria" data-category="<?= $category ?>" onclick="select_category('<?= addslashes($category) ?>')"><?= $category ?></div>
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
										<div aggiunta id="ghostPizzaAddition">
											<div class="d-flex">
												<div testo-ingrediente></div>
												<!-- <div class="ml-auto" prezzo-aggiunto></div> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex">
							<button class="ml-auto mdc-button mdc-button--raised blue" id="sendOrder">
								<i class="material-icons">done</i>
								<span class="mdc-button__label">Salva</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="mdc-dialog" id="newCustomerDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog" style="width: 668px;">
				<div class="mdc-dialog__content">
					<div>
						<div class="flex-wrap">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="newCustomerNameLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Nome</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="newCustomerName" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="newCustomerTelephoneLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Telefono</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="newCustomerTelephone" />
								</label>
							</div>
						</div>
						<div class="flex-wrap" style="margin-top: 16px;">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="newCustomerAddressLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Indirizzo</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input google_address_finder" placeholder="" id="newCustomerAddress" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="newCustomerDoorbellLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Campanello</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="newCustomerDoorbell" />
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveCustomer">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div class="mdc-dialog" id="editCustomerDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog" style="width: 668px;">
				<div class="mdc-dialog__content">
					<div>
						<div class="flex-wrap">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="editCustomerNameLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Nome</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="editCustomerName" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="editCustomerTelephoneLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Telefono</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="editCustomerTelephone" />
								</label>
							</div>
						</div>
						<div class="flex-wrap" style="margin-top: 16px;">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="editCustomerAddressLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Indirizzo</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input google_address_finder" placeholder="" id="editCustomerAddress" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="editCustomerDoorbellLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Campanello</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="editCustomerDoorbell" />
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveCustomer">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div class="mdc-dialog" id="editDeliveryInfoDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog" style="width: 668px;">
				<div class="mdc-dialog__content">
					<div id="deliveryInfo">
						<div class="flex-wrap">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="deliveryInfoNameLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Nome</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="deliveryInfoName" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="deliveryInfoTelephoneLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Telefono</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="deliveryInfoTelephone" />
								</label>
							</div>
						</div>
						<div class="flex-wrap" style="margin-top: 16px;">
							<div class="mr-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="deliveryInfoAddressLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Indirizzo</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input google_address_finder" placeholder="" id="deliveryInfoAddress" />
								</label>
							</div>
							<div class="ml-auto">
								<label class="mdc-text-field mdc-text-field--outlined" id="deliveryInfoDoorbellLabel">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label">Campanello</span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input type="text" class="mdc-text-field__input" id="deliveryInfoDoorbell" />
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="editDeliveryInfo">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div id="editPizzaComposition" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="d-flex">
						<div id="categorieIngredientiContainer" class="pick-one-of-these">
							<div class="categoria finder-container">
								<i class="mdi mdi-magnify finder-icon"></i>
								<input class="md-input finder-input" type="search" id="ingredientFinder" placeholder="Cerca" />
							</div>
							<div class="categoria pickable" id="assigned" onclick="show_assigned()">Assegnati</div>
							<div class="categoria pickable" id="pizzanote" onclick="show_notes()">Note</div>
							<?php foreach ($ingredients_categories as $category) { ?>
								<div class="categoria pickable" data-category="<?= $category ?>" onclick="select_ingredients_category('<?= addslashes($category) ?>')"><?= $category ?></div>
							<?php } ?>
						</div>
						<div id="elencoIngredienti" class="flex-1">
							<div class="flex-wrap">
								<div class="notes-container">
									<textarea id="pizza-notes" class="md-input" placeholder="Note"></textarea>
								</div>
								<?php foreach ($ingredients as $ingredient) { ?>
									<div class="ingrediente" data-elenco="<?= $ingredient['category'] ?>" data-id_ingredient="<?= $ingredient['id_ingredient'] ?>">
										<div class="d-flex light-green-600 -colorful">
											<!-- <div selecting-mark class="mr-auto mt-auto mb-auto"><i class="mdi mdi-check-bold"></i></div> -->
											<div ingredient-full-name><?= $ingredient['name'] ?></div>
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
		<div class="value" time></div>
		<div class="label">Cliente</div>
		<div class="value" customer></div>
		<div class="label">Indirizzo</div>
		<div class="value" address></div>
		<div class="label">Campanello</div>
		<div class="value" doorbell></div>
		<div class="label">Telefono</div>
		<div class="value" telephone></div>
		<div class="notes">
			<div class="label">Note</div>
			<div class="value" text-notes></div>
		</div>
		<div id="qrcode_printable"></div>
		<div pizze-container></div>
		<div totale-ordine></div>
	</div>
	<div id="ghostKitchenPrint">
		<div class="data"></div>
		<div class="label">Cliente</div>
		<div class="value" customer></div>
		<div class="label">Ordine</div>
		<div order-type takeaway class="value" style="display: none;">TakeAway</div>
		<div order-type delivery class="value">Domicilio</div>
		<div class="label">Orario</div>
		<div class="value" time></div>
		<div class="label">Distanza in macchina</div>
		<div class="value" travel_duration></div>
		<div pizze-container>
			<div id="ghostPizza">
				<div class="stackable-stuff">
					<div class="pizza-heading"><span pizza-quantity></span> <span pizza-name></span></div>
					<div pizza-ingredient id="kitchenIngredient"></div>
					<div pizza-ingredient without id="kitchenWithout"><i class="mdi mdi-minus"></i> <span without-name></span></div>
					<div pizza-aggiunta id="kitchenAddition"><i class="mdi mdi-plus-thick"></i> <span addition-name></span></div>
				</div>
				<div class="notes">
					<div class="label">Note</div>
					<div class="value" text-notes></div>
				</div>
			</div>
		</div>
	</div>
	<div id="printable"></div>
	<script>
	let geoShop = {
		north: <?= _GLOBAL_COMPANY['north'] ?: 45.665949 ?>,
		east: <?= _GLOBAL_COMPANY['east'] ?: 12.245599 ?>,
	};
	let ponies = JSON.parse(`<?= JSON_encode($ponies) ?>`);
	let ingredients = JSON.parse(`<?= JSON_encode($ingredients) ?>`);
	let ingredients_categories = JSON.parse(`<?= JSON_encode($ingredients_categories) ?>`);
	let pizzas = JSON.parse(`<?= JSON_encode($pizzas) ?>`);
	let pizzas_categories = JSON.parse(`<?= JSON_encode($pizzas_categories) ?>`);
	</script>
	<?= import_js('orders_manager') ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_PUBLIC_API ?>&callback=initMap&libraries=places&v=weekly" async></script>
</body>
</html>
