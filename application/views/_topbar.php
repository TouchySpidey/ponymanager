<div id="topBar" class="d-flex">
	<?php if ($withToggler) { ?>
		<div id="menuToggler" class="d-flex actionable">
			<div class="mt-auto mb-auto">
				<i class="mdi mdi-menu"></i>
			</div>
		</div>
	<?php } ?>
	<a id="logo" href="<?= site_url() ?>">
		<img src="<?= site_url() ?>frontend/images/logos/ponymanager.svg"/>
		<span>PonyManager</span>
	</a>
	<?php if ($landing) { ?>
		<?php if (!defined('_GLOBAL_USER')) { ?>
			<a href="<?= site_url() ?>main/login" class="ml-auto mt-auto mb-auto login-button mdc-button js-rippable">
				<span class="mdc-button__ripple"></span>
				<span class="mdc-button__label">Accedi</span>
			</a>
		<?php } else { ?>
			<a href="<?= site_url() ?>" class="ml-auto mt-auto mb-auto login-button mdc-button js-rippable">
				<span class="mdc-button__ripple"></span>
				<span class="mdc-button__label">Area riservata</span>
			</a>
		<?php } ?>
	<?php } ?>
</div>
