<?php
echo $this->session->flashdata('saved');
echo form_open_multipart('school/details_submit', array('id'=>'schooldetails', 'class'=>'cssform'));
?>


<fieldset>

	<legend accesskey="I" tabindex="<?php echo tab_index(); ?>">Informação Escolar</legend>

	<p>
		<label for="schoolname" class="required">Nome da escola</label>
		<?php
		$value = set_value('schoolname', element('name', $settings), FALSE);
		echo form_input(array(
			'name' => 'schoolname',
			'id' => 'schoolname',
			'size' => '30',
			'maxlength' => '255',
			'tabindex' => tab_index(),
			'value' => $value,
		));
		?>
	</p>
	<?php echo form_error('schoolname'); ?>

	<p>
		<label for="website">Endereço do site</label>
		<?php
		$value = set_value('website', element('website', $settings), FALSE);
		echo form_input(array(
			'name' => 'website',
			'id' => 'website',
			'size' => '40',
			'maxlength' => '255',
			'tabindex' => tab_index(),
			'value' => $value,
		));
		?>
	</p>
	<?php echo form_error('website'); ?>

</fieldset>



<fieldset>

	<legend accesskey="L" tabindex="<?php echo tab_index() ?>">Logotipo da Escola</legend>

	<div>Use esta seção para fazer upload do logotipo da escola.</div>

	<p>
		<label>Logotipo atual</label>
		<?php
		$logo = element('logo', $settings);
		if ( ! empty($logo) && is_file(FCPATH . 'uploads/' . $logo)) {
			echo img('uploads/' . $logo, FALSE, "style='padding:1px; border:1px solid #ccc; max-width: 300px; width: auto; height: auto'");
		} else {
			echo "<span><em>Nenhum encontrado</em></span>";
		}
		?>
	</p>

	<p>
		<label for="userfile">Carregar arquivo</label>
		<?php
		echo form_upload(array(
			'name' => 'userfile',
			'id' => 'userfile',
			'size' => '25',
			'maxlength' => '255',
			'tabindex' => tab_index(),
			'value' => '',
		));
		?>
		<p class="hint">Carregar um novo logotipo irá <span>substituirá</span> o atual.</p>
	</p>

	<?php
	if ($this->session->flashdata('image_error') != '' ) {
		echo "<p class='hint error'><span>" . $this->session->flashdata('image_error') . "</span></p>";
	}
	?>

	<p>
		<label for="logo_delete">Apagar logotipo?</label>
		<?php
		echo form_checkbox(array(
			'name' => 'logo_delete',
			'id' => 'logo_delete',
			'value' => '1',
			'tabindex' => tab_index(),
			'checked' => FALSE,
		));
		?>
		<p class="hint">Marque esta caixa para <span>excluir o logotipo atual</span>. Se você estiver enviando um novo logotipo, isso será feito automaticamente.</p>
	</p>

</fieldset>


<?php

$this->load->view('partials/submit', array(
	'submit' => array('Salvar', tab_index()),
	'cancel' => array('Voltar', tab_index(), 'controlpanel'),
));

echo form_close();
