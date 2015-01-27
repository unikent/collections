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
$infield = optional_param('field', 'id', PARAM_ALPHAEXT);
$invalue = optional_param('value', '', PARAM_RAW);
$recordid = optional_param('recordid', '', PARAM_RAW);

$menu = array(
    '' => 'Home',
    'catalog' => 'Catalog',
    'collections' => 'Collections',
    'people' => 'People'
);

$tabtables = array(
    'catalog' => 'calm_catalog',
    'collections' => 'calm_collections',
    'people' => 'calm_people'
);

$tabtableskey = array(
    'catalog' => 'id',
    'collections' => 'id',
    'people' => 'code'
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

$validfields = \Verdi\Models\Catalog::get_field_list();
if ($tab == 'collections') {
    $validfields = \Verdi\Models\Collection::get_field_list();
}
if ($tab == 'people') {
    $validfields = \Verdi\Models\People::get_field_list();
}

if (!empty($tab)) {
    $options = '';
    foreach ($validfields as $field) {
        $upperfield = $field == 'id' ? 'ID' : ucwords($field);
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
}

if ($tab == 'catalog') {
    if (isset($infield) && in_array($infield, $validfields) && !empty($invalue)) {
        $catalogs = $DB->get_records_sql("SELECT * FROM {calm_catalog} WHERE $infield LIKE :val", array(
            'val' => "%{$invalue}%"
        ));

        $count = count($catalogs);
        echo "<div class=\"row\"><div class=\"col-lg-12\"><i>$count matching results!</i></div></div>";

        if (!empty($catalogs)) {
            echo '<table class="table"><tr><th>ID</th><th>RefNo</th><th>Author</th><th>Title</th></tr>';
            foreach ($catalogs as $catalog) {
                echo "<tr><td><a href=\"?tab=$tab&recordid={$catalog->id}\">{$catalog->id}</a></td><td>{$catalog->refno}</td><td>{$catalog->artist}</td><td>{$catalog->title}</td></tr>";
            }
            echo '</table><br />';
        } else {
            echo '<br /><p>No results!</p>';
        }
    }
}

if ($tab == 'collections') {
    if (isset($infield) && in_array($infield, $validfields) && !empty($invalue)) {
        $collections = $DB->get_records_sql("SELECT * FROM {calm_collections} WHERE $infield LIKE :val", array(
            'val' => "%{$invalue}%"
        ));

        $count = count($collections);
        echo "<div class=\"row\"><div class=\"col-lg-12\"><i>$count matching results!</i></div></div>";

        if (!empty($collections)) {
            echo '<table class="table"><tr><th>ID</th><th>Code</th><th>Title</th></tr>';
            foreach ($collections as $collection) {
                echo "<tr><td><a href=\"?tab=$tab&recordid={$collection->id}\">{$collection->id}</a></td><td>{$collection->RefNo}</td><td>{$collection->Title}</td></tr>";
            }
            echo '</table><br />';
        } else {
            echo '<br /><p>No results!</p>';
        }
    }
}

if ($tab == 'people') {
    if (isset($infield) && in_array($infield, $validfields) && !empty($invalue)) {
        $people = $DB->get_records_sql("SELECT * FROM {calm_people} WHERE $infield LIKE :val", array(
            'val' => "%{$invalue}%"
        ));

        $count = count($people);
        echo "<div class=\"row\"><div class=\"col-lg-12\"><i>$count matching results!</i></div></div>";

        if (!empty($people)) {
            echo '<table class="table"><tr><th>Code</th><th>Name</th></tr>';
            foreach ($people as $person) {
                echo "<tr><td><a href=\"?tab=$tab&recordid={$person->code}\">{$person->code}</a></td><td>{$person->fullname}</td></tr>";
            }
            echo '</table><br />';
        } else {
            echo '<br /><p>No results!</p>';
        }
    }
}

if (!empty($recordid) && isset($tabtables[$tab])) {
    $idkey = $tabtableskey[$tab];

    $record = $DB->get_record($tabtables[$tab], array(
        $idkey => $recordid
    ));

    echo '<table class="table">';
    foreach ((array)$record as $k => $v) {
        echo "<tr><th>$k</th><td>$v</td></tr>";
    }
    echo '</table><br />';
}

echo $OUTPUT->footer();
