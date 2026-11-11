<div class="bd-callout bd-callout-info">
	<h6>Feriados escolares</h6>
	<dd>As datas devem ser a primeira e a última datas do <strong>feriado</strong> em si, não incluem os dias de aula.</dd>
	<h6>Ano letivo</h6>
	<dd>O feriado deve ser em <?= html_escape($session->name) ?>: entre
		<span><?= $session->date_start->format('d/m/Y') ?></span> e
		<span><?= $session->date_end->format('d/m/Y') ?>.</span>
	</dd>

	<h6>Formato de data</h6>
	<dd>Use o <strong>DD/MM/AAAA</strong> formato ao inserir datas, por exemplo <strong>16/04/2020</strong></dd>
</div>