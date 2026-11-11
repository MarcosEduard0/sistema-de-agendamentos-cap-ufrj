<?php

$block_class = 'b-33';

$blocks = [];

$blocks[] = [
	'title' => 'Total de agendamentos',
	'figure' => $totals['all'],
];

$blocks[] = [
	'title' => 'Na sessão atual',
	'figure' => $totals['session'],
];

$blocks[] = [
	'title' => 'Ativos',
	'figure' => $totals['active'],
];

// total bookings ever
// total in current session
// current active bookings

// if max_active:
// maximum bookings allowed
// number permitted (max - active)

$max_active_bookings = (int) abs(setting('num_max_bookings'));
if ($max_active_bookings > 0) {

	$block_class = 'b-20';
	$num_active = (int) $totals['active'];

	$blocks[] = [
		'title' => 'Limite de agendamentos ativos',
		'figure' => $max_active_bookings,
	];

	$blocks[] = [
		'title' => 'Agendamentos disponíveis',
		'figure' => ($max_active_bookings - $num_active),
	];
}


echo '<dl class="teacher-stats">';
foreach ($blocks as $block) {
    echo '<div class="teacher-stat">';
    echo '<dt>' . html_escape($block['title']) . '</dt>';
    echo '<dd>' . number_format($block['figure'], 0, ',', '.') . '</dd>';
    echo '</div>';
}
echo '</dl>';
