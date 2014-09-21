<?php
/**
 * Rapid Prototyping Framework in PHP.
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

require_once('../config.php');

$PAGE->set_url('/demo/form.php');
$PAGE->set_title("Rapid Protoyping Framework Demo - Cache");

echo $OUTPUT->header();
echo $OUTPUT->heading("Cache");

$hits = $CACHE->get('hits');
if (!$hits) {
	$hits = 0;
}
echo "Hits in cache lifetime: $hits";
$CACHE->set('hits', $hits + 1);
?>

<h3>Code</h3>
<pre>
$hits = $CACHE->get('hits');
if (!$hits) {
	$hits = 0;
}
echo "Hits in cache lifetime: $hits";
$CACHE->set('hits', $hits + 1);
</pre>

<?php
echo $OUTPUT->footer();
