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

namespace Verdi\Data\MySQL;

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

        if ($CFG->version < 2015012700) {
            echo "Migrating to version: 2015012700.\n";
            $this->migration_2015012700();
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
              `name` varchar(255) COLLATE utf8_unicode_ci NULL,
              `value` varchar(255) COLLATE utf8_unicode_ci NULL
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

        // Create accession table.
        $DB->execute("
            CREATE TABLE IF NOT EXISTS {calm_accession} (
                `id` int(11) NOT NULL,
                `AccessionCategory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `AccessStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `AcqTerms` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `AdminHistory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Copies` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Copyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Created` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Creator` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `CustodialHistory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `DepositorId` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Extent` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Modified` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Modifier` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `PublnNote` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `RecordID` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `RecordType` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Repository` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `DataSet` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL
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
                `id`  int(11) COLLATE utf8_unicode_ci NOT NULL AUTO_INCREMENT,
                `RecordType`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `IDENTITY`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Level`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Repository`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RefNo`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `AltRefNo`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `AccNo`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Extent`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Artist`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Title`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Date`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText7`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped7`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped8`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Publisher`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped6`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `PubDate`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `CONTENT`  text COLLATE utf8_unicode_ci NULL,
                `Description`  text COLLATE utf8_unicode_ci NULL,
                `UserText4`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Format`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Technique`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText6`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText1`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped2`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped3`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Series`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Thumbnail`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Notes`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped5`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Appraisal`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Arrangement`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Ignition`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText5`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped9`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText3`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText8`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserText9`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ACCESS`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Location`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ClosedUntil`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `AccessConditions`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Copyright`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Suspension`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ChassisNo`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `AccessStatus`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `PhysicalDescription`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `CONSERVATIONREQUIRED`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ConservationPriority`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ALLIED_MATERIALS`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Originals`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Copies`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RelatedMaterial`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RelatedRecord`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `URL`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `CATALOGUE_STATUS`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `CatalogueStatus`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `CountryCode`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RepositoryCode`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `EHFDPublisher`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `EHPDLanguage`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `ADMIN_DETAILS`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RecordID`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Creator`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Created`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Modifier`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `Modified`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `UserWrapped4`  varchar(255) COLLATE utf8_unicode_ci NULL,
                `RCN`  varchar(255) COLLATE utf8_unicode_ci NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
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
              `code` varchar(255) COLLATE utf8_unicode_ci NULL,
              `personname` varchar(255) COLLATE utf8_unicode_ci NULL,
              `fullname` varchar(255) COLLATE utf8_unicode_ci NULL,
              `nationality` varchar(255) COLLATE utf8_unicode_ci NULL
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
              `name` varchar(255) COLLATE utf8_unicode_ci NULL
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
                `alsoPublishedIn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `alsoPublishedOn` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `artist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `copyrightContactDetails` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `copyrightHolder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `date` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `dayOfYear` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `displayCopyright` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `displayRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `embeddedText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `endDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `format` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `genre` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `impliedText` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `isSingleDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `level` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `locationOfArtwork` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `medium` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `personCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `publisher` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `relatedPersonCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `relatedRecord` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `relatestocartoon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `restrictImageDisplay` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `series` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `series_s` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `startDate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `technique` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
                `webtab` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL
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

    /**
     * Add bcad file mapping table.
     */
    public function migration_2015012700() {
        global $DB;

        $DB->execute("DROP TABLE IF EXISTS {file_map};");

        $DB->execute("
            CREATE TABLE IF NOT EXISTS {bcad_files} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `recordid` varchar(50) COLLATE utf8_bin NOT NULL,
                `filename` text COLLATE utf8_bin NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        set_config('version', 2015012700);
    }
}
