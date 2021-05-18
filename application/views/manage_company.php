<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Amministrazione') ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="d-flex filter-orders-by-type pick-one-of-these" id="tabs" style="padding: 16px;">
			<div class="actionable pickable filter-tab" tab="order">Ordine</div>
			<div class="actionable pickable filter-tab" tab="map">Mappa</div>
			<div class="actionable pickable filter-tab" tab="users">Utenti</div>
			<div class="actionable pickable filter-tab" tab="modules">Moduli</div>
		</div>
		<div id="panels" style="padding: 16px;">
			<div class="tabs" tab="order">
				<div class="flex-wrap">
					<div class="card">
						<div class="general-heading">Tipologia di default</div>
						<div class="pick-one-of-these">
							<div class="">
								<div class="d-flex">
									<div class="d-flex md-checkbox-lg actionable pickable">
										<input type="radio" name="delivery_type" />
										<div>Delivery</div>
									</div>
									<div class="order-pricing">
										<input type="text" class="md-input-xs order-pricing" value="1,50€" />
									</div>
								</div>
							</div>
							<div class="">
								<div class="d-flex">
									<div class="d-flex md-checkbox-lg actionable pickable">
										<input type="radio" name="delivery_type" />
										<div>Takeaway</div>
									</div>
									<input type="text" class="md-input-xs order-pricing" value="1,50€" />
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="general-heading">Pony disponibili</div>
						<div class="d-flex">
							<input type="text" class="md-input-md" placeholder="Nome del pony" />
							<button type="button" class="btn blue"><i class="mdi mdi-plus"></i> Aggiungi</button>
						</div>
					</div>
					<div class="card">
						<div class="general-heading">Pagamenti accettati</div>
						<div class="d-flex">
							<input type="text" class="md-input-md" placeholder="Metodo di pagamento" />
							<button type="button" class="btn blue"><i class="mdi mdi-plus"></i> Aggiungi</button>
						</div>
					</div>
					<div class="card">
						<div class="general-heading">Fasce orarie</div>
						<div class="d-flex">
							<input type="text" class="md-input-md" placeholder="Inizio" />
							<input type="text" class="md-input-md" placeholder="Fine" />
							<button type="button" class="btn blue"><i class="mdi mdi-plus"></i> Aggiungi</button>
						</div>
					</div>
				</div>
			</div>
			<div class="tabs" tab="map">
				Posizione del locale
			</div>
			<div class="tabs" tab="users">
				Utenti
			</div>
			<div class="tabs" tab="modules">
				Mappa: prova gratuita un mese
				poi: 5€ al mese
				Ricerca indirizzo: prova gratuita un mese
				poi: 3€ al mese
				Analitica avanzata: prova gratuita un mese
				poi: 3€ al mese
			</div>
		</div>
	</div>
	<?= import_js('manage_company') ?>
</body>
</html>
