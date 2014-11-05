<?php
/**
 * VERDI CLI scripts.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

define('CLI_SCRIPT', true);
define('INSTALLING', true);

require_once(dirname(__FILE__) . '/../config.php');

// Always run this.
\Cron\Task\Catalog::run();

// Import CALM accessions every 24 hours.
if (!isset($CFG->calm_accessions_run) || (time() - $CFG->calm_accessions_run) > 86400) {
    set_config('calm_accessions_run', time());

    echo "Running Accessions Cron...\n";
    \Cron\Task\Calm\Accessions::run();
}

// Import CALM catalogs every 24 hours.
if (!isset($CFG->calm_catalogs_run) || (time() - $CFG->calm_catalogs_run) > 86400) {
    set_config('calm_catalogs_run', time());

    echo "Running Catalogs Cron...\n";
    \Cron\Task\Calm\Catalogs::run();
}

// Import CALM collections every 24 hours.
if (!isset($CFG->calm_collections_run) || (time() - $CFG->calm_collections_run) > 86400) {
    set_config('calm_collections_run', time());

    echo "Running Collections Cron...\n";
    \Cron\Task\Calm\Collections::run();
}

// Import CALM people every 24 hours.
if (!isset($CFG->calm_people_run) || (time() - $CFG->calm_people_run) > 86400) {
    set_config('calm_people_run', time());

    echo "Running People Cron...\n";
    \Cron\Task\Calm\People::run();
}

// Import CALM subjects every 24 hours.
if (!isset($CFG->calm_subjects_run) || (time() - $CFG->calm_subjects_run) > 86400) {
    set_config('calm_subjects_run', time());

    echo "Running Subjects Cron...\n";
    \Cron\Task\Calm\Subjects::run();
}