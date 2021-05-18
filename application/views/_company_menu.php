<div id="mainMenuBackdrop">
	<div id="mainMenu" class="v-flex">
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
		<div style="max-height: calc(100vh - 177px); overflow: auto;" class="flex-1">
			<a href="<?= site_url() ?>main/index" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-home"></i><span class="main-link-description">Home</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/index/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-format-list-bulleted-square"></i><span class="main-link-description">Gestione Ordini</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a>
			<a href="<?= site_url() ?>menu/index/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-note-text"></i><span class="main-link-description">Modifica Menù</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/cost/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-book-open-variant"></i><span class="main-link-description">Nota spese</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a>
			<a href="<?= site_url() ?>orders/preset/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-moped"></i><span class="main-link-description">Pre-set degli ordini</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a>
			<!-- <a href="<?= site_url() ?>company/manage/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-store"></i><span class="main-link-description">Amministrazione</span></div>
					<div class="ml-auto show-on-parent-hover" desktop><i class="mdi mdi-chevron-right"></i></div>
				</div>
			</a> -->
			<a href="<?= site_url() ?>orders/analytics/company/<?= _COMPANY_URI ?>" class="parent-to-be-hovered main-link">
				<div class="d-flex link-block">
					<div><i class="mdi mdi-chart-areaspline"></i><span class="main-link-description">Storico ed Analisi</span></div>
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
		<a href="javascript:toggleFullScreen()" class="parent-to-be-hovered main-link">
			<div class="d-flex link-block">
				<div><i class="mdi mdi-fullscreen not-fullscreen-icon" style="display: none;"></i><i class="mdi mdi-fullscreen-exit fullscreen-icon"></i><span class="main-link-description">Modalità schermo intero</span></div>
			</div>
		</a>
	</div>
</div>

<script>
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
