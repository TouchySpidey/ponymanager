<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Landing', ['landing']) ?>
<body>
	<?php topbar(false, true) ?>
	<div id="pageContainer">
		<div id="splashContainer">
			<div id="splash">
				<div class="title">PonyManager, per una gestione semplice ed efficace</div>
				<div class="subtitle">L'unico tool online per facilitare la gestione degli ordini. Ottimizzato per gli esercizi che effettuano consegne a domicilio grazie all'integrazione con Google Maps</div>
				<div class="subtitle">Registrati ora per una prova gratuita!</div>
				<div class="d-flex">
					<a class="mdc-button mdc-button--raised js-rippable signup-button" href="<?= site_url() ?>main/signup">
						<span class="mdc-button__ripple"></span>
						<span class="mdc-button__label">Registrati</span>
					</a>
				</div>
			</div>
			<div id="infographic">
				<div class="infographic-container">
					<div class="infographic-image" style="background-image: url('<?= site_url() ?>frontend/images/icons/ninja.svg')"></div>
					<div class="infographic-description">Il prodotto chiavi in mano per una partenza in pole position!</div>
				</div>
			</div>
		</div>
	</div>
	<div id="cookieBar">
		<div class="cookieBarInner">
			<span class="cookieBarText">PonyManager utilizza i cookie per fornire i propri servizi e analizzare il traffico in forma anonima. Puoi limitare l'accesso ai cookie in qualsiasi momento nelle impostazioni del browser.</span>
			<span class="cookieBarButtons">
				<a href="<?= site_url() ?>main/cookies" rel="noopener" target="_blank" class="cookieBarButton cookieBarMoreButton">Ulteriori informazioni.</a>
				<a href="#" class="cookieBarButton cookieBarConsentButton" id="cookieOk">Ok, ho capito.</a>
			</span>
		</div>
	</div>
	<?= import_js('landing') ?>
</body>
