<aside class="mdc-drawer mdc-drawer--modal" id="mainMenu">
	<div class="mdc-drawer__content">
		<nav class="mdc-list">
			<a class="js-rippable main-link mdc-list-item mdc-list-item--activated" href="<?= site_url() ?>main/home" aria-current="page">
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">inbox</i>
					<span class="mdc-list-item__text">Inbox</span>
				</div>
			</a>
			<a class="js-rippable main-link mdc-list-item" href="<?= site_url() ?>main/change_password">
				<span class="mdc-list-item__ripple"></span>
				<div class="link-block">
					<i class="material-icons mdc-list-item__graphic" aria-hidden="true">vpn_key</i>
					<span class="mdc-list-item__text">Cambia Password</span>
				</div>
			</a>
			<a class="js-rippable main-link mdc-list-item" href="<?= site_url() ?>main/logout">
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
</script>
