<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Home', ['home']) ?>
<body>
	<?php topbar(false) ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="home-grid">
			<div class="many-cards">
				<div class="general-heading">Gestione account</div>
				<div class="spaced-cards-container flex-wrap">
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Cambio password</div>
							<div>È consigliabile modificare la password ogni qualche mese per prevenire accessi non autorizzati</div>
							<div class="custom-message mt-auto"><b>Ultimo cambio password: <span></span></b></div>
						</div>
						<div class="actionable action-list-item"><a>Modifica password</a></div>
					</div>
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Abbonamento PonyManager</div>
							<div>Controlla il tuo metodo di pagamento per continuare a utilizzare PonyManager al termine della prova gratuita</div>
							<div class="custom-message mt-auto"><b>Giorni di prova gratuita rimanenti: <span></span></b></div>
						</div>
						<div class="actionable action-list-item"><a>Abbonamento</a></div>
					</div>
					<div class="card card-paddingless v-flex">
						<div class="card-body v-flex flex-1">
							<div class="card-title">Assistenza</div>
							<div>Visualizza i ticket che hai aperto per chiedere assistenza sulla piattaforma</div>
							<div class="custom-message mt-auto"><b>Non hai nuovi messaggi</b></div>
						</div>
						<div class="actionable action-list-item"><a>Chiedi assistenza</a></div>
					</div>
				</div>
			</div>
			<div class="companies-list flex-1">
				<div class="general-heading">Le tue aziende</div>
				<?php foreach ($privileges as $privilege) { ?>
					<a class="card actionable" href="<?= site_url() ?>orders/index/company/<?= $privilege['uri_name'] ?>" style="color: black;">
						<div class="card-title"> <?= $privilege['name'] ?> </div>
						<?php if (_GLOBAL_USER['email'] == $privilege['owner']) { ?>
							<div class="text-center"><i class="mdi mdi-star-outline"></i> Privilegi da proprietario</div>
						<?php } else { ?>
							<div class="text-center">Accesso limitato</div>
						<?php } ?>
					</a>
				<?php } ?>
				<div class="d-flex">
					<div class="btn teal-700 ml-auto" id="newCompanyButton"><i class="mdi mdi-store"></i> Crea nuova</div>
				</div>
			</div>
		</div>
	</div>
	<?= import_js('home') ?>
</body>
</html>
