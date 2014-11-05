<?php
/**
 * Core Library.
 *
 * @package VERDI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace Data\MySQL;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");


class Migrate
{
    /**
     * Run migrations.
     */
    public function run() {
        global $CFG;

        echo "Current version: {$CFG->version}.\n";

        if (!isset($CFG->version) || $CFG->version < 2014110400) {
            echo "Migrating to version: 2014110400.\n";
            $this->migration_2014110400();
        }

        if ($CFG->version < 2014110500) {
            echo "Migrating to version: 2014110500.\n";
            $this->migration_2014110500();
        }

        echo "Migrated to version: {$CFG->version}.\n";
    }

    /**
     * Initial table layout.
     */
    public function migration_2014110400() {
        global $DB;

        // Create config table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {config} (
              `id` int(11) NOT NULL,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {config}
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `name` (`name`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {config}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create file map table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {file_map} (
              `id` int(11) NOT NULL,
              `fullpath` varchar(1024) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {file_map}
                ADD PRIMARY KEY (`id`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {file_map}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create accession table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_accession} (
              `id` int(11) NOT NULL,
              `accno` int(11) NOT NULL,
              `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
              `value` text COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_accession}
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `u_accno_name` (`accno`,`name`),
                ADD KEY `accno` (`accno`),
                ADD KEY `key` (`name`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_accession}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create collections table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_collections} (
              `id` int(11) NOT NULL,
              `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `description` text COLLATE utf8_unicode_ci NOT NULL,
              `level_t` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `extent_t` varchar(255) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_collections}
                ADD PRIMARY KEY (`id`),
                ADD KEY `code` (`code`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_collections}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create people table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_people} (
              `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `personname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `nationality` varchar(255) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_people}
                ADD PRIMARY KEY (`code`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_people}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create subjects table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_subjects} (
              `id` int(11) NOT NULL,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_subjects}
                ADD PRIMARY KEY (`id`),
                ADD KEY `name` (`name`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_subjects}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        // Create subjects_related table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_subjects_related} (
              `id` int(11) NOT NULL,
              `subj` int(11) NOT NULL,
              `related` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_subjects_related}
                ADD PRIMARY KEY (`id`),
                ADD KEY `subj` (`subj`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_subjects_related}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        set_config('version', 2014110400);
    }

    /**
     * Add catalog table.
     */
    public function migration_2014110500() {
        global $DB;

        // Create catalog table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_catalog} (
              `id` int(11) NOT NULL,
              `refno` varchar(50) NULL,
              `altrefno` varchar(50) NULL,
              `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
              `value` text COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_catalog}
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `u_refnos_name` (`refno`,`altrefno`,`name`),
                ADD KEY `u_refnos` (`refno`,`altrefno`),
                ADD KEY `key` (`name`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_catalog}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        set_config('version', 2014110500);
    }
}