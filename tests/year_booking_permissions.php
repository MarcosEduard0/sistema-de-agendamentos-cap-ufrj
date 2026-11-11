<?php
// Run with: C:\xampp\php\php.exe tests/year_booking_permissions.php
 define('BASEPATH', __DIR__);
 define('ADMINISTRATOR', 1);
 require __DIR__ . '/../application/libraries/Userauth.php';
 require __DIR__ . '/../application/components/bookings/exceptions/BookingValidationException.php';
 class CI_Model {}
 require __DIR__ . '/../application/models/Bookings_model.php';
 class PermissionUser extends Userauth {
     public $admin = false;
     public function __construct() {}
     public function is_level($level) { return $this->admin; }
 }
 class TestLoader { public function model($name) {} }
 class TestSessions {
     public $session;
     public $seen;
     public function get_by_date($date, $unused = null) { $this->seen = $date; return $this->session; }
 }
 class TestDb {
     public $calls = 0;
     public function query($sql, $params) { $this->calls++; return $this; }
     public function row() { return null; }
     public function num_rows() { return 0; }
 }
 class TestBookings extends Bookings_model {
     public $userauth; public $sessions_model; public $load; public $db;
     public function __construct() {}
 }
 function check($condition, $message) { if (!$condition) { throw new RuntimeException($message); } }
 $auth = new PermissionUser();
 foreach ([0, 1] as $admin) {
     $auth->admin = (bool) $admin;
     foreach ([0, 1] as $flag) {
         $year = (object) ['is_selectable' => $flag];
         check($auth->can_book_session($year) === (bool) ($admin || $flag), 'Permission matrix failed');
     }
 }
 $auth->admin = false;
 check(!$auth->can_book_session(false), 'Missing year must be denied');
 $model = new TestBookings(); $model->userauth = $auth;
 $model->sessions_model = new TestSessions(); $model->load = new TestLoader(); $model->db = new TestDb();
 $data = ['date' => '2025-02-03', 'session_id' => 999, 'period_id' => 1, 'room_id' => 1];
 foreach ([false, (object) ['is_selectable' => 0]] as $year) {
     $model->sessions_model->session = $year;
     try { $model->validate_booking($data); throw new RuntimeException('Closed year allowed'); }
     catch (app\components\bookings\exceptions\BookingValidationException $e) {}
 }
 check($model->db->calls === 0, 'Closed year reached booking query');
 check($model->sessions_model->seen === $data['date'], 'Must resolve year from date, not posted session_id');
 $model->sessions_model->session = (object) ['is_selectable' => 1];
 check($model->validate_booking($data), 'Open year rejected');
 $auth->admin = true; $model->sessions_model->session = (object) ['is_selectable' => 0];
 check($model->validate_booking($data), 'Administrator rejected');
 echo "PASS: permission matrix, closed/missing years, posted ID bypass, open years and administrator bypass.\n";
