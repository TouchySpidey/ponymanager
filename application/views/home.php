<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Home', ['home']) ?>
<body>
	<?php topbar(false) ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="landing-link">
			<a class="bluelink" href="<?= site_url() ?>main/landing"><i class="mdi mdi-arrow-left-thick"></i> Landing page</a>
		</div>
		<div class="home-grid">
			<div class="many-cards">
				<div class="general-heading">Gestione account</div>
				<div class="spaced-cards-container flex-wrap">
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Profilo</div>
							<div>Completando le informazioni del tuo profilo avrai accesso a tutte le funzionalità di PonyManager</div>
							<div class="custom-message mt-auto"><b>Profilo incompleto</b></div>
						</div>
						<div class="actionable action-list-item"><a class="bluelink">Profilo</a></div>
					</div>
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Cambio password</div>
							<div>È consigliabile modificare la password ogni qualche mese per prevenire accessi non autorizzati</div>
							<div class="custom-message mt-auto"><b>Ultimo cambio password: <span></span></b></div>
						</div>
						<div class="actionable action-list-item"><a class="bluelink">Modifica password</a></div>
					</div>
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Assistenza</div>
							<div>Visualizza i ticket che hai aperto per chiedere assistenza sulla piattaforma</div>
							<div class="custom-message mt-auto"><b>Non hai nuovi messaggi</b></div>
						</div>
						<div class="actionable action-list-item"><a class="bluelink">Chiedi assistenza</a></div>
					</div>
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Abbonamento PonyManager</div>
							<div>Controlla il tuo metodo di pagamento per continuare a utilizzare PonyManager al termine della prova gratuita</div>
							<div class="custom-message mt-auto"><b>Giorni di prova gratuita rimanenti: <span></span></b></div>
						</div>
						<div class="actionable action-list-item"><a class="bluelink">Gestione abbonamento</a></div>
					</div>
				</div>
			</div>
			<div class="companies-list flex-1">
				<div class="general-heading">I tuoi locali</div>
				<?php foreach ($privileges as $privilege) { ?>
					<a class="card actionable mdc-ripple-surface" href="<?= site_url() ?>orders/index/company/<?= $privilege['uri_name'] ?>" style="color: black;">
						<div class="card-title"> <?= $privilege['name'] ?> </div>
						<?php if (_GLOBAL_USER['email'] == $privilege['owner']) { ?>
							<div class="text-center"><i class="mdi mdi-star-outline"></i> Privilegi da proprietario</div>
						<?php } else { ?>
							<div class="text-center">Accesso limitato</div>
						<?php } ?>
					</a>
				<?php } ?>
				<div class="d-flex">
					<div class="btn teal-700 ml-auto" id="newCompanyButton"><i class="mdi mdi-store"></i> Crea nuovo</div>
				</div>
			</div>
		</div>
	</div>
	<div id="newCompanyModal" class="w3-modal modal-container order-modal">
		<div class="w3-modal modal-backdrop"></div>
		<div class="w3-modal-content v-flex">
			<div class="w3-container v-flex flex-1">
				<span onclick="closeModal(this)" class="w3-button w3-display-topright modal-closer">&times;</span>
				<form class="v-flex flex-1" method="POST" action="<?= site_url() ?>main/createCompany">
					<div class="general-heading">Nuovo locale</div>
					<div class="v-flex flex-1">
						<div class="flex-wrap" style="margin: 22px auto;">
							<div style="margin: 22px;">
								<div><label>Nome del locale</label></div>
								<div>
									<input id="shopName" name="name" type="text" class="md-input" placeholder="Nome" />
								</div>
							</div>
							<div style="margin: 22px;">
								<div><label>Tag <i class="mdi mdi-spin mdi-loading" style="display: none;" id="tagLoader"></i></label></div>
								<!-- <div class="subtitle-sm text-center">Imposta l'opzione di default tra takeaway / delivery ed il costo base del servizio di delivery</div> -->
								<div>
									<input id="shopTag" name="tag" type="text" class="md-input" placeholder="Tag" />
								</div>
							</div>
						</div>
						<div style="margin: 22px;">
							<div>Nei prossimi update di PonyManager, sarà possibile aprire gli ordini <b>online</b> ai clienti del locale. Il <b>tag</b> serve a generare un link unico al quale sarà possibile connettersi per visualizzare il menù ed effettuare ordini.</div>
							<div></div>
						</div>
					</div>
					<div class="d-flex">
						<div class="ml-auto">
							<button class="btn gblue" id="makeLocal"><i class="mdi mdi-check"></i> Crea</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?= import_js('home') ?>
</body>
</html>
