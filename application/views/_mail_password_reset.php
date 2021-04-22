<p>Ciao <?= $name ?>,</p>
<p>Per reimpostare la tua password clicca sul link qui sotto</p>
<p>
	<a href="<?= site_url() ?>main/reset_password/<?= $reset_token ?>">Scegli nuova password</a>
</p>
<p>Il link resterà valido <b>per le prossime 2 ore</b></p>
<p>Se non hai fatto tu questa richiesta, per favore contatta l'assistenza</p>
