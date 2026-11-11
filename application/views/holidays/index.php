<?php

$messages = $this->session->flashdata('saved');
echo "<div class='messages'>{$messages}</div>";


echo iconbar([
	['holidays/add?session_id=' . $session->session_id, 'Adicionar Feriado', 'add.png'],
	['holidays/import_year/' . $session->session_id, 'Importar de outro ano letivo', 'calendar_view_month.png'],
]);

$sort_cols = ["Name", "StartDate", "EndDate", "None"];

?>



<table width="100%" cellpadding="4" cellspacing="2" border="0" class="zebra-table sort-table" up-data='<?= json_encode($sort_cols) ?>' id="jsst-holidays">

	<col />
	<col />
	<col />
	<col />

	<thead>
		<tr class="heading">
			<td class="h" width="30%" title="Nome">Nome</td>
			<td class="h" width="25%" title="StartDate">Data de Início</td>
			<td class="h" width="25%" title="EndDate">Data de término</td>
			<td class="h" width="10%" title="Duration">Duração</td>
			<td class="n" width="10%" title="X"></td>
		</tr>
	</thead>


	<?php if (empty($holidays)) : ?>

		<tbody>
			<tr>
				<td colspan="4" align="center" style="padding:16px 0; color: #666">Sem feriados.</td>
			</tr>
		</tbody>

	<?php else : ?>

		<tbody>
			<?php

			$dateFormat = 'd/m/y';

			foreach ($holidays as $holiday) {

				echo "<tr>";

				$name = html_escape($holiday->name);
				echo "<td>{$name}</td>";

				$start = $holiday->date_start ? $holiday->date_start->format($dateFormat) : '';
				echo "<td>{$start}</td>";

				$end = $holiday->date_end ? $holiday->date_end->format($dateFormat) : '';
				echo "<td>{$end}</td>";

				// Duração
				$duration = 1 + ($holiday->date_start->diff($holiday->date_end)->format('%a'));
				echo "<td>{$duration} dias</td>";

				echo "<td>";
				$actions['edit'] = 'holidays/edit/' . $holiday->holiday_id;
				$actions['delete'] = 'holidays/delete/' . $holiday->holiday_id;
				$this->load->view('partials/editdelete', $actions);
				echo "</td>";

				echo "</tr>";
			}

			?>
		</tbody>

	<?php endif; ?>

</table>