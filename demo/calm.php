<?php
/**
 * Index Page.
 *
 * @package VERDI
 * @subpackage Demo
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

require_once(dirname(__FILE__) . '/../config.php');

$PAGE->set_url('/demo/calm.php');
$PAGE->set_title("Calm Data Explorer");

echo $OUTPUT->header();
echo $OUTPUT->heading("Calm Data Explorer");

$tab = optional_param('tab', '', PARAM_ALPHA);
$infield = optional_param('field', 'title', PARAM_ALPHAEXT);
$invalue = optional_param('value', '', PARAM_RAW);

$menu = array(
    '' => 'Home',
    'catalog' => 'Catalog',
    'collections' => 'Collections'
);

echo '<div class="row"><ul class="nav nav-pills" role="tablist">';
foreach ($menu as $query => $item) {
    $active = $tab == $query ? ' class="active"' : '';
    echo "<li role=\"presentation\"$active><a href=\"/demo/calm.php?tab={$query}\">{$item}</a></li>";
}
echo '</ul></div><br />';

if (empty($tab)) {
    echo '<p>Here, you can browse the CALM data.</p>';
}

if ($tab == 'catalog') {

    $fields = \Models\Catalog::get_field_list();
    $options = '';
    foreach ($fields as $field) {
        $upperfield = ucwords($field);
        $selected = $field == $infield ? ' selected="selected"' : '';
        $options .= "<option value=\"$field\"$selected>$upperfield</option>";
    }

    echo <<<HTML
        <form role="form" action="/demo/calm.php" method="GET">
            <input type="hidden" name="tab" value="$tab">
            <div class="row">
              <div class="col-lg-12">
                <div class="input-group">
                  <div class="input-group-addon">
                    <select name="field">
                      $options
                    </select>
                  </div>
                  <input type="text" name="value" class="form-control" placeholder="">
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">Search!</button>
                  </div>
                </div>
              </div>
            </div>
        </form>
HTML;

    if (isset($infield) && in_array($infield, $fields) && !empty($invalue)) {
        $catalogs = $DB->get_records_sql("SELECT * FROM {calm_catalog} WHERE $infield LIKE :val", array(
            'val' => "%{$invalue}%"
        ));

        if (!empty($catalogs)) {
            foreach ($catalogs as $catalog) {
                echo '<table class="table">';
                foreach ((array)$catalog as $k => $v) {
                    echo "<tr><th>$k</th><td>$v</td></tr>";
                }
                echo '</table><br />';
            }
        } else {
            echo '<br /><p>No results!</p>';
        }
    }
}

if ($tab == 'collections') {

}

echo $OUTPUT->footer();
