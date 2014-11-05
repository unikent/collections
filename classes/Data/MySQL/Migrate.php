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
                `AccessionCategory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `AccessStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `AcqTerms` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `AdminHistory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Copies` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Copyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Created` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Creator` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `CustodialHistory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `DepositorId` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Extent` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Modifier` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `PublnNote` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `RecordID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `RecordType` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Repository` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `DataSet` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_accession}
                ADD PRIMARY KEY (`id`);
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
                `alsoPublishedIn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `alsoPublishedOn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `copyrightContactDetails` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `copyrightHolder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `dayOfYear` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `displayCopyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `displayRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `embeddedText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `endDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `format` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `genre` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `impliedText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `isSingleDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `level` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `locationOfArtwork` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `medium` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `personCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `publisher` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `relatedPersonCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `relatedRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `relatestocartoon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `restrictImageDisplay` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `series` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `series_s` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `startDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `technique` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `webtab` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // Indexes.
        $DB->execute("
            ALTER TABLE {calm_catalog}
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `u_refnos` (`refno`,`altrefno`);
        ");

        // Auto increment.
        $DB->execute("
            ALTER TABLE {calm_catalog}
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

        set_config('version', 2014110500);
    }
}
