<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Date proposals are deliberately not a national holiday calendar. */
class Holiday_import
{
    public function date($value)
    {
        if (!is_string($value)) { return false; }
        $date = DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value ? $date : false;
    }

    public function propose($date, $offset)
    {
        $value = sprintf('%04d-%s', (int) $date->format('Y') + $offset, $date->format('m-d'));
        return $this->date($value) ? $value : '';
    }

    public function validate($source, $selected, $starts, $ends, $target)
    {
        if (!is_array($selected) || !$selected || !is_array($starts) || !is_array($ends)) {
            throw new InvalidArgumentException('Selecione ao menos um feriado e revise suas datas.');
        }
        $known = [];
        foreach ($source as $holiday) { $known[(string) $holiday->holiday_id] = $holiday; }
        $rows = [];
        foreach (array_unique($selected, SORT_REGULAR) as $id) {
            if (!is_scalar($id) || !isset($known[(string) $id])) {
                throw new InvalidArgumentException('Um feriado selecionado não pertence ao ano de origem.');
            }
            $start = $this->date(isset($starts[$id]) ? $starts[$id] : null);
            $end = $this->date(isset($ends[$id]) ? $ends[$id] : null);
            if (!$start || !$end || $end < $start) {
                throw new InvalidArgumentException('Revise as datas de ' . $known[$id]->name . ': informe datas válidas, com o término igual ou posterior ao início.');
            }
            if ($start < $target->date_start || $end > $target->date_end) {
                throw new InvalidArgumentException(sprintf(
                    '%s está fora do período do ano letivo, que vai de %s a %s. Ajuste as datas ou desmarque este feriado para continuar.',
                    $known[$id]->name, $target->date_start->format('d/m/Y'), $target->date_end->format('d/m/Y')
                ));
            }
            $rows[] = ['session_id' => $target->session_id, 'name' => $known[$id]->name,
                'date_start' => $start->format('Y-m-d'), 'date_end' => $end->format('Y-m-d')];
        }
        return $rows;
    }
}
