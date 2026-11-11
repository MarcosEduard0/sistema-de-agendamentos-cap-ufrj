<?php
define('BASEPATH', __DIR__);
class CI_Model {}
require __DIR__ . '/../application/models/Dates_model.php';
class RangeSessions {
    public function get($id) {
        return $id == 1 ? (object) ['date_start' => new DateTime('2026-02-01'), 'date_end' => new DateTime('2026-12-18')] : false;
    }
}
class RangeDb {
    public $calls = [];
    public function query($sql, $params) { $this->calls[] = [$sql, $params]; return true; }
}
class RangeDates extends Dates_model {
    public $sessions_model, $db;
    public function __construct() { $this->sessions_model = new RangeSessions(); $this->db = new RangeDb(); }
}
function check($ok, $message) { if (!$ok) { throw new RuntimeException($message); } }
$model = new RangeDates();
check($model->apply_week(1, 2, '2026-02-23', '2026-04-05'), 'Valid range');
list($sql, $params) = $model->db->calls[0];
check(strpos($sql, '`date` >= ? AND `date` <= ?') !== false, 'Inclusive range missing');
check($params === [2,1,'2026-02-23','2026-04-05'], 'Unexpected parameters');
check(strpos($sql, 'session_id = ?') !== false && strpos($sql, 'SET week_id = ?') !== false, 'Year scope and week-only update');
foreach ([['2026-04-05','2026-02-23'], ['2026-02-30','2026-04-05'], ['2026-01-31','2026-04-05'], ['2026-02-23','2026-12-19'], ['', '2026-04-05'], [null,'2026-04-05']] as $range) {
    $before = count($model->db->calls);
    try { $model->apply_week(1,2,$range[0],$range[1]); throw new RuntimeException('Invalid range accepted'); }
    catch (InvalidArgumentException $e) {}
    check(count($model->db->calls) === $before, 'Invalid range reached DB');
}
check($model->apply_week(1,2,'2026-04-05','2026-04-05'), 'Single date');
check($model->apply_week(1,2), 'Whole year');
$last = end($model->db->calls);
check($last[1] === [2,1] && strpos($last[0], '`date` >=') === false, 'Whole year changed');
check(!$model->apply_week(999,2), 'Missing year');
echo "PASS: inclusive range, scoped SQL, invalid/reversed/out-of-year dates rejected before DB, single day and whole year (simulated DB).\n";
