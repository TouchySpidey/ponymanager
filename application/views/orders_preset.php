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
				<div class="card parent-to-be-hovered p-relative">
					<div class="general-heading">Configurazione Servizio</div>
					<div class="ml-auto show-on-parent-hover actionable round-btn top-right-corner" id="editService"><i class="mdi mdi-pencil"></i></div>
					<div class="subtitle-sm text-center">Imposta l'opzione di default tra takeaway / delivery ed il costo base del servizio di delivery</div>
					<div class="d-flex">
						<div>Opzione di default: <div><b>Delivery</b></div></div>
						<div class="ml-auto">Costo della delivery: <div><b>2.50</b></div></div>
					</div>
				</div>
			</div>
			<div class="maincard">
				<div class="card parent-to-be-hovered p-relative">
					<div class="general-heading">Orari di apertura</div>
					<div class="ml-auto show-on-parent-hover actionable round-btn top-right-corner" id="editShifts"><i class="mdi mdi-pencil"></i></div>
					<div class="subtitle-sm text-center">Imposta gli orari in cui il tuo locale è aperto al pubblico, delimitando così il range di orari in cui è possibile eseguire ordini</div>
					<div class="d-flex" id="shiftList">
						<div id="ghostCardShift">
							<b>Dalle</b> <span class="js-from"></span> <b>alle</b> <span class="js-to"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="maincard">
				<div class="card parent-to-be-hovered p-relative">
					<div class="general-heading">Metodi di pagamento</div>
					<div class="ml-auto show-on-parent-hover actionable round-btn top-right-corner" id="editPayments"><i class="mdi mdi-pencil"></i></div>
					<div class="subtitle-sm text-center">Definisci quali metodi di pagamento accetti in questo locale</div>
					<div class="flex-wrap">
						<div class="card">Contanti</div>
						<div class="card">Bancomat</div>
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
	<div id="paymentsModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex very-wide-modal">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="subtitle-sm text-center">I metodi di pagamento attribuiti a ciascun ordine fanno sì che l'analitica risulti più accurata</div>
					<div>
					</div>
					<div class="d-flex">
						<div>
							<button class="btn red-800" id="discardPayments"><i class="mdi mdi-close"></i> Annulla</button>
						</div>
						<div class="ml-auto">
							<button class="btn gblue" id="savePayments"><i class="mdi mdi-check"></i> Salva</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="shiftsModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="general-heading">Orari di apertura</div>
					<div class="subtitle-sm text-center">Imposta gli orari in cui il tuo locale è aperto al pubblico, delimitando così il range di orari in cui è possibile eseguire ordini</div>
					<div class="v-flex flex-1">
						<div id="modalShiftList" style="margin: 22px auto;">
							<div class="shift-box" id="ghostShiftBox">
								<label>Dalle</label>
								<input class="md-input js-from" type="text" />
								<label>Alle</label>
								<input class="md-input js-to" type="text" />
							</div>
						</div>
						<div class="v-flex" style="margin: 0 auto 22px auto;">
							<div id="addShift" onclick="addShift()" class="btn green ml-auto mr-auto"><i class="mdi mdi-plus"></i> Aggiungi</div>
						</div>
					</div>
					<div class="d-flex">
						<div>
							<button class="btn red-800" id="discardPayments" onclick="syncShifts()"><i class="mdi mdi-close"></i> Annulla</button>
						</div>
						<div class="ml-auto">
							<button class="btn gblue" id="savePayments"><i class="mdi mdi-check"></i> Salva</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="serviceModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<div class="v-flex flex-1">
					<div class="general-heading">Configurazione Servizio</div>
					<div class="subtitle-sm text-center">Imposta l'opzione di default tra takeaway / delivery ed il costo base del servizio di delivery</div>
					<div class="v-flex flex-1">
						<div class="d-flex big-switch" style="margin: 22px auto;">
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
						<div style="margin: 22px auto;">
							<div><label>Costo base del servizio di delivery</label></div>
							<div>
								<input type="text" class="md-input" placeholder="Prezzo del delivery" />
							</div>
						</div>
					</div>
					<div class="d-flex">
						<div>
							<button class="btn red-800" id="discardPayments" onclick="syncShifts()"><i class="mdi mdi-close"></i> Annulla</button>
						</div>
						<div class="ml-auto">
							<button class="btn gblue" id="savePayments"><i class="mdi mdi-check"></i> Salva</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	let shifts = JSON.parse('<?= JSON_encode($shifts) ?>');
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
