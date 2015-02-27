<?php
/**
 * Core Library.
 *
 * @package SCAPI
 * @subpackage lib
 * @version 2.0
 * @author Skylar Kelty <S.Kelty@kent.ac.uk>
 * @copyright University of Kent
 */

namespace SCAPI\Service;

defined("SCAPI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Service
{
    /**
     * Do the UNIX double-fork dance.
     */
    private final function fork() {
        // Fork once.
        if (pcntl_fork()) {
            // Return the parent.
            return;
        }

        // Clear up.
        if (ob_get_length() !== false) {
            ob_end_clean();
        }
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);

        // Register shutdown function for new processes.
        register_shutdown_function(function() {
            $pid = posix_getpid();
            posix_kill($pid, SIGHUP);
        });

        // Make this a session leader.
        if (posix_setsid() < 0) {
            return;
        }

        // Double fork magic.
        if (pcntl_fork()) {
            // Return the parent.
            return;
        }

        global $DB;
        $DB->reset();
    }

    /**
     * Run the service.
     */
    public final function run($data) {
        global $CFG;

        if (!$CFG->developer_mode) {
            $this->fork();
        }

        $this->perform($data);
    }

    protected abstract function perform($data);
}
