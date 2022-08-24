<?php

use app\components\Calendar;

$day_name = Calendar::get_day_name($slot->datetime->format('N'));
$day_name = Calendar::traslate_2_portuguese($day_name);

// Lista de datas disponíveis para escolher para datas recorrentes
//
$recurring_date_options = [];
if (isset($slot->recurring_dates)) {
	foreach ($slot->recurring_dates as $date) {
		// $title = $date->date->format(setting('date_format_long'));
		$title = $date->date->format('d-m-Y');
		$title = trim(str_replace($day_name, '', $title));
		if ($date->date->format('Y-m-d') == $slot->date) $title = '* ' . $title;
		$recurring_date_options[$date->date->format('Y-m-d')] = $title;
	}
}

echo "<fieldset style='border:0;padding:0 8px'>";

// Departamento
//
$field = sprintf('slots[%d][department_id]', $slot->mbs_id);
$label = form_label('Departamento', $field);
$options = results_to_assoc($all_departments, 'department_id', 'name', '(Nenhum)');
$default = isset($default_values['department_id']) ? $default_values['department_id'] : '';
$value = set_value($field, $default, FALSE);
$input = form_dropdown([
	'name' => $field,
	'id' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control',
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));


// Usuário
//
$field = sprintf('slots[%d][user_id]', $slot->mbs_id);
$label = form_label('Usuário', $field);
$options = results_to_assoc($all_users, 'user_id', function ($user) {
	return strlen($user->displayname)
		? $user->displayname
		: $user->username;
}, '(Nenhum)');
$default = isset($default_values['user_id']) ? $default_values['user_id'] : '';
$value = set_value($field, $default, FALSE);
$input = form_dropdown([
	'name' => $field,
	'id' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control',
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));


// Descrição
//
$field = sprintf('slots[%d][notes]', $slot->mbs_id);
$default = isset($default_values['notes']) ? $default_values['notes'] : '';
$value = set_value($field, $default, FALSE);
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
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));

// Recurring start from
//
$field = sprintf('slots[%d][recurring_start]', $slot->mbs_id);
$default = ($default_values['recurring_start'] == 'date')
	? $slot->date
	: $default_values['recurring_start'];
$value = set_value($field, $default, FALSE);
$label = form_label('Começando de...', 'recurring_start');
$options = ['session' => '(Início da sessão)', 'Data específica...' => $recurring_date_options];
$input = form_dropdown([
	'name' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control',
]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));

// Recurring end date
//
$field = sprintf('slots[%d][recurring_end]', $slot->mbs_id);
$default = ($default_values['recurring_end'] == 'date')
	? $slot->date
	: $default_values['recurring_end'];
$label = form_label('Até...', 'recurring_end');
$value = set_value($field, 'session', FALSE);
$options = ['session' => '(Fim da sessão)', 'Data específica...' => $recurring_date_options];
$input = form_dropdown([
	'name' => $field,
	'options' => $options,
	'selected' => $value,
	'class' => 'form-control',

]);
echo sprintf("<p>%s%s</p>%s", $label, $input, form_error($field));


echo "</fieldset>";
