<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('../config.php');

$PAGE->set_url('/demo/table.php');
$PAGE->set_title("Rapid Protoyping Framework Demo - Tables");

echo $OUTPUT->header();
echo $OUTPUT->heading("Tables");

$data = $DB->get_records('extract');
$table = new \Presentation\Table();
$table->set_data($data);
echo $table;
?>

<h3>Code</h3>
<pre>
$data = $DB->get_records('extract');
$table = new \Presentation\Table();
$table->set_data($data);
echo $table;
</pre>

<?php
echo $OUTPUT->footer();
