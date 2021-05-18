<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<?php html_head('Just Do It') ?>
<body>
	<?php topbar() ?>
	<?php main_menu() ?>
	<div id="pageContainer">
		<div>
		<?php foreach ($geos as $geo) { ?>
			<?= $geo['north'] ?>
			<?= $geo['east'] ?>
		<?php } ?>
		</div>
		<form action="<?= site_url() ?>main/g_geo" method="post">
			<input type="text" name="q" class="md-input" />
			<input type="text" name="y" class="md-input" />
			<button type="submit" class="btn light-blue">GEOCODE!</button>
		</form>
	</div>
</body>
</html>
