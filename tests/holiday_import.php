<?php
define('BASEPATH', __DIR__);
require __DIR__ . '/../application/libraries/Holiday_import.php';
function check($condition, $message) { if (!$condition) { throw new RuntimeException($message); } }
$import = new Holiday_import();
check($import->propose(new DateTime('2024-02-29'), 1) === '', 'Leap date must require correction');
check($import->propose(new DateTime('2025-12-25'), 1) === '2026-12-25', 'Year proposal');
$target = (object) ['session_id' => 2, 'date_start' => new DateTime('2026-01-01'), 'date_end' => new DateTime('2026-12-31')];
$source = [(object) ['holiday_id' => 1, 'name' => 'Natal']];
$rows = $import->validate($source, ['1'], [1 => '2026-12-25'], [1 => '2026-12-25'], $target);
check(count($rows) === 1 && $rows[0]['name'] === 'Natal' && $rows[0]['session_id'] === 2, 'Validated row');
// Unchecked holidays must not participate in date validation.
$sourceWithChristmas = array_merge($source, [(object) ['holiday_id' => 2, 'name' => 'Natal fora do ano']]);
$selectedRows = $import->validate($sourceWithChristmas, ['1'], [1 => '2026-04-21', 2 => '2027-12-25'], [1 => '2026-04-21', 2 => '2027-12-25'], $target);
check(count($selectedRows) === 1 && $selectedRows[0]['date_start'] === '2026-04-21', 'Unchecked out-of-range holiday blocked import');
$cases = [
    [[], [], []],
    [['99'], [99 => '2026-01-01'], [99 => '2026-01-01']],
    [['1'], [1 => '2026-02-30'], [1 => '2026-03-01']],
    [['1'], [1 => '2026-12-26'], [1 => '2026-12-25']],
    [['1'], [1 => '2025-12-25'], [1 => '2025-12-25']],
];
foreach ($cases as $case) {
    try { $import->validate($source, $case[0], $case[1], $case[2], $target); throw new RuntimeException('Invalid import accepted'); }
    catch (InvalidArgumentException $e) {}
}
class CI_Model {}
require __DIR__ . '/../application/models/Holidays_model.php';
class ImportDb {
    public $db_debug = true, $rows = [], $snapshot = [], $count = 0, $fail = false;
    public function trans_begin() { $this->snapshot = $this->rows; }
    public function trans_commit() { return true; }
    public function trans_rollback() { $this->rows = $this->snapshot; }
    public function trans_status() { return true; }
    public function query($sql, $params) { $this->count = 1; return $this; }
    public function num_rows() { return $this->count; }
    public function get_where($table, $row, $limit) { $this->count = in_array($row, $this->rows, true) ? 1 : 0; return $this; }
    public function insert($table, $row) { if ($this->fail && count($this->rows)) { return false; } $this->rows[] = $row; return true; }
}
class ImportDates { public $calls = 0; public function refresh_holidays($id) { $this->calls++; } }
class ImportModel extends Holidays_model { public $db, $dates_model; public function __construct() {} }
$model = new ImportModel(); $model->db = new ImportDb(); $model->dates_model = new ImportDates();
$result = $model->import_reviewed(2, [$rows[0], $rows[0]]);
check($result === ['inserted' => 1, 'skipped' => 1], 'Duplicates in same batch');
check($model->import_reviewed(2, $rows) === ['inserted' => 0, 'skipped' => 1], 'Repeated import');
check($model->dates_model->calls === 1, 'Refresh once after insert');
$model->db = new ImportDb(); $model->db->fail = true;
$second = $rows[0]; $second['name'] = 'Outro';
try { $model->import_reviewed(2, [$rows[0], $second]); throw new RuntimeException('Failed insert accepted'); }
catch (RuntimeException $e) { check($e->getMessage() === 'Insert failed.', 'Unexpected error'); }
check(!$model->db->rows && $model->db->db_debug, 'Rollback and debug restoration');
echo "PASS: dates, leap year, source membership, range, reversed dates, empty selection, duplicates, repeated import and rollback (simulated DB).\n";
