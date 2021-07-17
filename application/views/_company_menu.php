<aside class="mdc-drawer mdc-drawer--modal" id="mainMenu">
	<div class="mdc-drawer__content">
		<nav class="mdc-list" id="drawerList">
			<a href="<?= site_url() ?>main/index" <?php if (current_url() == site_url().'main/index') { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">home</i>
					<span class="mdc-list-item__text">Home</span>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/index/company/<?= _COMPANY_URI ?>" <?php if (current_url() == site_url().'orders/index/company/'._COMPANY_URI) { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">list</i>
					<span class="mdc-list-item__text">Gestione Ordini</span>
				</div>
			</a>
			<a href="<?= site_url() ?>menu/index/company/<?= _COMPANY_URI ?>" <?php if (current_url() == site_url().'menu/index/company/'._COMPANY_URI) { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">description</i>
					<span class="mdc-list-item__text">Modifica menù</span>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/cost/company/<?= _COMPANY_URI ?>" <?php if (current_url() == site_url().'orders/cost/company/'._COMPANY_URI) { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">menu_book</i>
					<span class="mdc-list-item__text">Nota spese</span>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/preset/company/<?= _COMPANY_URI ?>" <?php if (current_url() == site_url().'orders/preset/company/'._COMPANY_URI) { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">moped</i>
					<span class="mdc-list-item__text">Pre-set degli ordini</span>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/analytics/company/<?= _COMPANY_URI ?>" <?php if (current_url() == site_url().'orders/analytics/company/'._COMPANY_URI) { ?>class="js-rippable main-link mdc-list-item mdc-list-item--activated" aria-current="page" tabindex="0"<?php } else { ?>class="js-rippable main-link mdc-list-item"<?php } ?>>
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">analytics</i>
					<span class="mdc-list-item__text">Storico ed Analisi</span>
				</div>
			</a>
			<a href="<?= site_url() ?>main/logout" class="js-rippable main-link mdc-list-item">
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">logout</i>
					<span class="mdc-list-item__text">Logout</span>
				</div>
			</a>
		</nav>
	</div>
</aside>
<div class="mdc-drawer-scrim"></div>
<script>
let menuDrawer = mdc.drawer.MDCDrawer.attachTo(document.getElementById('mainMenu'));
var elem = document.documentElement;
function toggleFullScreen() {
	if (screen.width == window.innerWidth && screen.height == window.innerHeight) {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) { /* Safari */
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) { /* IE11 */
			document.msExitFullscreen();
		}
	} else {
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.webkitRequestFullscreen) { /* Safari */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) { /* IE11 */
			elem.msRequestFullscreen();
		}
	}
}

</script>
