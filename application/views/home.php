<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Home', ['home']) ?>
<body>
	<?php topbar(false) ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div class="flex-wrap">
			<div class="reducer">
				<div id="splash">
					<div class="title">PonyManager, per una gestione semplice ed efficace</div>
					<div class="subtitle">L'unico tool online per facilitare la gestione degli ordini. Ottimizzato per gli esercizi che effettuano consegne a domicilio grazie all'integrazione con Google Maps</div>
				</div>
			</div>
			<div class="fixer">
				<div class="flex-wrap">
					<?php foreach ($privileges as $privilege) { ?>
						<a class="card actionable" href="<?= site_url() ?>orders/index/company/<?= $privilege['uri_name'] ?>" style="color: black;">
							<div class="general-heading"> <?= $privilege['name'] ?> </div>
							<?php if (_GLOBAL_USER['email'] == $privilege['owner']) { ?>
								<div class="text-center"><i class="mdi mdi-star-outline"></i> Privilegi da proprietario</div>
							<?php } else { ?>
								<div class="text-center">Accesso limitato</div>
							<?php } ?>
						</a>
					<?php } ?>
					<div class="btn teal-700 ml-auto" id="newCompanyButton"><i class="mdi mdi-store"></i> Crea nuova</div>
				</div>
			</div>
		</div>
	</div>
	<?= import_js('home') ?>
</body>
</html>
