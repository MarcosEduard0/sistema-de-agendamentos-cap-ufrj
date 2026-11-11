<?php
if (!$this->session->userdata('apply_week_token')) {
    $this->session->set_userdata('apply_week_token', bin2hex(random_bytes(32)));
}
echo form_open('sessions/apply_week', ['class' => 'cssform-stacked'], [
    'session_id' => $session->session_id,
    'apply_week_token' => $this->session->userdata('apply_week_token'),
]);
?>
<fieldset>
    <legend>Aplicação em massa</legend>
    <p>Escolha a semana (trimestre) e onde aplicá-la. Apenas a classificação das datas será alterada; os agendamentos existentes serão mantidos.</p>
    <div class="form-group">
        <label for="apply_week_id">Semana / trimestre</label>
        <?php
        $options = ['' => 'Selecionar uma semana...'];
        foreach (($weeks ?: []) as $week) { $options[$week->week_id] = $week->name; }
        echo form_dropdown('week_id', $options, '', ['id' => 'apply_week_id', 'class' => 'form-control', 'required' => 'required']);
        ?>
    </div>
    <div class="form-group">
        <label for="apply_scope">Aplicar em</label>
        <?= form_dropdown('apply_scope', ['range' => 'Um intervalo de datas', 'all' => 'Todo o ano letivo'], 'range', ['id' => 'apply_scope', 'class' => 'form-control', 'up-switch' => '.apply-week-range']) ?>
    </div>
    <div class="apply-week-range" up-show-for="range">
        <div class="form-group">
            <label for="apply_start">Data de início</label>
            <?= form_input(['type' => 'date', 'name' => 'apply_start', 'id' => 'apply_start', 'class' => 'form-control', 'min' => $session->date_start->format('Y-m-d'), 'max' => $session->date_end->format('Y-m-d')]) ?>
        </div>
        <div class="form-group">
            <label for="apply_end">Data de fim</label>
            <?= form_input(['type' => 'date', 'name' => 'apply_end', 'id' => 'apply_end', 'class' => 'form-control', 'min' => $session->date_start->format('Y-m-d'), 'max' => $session->date_end->format('Y-m-d')]) ?>
        </div>
        <p class="form-text text-muted">As duas datas estão incluídas. Para semanas completas, informe a segunda-feira inicial e o domingo final. As datas fora do intervalo permanecem como estão.</p>
    </div>
    <button type="submit" class="btn btn-primary">Aplicar</button>
</fieldset>
<?= form_close() ?>
