<div id="mainMenuBackdrop">
	<div id="mainMenu">
		<div class="parent-to-be-hovered main-link" style="padding: 12px;">
			<div class="d-flex">
				<div class="ml-auto mr-auto">
					<a id="logo" href="<?= site_url() ?>">
						<img src="<?= site_url() ?>frontend/images/logos/ponymanager.svg"/>
						<span>PonyManager</span>
					</a>
				</div>
			</div>
		</div>
		<a href="<?= site_url() ?>main/index" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-home"></i><span class="main-link-description">Home</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>orders/index/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-format-list-bulleted-square"></i><span class="main-link-description">Ordini</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>menu/index/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-note-text"></i><span class="main-link-description">Menù</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>main/fattorini" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-moped"></i><span class="main-link-description">Impostazioni Ordine</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>company/manage/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-store"></i><span class="main-link-description">Impostazioni Locale</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>orders/analytics/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-chart-areaspline"></i><span class="main-link-description">Analitica</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
		<a href="<?= site_url() ?>main/logout" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-logout"></i><span class="main-link-description">Logout</span></div>
				<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
			</div>
		</a>
	</div>
</div>
