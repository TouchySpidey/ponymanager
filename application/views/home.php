<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Home') ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div style="padding: 16px;">
			<div class="flex-wrap">
				<div class="card">
					<div class="general-heading">Informazioni</div>
					<div>
						email: <?= $this->session->user['email'] ?>
					</div>
				</div>
				<?php foreach ($privileges as $privilege) { ?>
					<a class="card" href="<?= site_url() ?>orders/index/company/<?= $privilege['uri_name'] ?>" style="color: black;">
						<div class="general-heading"><?= $privilege['name'] ?></div>
						<?php if (_GLOBAL_USER['email'] == $privilege['owner']) { ?>
							<div>Privilegi da proprietario</div>
						<?php } else { ?>
							<div>Accesso limitato</div>
						<?php } ?>
					</a>
				<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>
