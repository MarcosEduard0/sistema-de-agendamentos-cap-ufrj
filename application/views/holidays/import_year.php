<?php if ($error): ?>
    <?= msgbox('error', $error) ?>
<?php endif; ?>
<p>Copie os feriados de outro ano letivo, escolhendo os itens e revisando as datas antes de salvar.</p>
<?= form_open(current_url(), ['method' => 'get']) ?>
<div class="form-group">
    <label for="source_id">Ano letivo de origem</label>
    <?php
    $options = ['' => 'Selecione um ano letivo'];
    foreach ($sources as $year) { $options[$year->session_id] = $year->name; }
    echo form_dropdown('source_id', $options, $source ? $source->session_id : '', ['id' => 'source_id', 'class' => 'form-control', 'required' => 'required']);
    ?>
</div>
<button class="btn btn-primary" type="submit">Carregar feriados</button>
<?= form_close() ?>
<?php if (!$sources): ?>
    <p>Não há outro ano letivo cadastrado para importar.</p>
<?php elseif ($source && !$holidays): ?>
    <p>O ano letivo selecionado não possui feriados.</p>
<?php elseif ($source): ?>
    <hr>
    <p><strong>Revise as datas:</strong> a proposta apenas ajusta o ano. Carnaval, Páscoa, Corpus Christi, recessos e outras datas móveis precisam ser conferidos. Nenhum feriado será importado sem sua confirmação.</p>
    <p>Destino: <strong><?= html_escape($target->name) ?></strong>, de <?= $target->date_start->format('d/m/Y') ?> a <?= $target->date_end->format('d/m/Y') ?>. Itens com o mesmo nome e intervalo já cadastrados serão ignorados.</p>
    <?= form_open(current_url(), ['novalidate' => 'novalidate'], ['source_id' => $source->session_id, 'import_token' => $import_token]) ?>
    <table class="zebra-table" width="100%">
        <thead><tr><td>Importar</td><td>Feriado</td><td>Datas na origem</td><td>Início no destino</td><td>Término no destino</td></tr></thead>
        <tbody>
        <?php
        $offset = (int) $target->date_start->format('Y') - (int) $source->date_start->format('Y');
        $selected = $this->input->post('selected');
        $selected = is_array($selected) ? $selected : [];
        foreach ($holidays as $holiday):
            $id = $holiday->holiday_id;
            $start = $this->holiday_import->propose($holiday->date_start, $offset);
            $end = $this->holiday_import->propose($holiday->date_end, $offset);
        ?>
            <tr>
                <td><?= form_checkbox(['name' => 'selected[]', 'value' => $id, 'id' => 'holiday-' . $id, 'checked' => in_array((string) $id, array_map('strval', $selected), true)]) ?></td>
                <td><label for="holiday-<?= $id ?>"><?= html_escape($holiday->name) ?></label></td>
                <td><?= $holiday->date_start->format('d/m/Y') ?> a <?= $holiday->date_end->format('d/m/Y') ?></td>
                <td><?= form_input(['type' => 'date', 'name' => 'starts[' . $id . ']', 'value' => set_value('starts[' . $id . ']', $start, false), 'class' => 'form-control', 'aria-label' => 'Início: ' . $holiday->name]) ?></td>
                <td><?= form_input(['type' => 'date', 'name' => 'ends[' . $id . ']', 'value' => set_value('ends[' . $id . ']', $end, false), 'class' => 'form-control', 'aria-label' => 'Término: ' . $holiday->name]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><label><?= form_checkbox(['name' => 'reviewed', 'value' => '1', 'required' => 'required']) ?> Revisei as datas dos feriados selecionados para o ano letivo de destino.</label></p>
    <button class="btn btn-primary" type="submit">Confirmar importação</button>
    <?= anchor('holidays/session/' . $target->session_id, 'Cancelar') ?>
    <?= form_close() ?>
<?php endif; ?>
