<?php
$attrs = ['class' => 'cssform-stacked'];
echo form_open('sessions/apply_week', $attrs, ['session_id' => $session->session_id]);
?>

<fieldset>

	<legend>Aplicação em massa</legend>

	<div style="padding: 12px 0 12px 0;">
		Aplique a Semana selecionada a todas as semanas deste ano letivo.
	</div>

	<p>
		<?php
		$options = array('' => 'Selecionar uma semana...');
		if (isset($weeks)) {
			foreach ($weeks as $week) {
				$options[$week->week_id] = html_escape($week->name);
			}
		}
		echo form_dropdown([
			'name' => 'week_id',
			'id' => 'week_id',
			'class' => 'form-control',
			'options' => $options,
		]);
		?>
	</p>


	<?php
	$this->load->view('partials/submit', array(
		'submit' => array('Aplicar', tab_index()),
	));
	?>

</fieldset>

<?= form_close() ?>