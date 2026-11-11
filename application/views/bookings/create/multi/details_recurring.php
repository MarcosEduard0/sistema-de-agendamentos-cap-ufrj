<?php

use app\components\Calendar;
use app\components\TabPane;

// Date format of bookings
$date_format = setting('date_format_long', 'crbs');

// For period display
$time_fmt = setting('time_format_period');


echo "<fieldset>";

echo "<div style='margin-bottom:16px'>Insira os valores padrão para cada agendamento recorrente. Você pode alterá-los para cada agendamento na próxima etapa.</div>";

// Department
//
$field = 'department_id';
$label = form_label('Departamento', $field);
$options = results_to_assoc($all_departments, 'department_id', 'name', '(None)');
$value = set_value($field, $department ? $department->department_id : '', FALSE);
$input = form_dropdown([
	'name' => $field,
	'id' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control'
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));


// Who
//
$field = 'user_id';
$label = form_label('Usuário', $field);
$options = results_to_assoc($all_users, 'user_id', function ($user) {
	return strlen($user->displayname)
		? $user->displayname
		: $user->username;
}, '(None)');
$value = set_value($field, $user->user_id, FALSE);
$input = form_dropdown([
	'name' => $field,
	'id' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control'
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));


// Notes
//
$field = 'notes';
$value = set_value($field, '', FALSE);
$label = form_label('Descrição', 'notes');
$input = form_textarea([
	'autofocus' => 'true',
	'name' => $field,
	'id' => $field,
	'rows' => '3',
	'cols' => '50',
	'tabindex' => tab_index(),
	'value' => $value,
	'class' => 'form-control',
	'style' =>  'width: auto;'
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));

// Recurring start from
//
$field = 'recurring_start';
$value = set_value($field, 'date', FALSE);
$label = form_label('Começando de...', 'recurring_start');
$options = ['session' => 'Início do ano letivo', 'date' => 'Data(s) selecionada(s)'];
$input = form_dropdown([
	'name' => 'recurring_start',
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control',
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));

// Recurring end date
//
$field = 'recurring_end';
$label = form_label('Até...', 'recurring_end');
$value = set_value($field, 'session', FALSE);
$options = ['session' => 'Fim do ano letivo', 'date' => 'Data(s) selecionada(s)'];
$input = form_dropdown([
	'name' => 'recurring_end',
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control'
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));

echo "</fieldset>";
