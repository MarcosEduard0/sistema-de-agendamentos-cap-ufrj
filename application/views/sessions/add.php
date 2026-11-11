<?php

$session_id = NULL;

if (isset($session) && is_object($session)) {
	$session_id = set_value('session_id', $session->session_id);
}

echo form_open(current_url(), ['class' => 'needs-validation', 'id' => 'session_add', 'novalidate' => 'true'], ['session_id' => $session_id]);

?>

<fieldset>

	<legend accesskey="S" tabindex="<?= tab_index() ?>">Ano letivo</legend>

	<div class="form-group row">
		<label for="name" class="col-sm-2 col-form-label">Nome</label>
		<div class="col-sm-4">
			<?php
			$field = 'name';
			$value = set_value($field, isset($session) ? $session->name : '', FALSE);
			echo form_input(array(
				'name' => $field,
				'id' => $field,
				'size' => '25',
				'maxlength' => '50',
				'tabindex' => tab_index(),
				'class' => 'form-control',
				'value' => $value,
				'required' => ''
			));
			?>
			<div class="invalid-feedback">Por favor, digite um nome.</div>
		</div>
	</div>
	<?php echo form_error('name'); ?>

	<div class="form-group row">
		<label for="date_start" class="col-sm-2 col-form-label">Data de início</label>
		<div class="col-sm-4">
			<?php
			$field = 'date_start';
			$value = set_value('date_start', isset($session) ? $session->date_start->format('Y-m-d') : '', FALSE);
			echo form_input(array(
				'name' => 'date_start',
				'id' => 'date_start',
				'size' => '10',
				'maxlength' => '10',
				'tabindex' => tab_index(),
				'value' => $value,
				'class' => 'form-control',
				'type' => 'date',
				'required' => '',
			));
			?>
			<div class="invalid-feedback">Por favor, digite uma data.</div>
		</div>
	</div>
	<?php echo form_error($field); ?>

	<div class="form-group row">
		<label for="date_end" class="col-sm-2 col-form-label">Data de fim</label>
		<div class="col-sm-4">
			<?php
			$field = 'date_end';
			$value = set_value('date_end', isset($session) ? $session->date_end->format('Y-m-d') : '', FALSE);
			echo form_input(array(
				'name' => 'date_end',
				'id' => 'date_end',
				'size' => '10',
				'maxlength' => '10',
				'tabindex' => tab_index(),
				'value' => $value,
				'class' => 'form-control',
				'type' => 'date',
				'required' => '',
			));
			?>
			<div class="invalid-feedback">Por favor, digite uma data.</div>
		</div>
	</div>

	<?php echo form_error($field); ?>

	<div class="form-group row">
		<label class="col-sm-2 col-form-label" for="is_selectable">Agendável</label>
		<div class="col-sm-3" style="padding-top: 8px">
			<?php
			$field = 'is_selectable';
			$value = isset($session) ? $session->is_selectable : '0';
			$checked = set_checkbox($field, '1', $value == '1');
			echo form_hidden($field, '0');
			echo form_checkbox(array(
				'name' => $field,
				'id' => $field,
				'value' => '1',
				'tabindex' => tab_index(),
				'checked' => $checked,
			));
			?>
		</div>
		<div class="hit">Marque esta caixa para permitir agendamentos para este ano letivo</div>
	</div>

	<?php echo form_error($field); ?>


</fieldset>


<?php

$this->load->view('partials/submit', array(
	'submit' => array('Salvar', tab_index()),
	'cancel' => array('Cancelar', tab_index(), 'sessions'),
));

echo form_close();
