<?php
/**
 * SCAPI CLI scripts.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

define('CLI_SCRIPT', true);

require_once(dirname(__FILE__) . '/../config.php');

echo "Running File Map cron...\n";
\SCAPI\Cron\Task\Files\Cartoons::run();

die();

// Run file mappings every 4 hours.
if (!isset($CFG->cartoons_filemap_run) || (time() - $CFG->cartoons_filemap_run) > 14400) {
    set_config('cartoons_filemap_run', time());

    echo "Running File Map cron...\n";
    \SCAPI\Cron\Task\Files\Cartoons::run();
}

// Import CALM accessions every 24 hours.
if (!isset($CFG->calm_accessions_run) || (time() - $CFG->calm_accessions_run) > 86400) {
    set_config('calm_accessions_run', time());

    echo "Running Accessions Cron...\n";
    \SCAPI\Cron\Task\Calm\Accessions::run();

    sleep(10);
}

// Import CALM catalogs every 24 hours.
if (!isset($CFG->calm_catalogs_run) || (time() - $CFG->calm_catalogs_run) > 86400) {
    set_config('calm_catalogs_run', time());

    echo "Running Catalogs Cron...\n";
    \SCAPI\Cron\Task\Calm\Catalogs::run();

    sleep(10);
}

// Import CALM collections every 24 hours.
if (!isset($CFG->calm_collections_run) || (time() - $CFG->calm_collections_run) > 86400) {
    set_config('calm_collections_run', time());

    echo "Running Collections Cron...\n";
    \SCAPI\Cron\Task\Calm\Collections::run();

    sleep(10);
}

// Import CALM people every 24 hours.
if (!isset($CFG->calm_people_run) || (time() - $CFG->calm_people_run) > 86400) {
    set_config('calm_people_run', time());

    echo "Running People Cron...\n";
    \SCAPI\Cron\Task\Calm\People::run();

    sleep(10);
}

// Import CALM subjects every 24 hours.
if (!isset($CFG->calm_subjects_run) || (time() - $CFG->calm_subjects_run) > 86400) {
    set_config('calm_subjects_run', time());

    echo "Running Subjects Cron...\n";
    \SCAPI\Cron\Task\Calm\Subjects::run();

    sleep(10);
}

// SOLR import every 12 hours.
if (!isset($CFG->solr_cartoons_run) || (time() - $CFG->solr_cartoons_run) > 43200) {
    set_config('solr_cartoons_run', time());

    echo "Running Cartoons SOLR Import Cron...\n";
    \SCAPI\Cron\Task\SOLR\Cartoons::run();

    sleep(10);
}
