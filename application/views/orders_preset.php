<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Preset Ordini', ['orders_preset', 'big-switch']) ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="flex-wrap">
			<div class="maincard">
				<div class="mdc-card mdc-card--outlined parent-to-be-hovered p-relative">
					<div class="mdc-card-wrapper__text-section general-heading">Impostazioni del Servizio</div>
					<div class="mdc-card-wrapper__text-section subtitle-sm text-center">Il servizio di default quando generi un ordine ed il prezzo del servizio di delivery</div>
					<div class="flex-wrap chips-container">
						<div class="ml-auto mr-auto mdc-chip gblue" role="row">
							<div class="mdc-chip__ripple"></div>
							<span role="gridcell">
								<span role="button" tabindex="0" class="mdc-chip__primary-action">
									<span class="mdc-chip__text">Contanti</span>
								</span>
							</span>
						</div>
						<div class="ml-auto mr-auto mdc-chip gblue" role="row">
							<div class="mdc-chip__ripple"></div>
							<span role="gridcell">
								<span role="button" tabindex="0" class="mdc-chip__primary-action">
									<span class="mdc-chip__text">Bancomat</span>
								</span>
							</span>
						</div>
					</div>
					<div class="mdc-card__actions">
						<button class="ml-auto mdc-button js-rippable mdc-card__action mdc-card__action--button" __open_dialog="#serviceDialog">
							<div class="mdc-button__ripple"></div>
							<i class="material-icons mdc-button__icon">edit</i> <span class="mdc-button__label">Modifica</span>
						</button>
					</div>
				</div>
			</div>
			<div class="maincard">
				<div class="mdc-card mdc-card--outlined parent-to-be-hovered p-relative">
					<div class="mdc-card-wrapper__text-section general-heading">Orari di apertura</div>
					<div class="mdc-card-wrapper__text-section subtitle-sm text-center">I range di orari durante i quali il locale è aperto al pubblico e si accettano ordinazioni</div>
					<div class="flex-wrap chips-container" id="shiftList">
						<div class="ml-auto mr-auto mdc-chip gblue" role="row" id="ghostCardShift">
							<div class="mdc-chip__ripple"></div>
							<span role="gridcell">
								<span role="button" tabindex="0" class="mdc-chip__primary-action">
									<span class="mdc-chip__text">Dalle <span class="js-from"></span> alle <span class="js-to"></span></span>
								</span>
							</span>
						</div>
					</div>
					<div class="mdc-card__actions">
						<button class="ml-auto mdc-button js-rippable mdc-card__action mdc-card__action--button" __open_dialog="#shiftsDialog">
							<div class="mdc-button__ripple"></div>
							<i class="material-icons mdc-button__icon">edit</i> <span class="mdc-button__label">Modifica</span>
						</button>
					</div>
				</div>
			</div>
			<div class="maincard">
				<div class="mdc-card mdc-card--outlined parent-to-be-hovered p-relative">
					<div class="mdc-card-wrapper__text-section general-heading">Metodi di pagamento</div>
					<div class="mdc-card-wrapper__text-section subtitle-sm text-center">I metodi di pagamento che vengono accettati nel locale</div>
					<div class="flex-wrap chips-container">
						<div class="ml-auto mr-auto mdc-chip gblue" role="row">
							<div class="mdc-chip__ripple"></div>
							<span role="gridcell">
								<span role="button" tabindex="0" class="mdc-chip__primary-action">
									<span class="mdc-chip__text">Contanti</span>
								</span>
							</span>
						</div>
						<div class="ml-auto mr-auto mdc-chip gblue" role="row">
							<div class="mdc-chip__ripple"></div>
							<span role="gridcell">
								<span role="button" tabindex="0" class="mdc-chip__primary-action">
									<span class="mdc-chip__text">Bancomat</span>
								</span>
							</span>
						</div>
					</div>
					<div class="mdc-card__actions">
						<button class="ml-auto mdc-button js-rippable mdc-card__action mdc-card__action--button" __open_dialog="#paymentsDialog">
							<div class="mdc-button__ripple"></div>
							<i class="material-icons mdc-button__icon">edit</i> <span class="mdc-button__label">Modifica</span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex">
			<div class="maincard">
				<div class="card v-flex parent-to-be-hovered p-relative">
					<div class="general-heading">Posizione del Locale</div>
					<div class="ml-auto show-on-parent-hover actionable round-btn top-right-corner" id="editMap"><i class="mdi mdi-pencil"></i></div>
					<div class="subtitle-sm text-center">Imposta la posizione esatta del tuo locale per avere una stima più accurata dei tempi di consegna</div>
					<div id="staticMap" style="background-image: url('https://maps.googleapis.com/maps/api/staticmap?center=45.665949,12.245599&zoom=14&size=1280x400&scale=2&markers=icon:<?= urlencode(site_url() . 'frontend/images/icons/blue_dot.svg') ?>|45.665949,12.245599&key=<?= GOOGLE_PUBLIC_API ?>')"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="positionModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex very-wide-modal">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="subtitle-sm text-center">Puoi cercare l'indirizzo e selezionare uno dei risultati oppure cliccare sulla mappa per un posizionamento più preciso</div>
					<div class="d-flex">
						<input id="gfinder" type="text" class="ml-auto mr-auto md-input" placeholder="Indirizzo" />
					</div>
					<div id="Gmap" class="flex-1"></div>
					<div class="d-flex">
						<div>
							<button class="btn red-800" id="discardGeo" onclick="syncGeo()"><i class="mdi mdi-close"></i> Annulla</button>
						</div>
						<div class="ml-auto">
							<button class="btn gblue" id="saveGeo" onclick="updateGeo()"><i class="mdi mdi-check"></i> Salva posizione</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="mdc-dialog" id="shiftsDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog">
				<div class="mdc-dialog__content">
					<div>
						<div class="general-heading">Configurazione degli orari</div>
						<div class="subtitle-sm text-center">Imposta gli orari per delimitare i range in cui è possibile generare ordini</div>
						<div class="v-flex flex-1">
							<div id="modalShiftList">
								<div class="shift-box" id="ghostShiftBox">
									<div class="d-flex">
										<div class="ml-auto mr-auto d-flex">
											<div class="v-flex">
												<button class="js-remove-range mdc-button js-rippable mdc-button flex-1" tabindex="0">
													<div class="mdc-button__ripple"></div>
													<i class="material-icons mdc-button__icon" style="margin-right: 0;">close</i>
												</button>
											</div>
											<div style="margin-right: 4px;">
												<label class="mdc-text-field mdc-text-field--outlined js-from">
													<span class="mdc-notched-outline">
														<span class="mdc-notched-outline__leading"></span>
														<span class="mdc-notched-outline__notch">
															<span class="mdc-floating-label">Dalle</span>
														</span>
														<span class="mdc-notched-outline__trailing"></span>
													</span>
													<input type="text" class="mdc-text-field__input" />
												</label>
											</div>
											<div style="padding-right: 64px;">
												<label class="mdc-text-field mdc-text-field--outlined js-to">
													<span class="mdc-notched-outline">
														<span class="mdc-notched-outline__leading"></span>
														<span class="mdc-notched-outline__notch">
															<span class="mdc-floating-label">Alle</span>
														</span>
														<span class="mdc-notched-outline__trailing"></span>
													</span>
													<input type="text" class="mdc-text-field__input" />
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex">
								<div class="ml-auto mr-auto" style="padding: 12px;">
									<button class="mdc-button mdc-button--outlined js-rippable mdc-button flex-1" tabindex="0" onclick="addShift()">
										<div class="mdc-button__ripple"></div>
										<i class="material-icons mdc-button__icon" style="margin-right: 0;">add</i>
										<span class="mdc-button__label">Orario</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveShift">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div class="mdc-dialog" id="serviceDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog">
				<div class="mdc-dialog__content">
					<div>
						<div class="general-heading">Configurazione Servizio</div>
						<div class="subtitle-sm text-center">Imposta l'opzione di default tra takeaway / delivery che troverai pre-selezionata quando generi un ordine ed il prezzo del servizio di delivery</div>
						<div class="v-flex flex-1">
							<div class="d-flex big-switch" style="margin: 0 auto; padding: 22px;">
								<div class="service-option active leftyOption">
									Delivery
								</div>
								<div class="cp-switch mt-auto mb-auto">
									<input id="one" class="middle-switch cp-input" type="checkbox">
									<label for="one" class="cp-slider"></label>
								</div>
								<div class="service-option rightyOption">
									Takeaway
								</div>
							</div>
							<div class="d-flex">
								<div class="ml-auto mr-auto" style="padding: 22px 22px 0;">
									<label class="mdc-text-field mdc-text-field--outlined" id="deliveryPrice">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<span class="mdc-floating-label">Prezzo della delivery</span>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input type="text" class="mdc-text-field__input" />
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveService">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>

	<div class="mdc-dialog" id="paymentsDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface" role="alertdialog">
				<div class="mdc-dialog__content">
					<div>
						<div class="general-heading">Configurazione Pagamenti</div>
						<div class="subtitle-sm text-center">I metodi di pagamento attribuiti a ciascun ordine fanno sì che l'analitica risulti più accurata</div>
						<div class="v-flex flex-1">
							<div id="modalPaymentList">
								<div class="shift-box" id="ghostPaymentBox">
									<div class="d-flex">
										<div class="ml-auto mr-auto d-flex">
											<div class="v-flex">
												<button class="js-remove-payment mdc-button js-rippable mdc-button flex-1" tabindex="0">
													<div class="mdc-button__ripple"></div>
													<i class="material-icons mdc-button__icon" style="margin-right: 0;">close</i>
												</button>
											</div>
											<div style="padding-right: 64px;">
												<label class="mdc-text-field mdc-text-field--outlined js-from">
													<span class="mdc-notched-outline">
														<span class="mdc-notched-outline__leading"></span>
														<span class="mdc-notched-outline__notch">
															<span class="mdc-floating-label">Metodo di pagamento</span>
														</span>
														<span class="mdc-notched-outline__trailing"></span>
													</span>
													<input type="text" class="mdc-text-field__input" />
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="d-flex">
								<div class="ml-auto mr-auto" style="padding: 12px;">
									<button class="mdc-button mdc-button--outlined js-rippable mdc-button flex-1" tabindex="0" onclick="addPayment()">
										<div class="mdc-button__ripple"></div>
										<i class="material-icons mdc-button__icon" style="margin-right: 0;">add</i>
										<span class="mdc-button__label">Metodo</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="mdc-dialog__actions">
					<button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="cancel">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Annulla</span>
					</button>
					<button type="button" class="mdc-button mdc-button--raised mdc-dialog__button" data-mdc-dialog-action="saveService">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Salva</span>
					</button>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
	<script>
	let shifts = JSON.parse('<?= JSON_encode($shifts) ?>');
	let deliveryPrice = 0;
	let serviceTakeAway = true;
	let geoShop = {
		north: 45.665949,
		east: 12.245599,
	}
	let staticUrlPrefix = 'https://maps.googleapis.com/maps/api/staticmap?center=';
	let staticUrlMiddle = '&zoom=14&size=1280x400&scale=2&markers=icon:<?= urlencode(site_url() . 'frontend/images/icons/blue_dot.svg') ?>|';
	let staticUrlSuffix = '&key=<?= GOOGLE_PUBLIC_API ?>';
	</script>
	<?= import_js('orders_preset', 'big-switch') ?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_PUBLIC_API ?>&callback=initMap&libraries=places&v=weekly" async></script>
</body>
</html>
