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

namespace Service;

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

abstract class Service
{
    /** Protected data */
    private $_data;

    public function __construct($data) {
        $this->_data = $data;
    }

    /**
     * Do the UNIX double-fork dance.
     */
    public function run() {
        // Fork once.
        if (pcntl_fork()) {
            // Return the parent.
            return;
        }

        // Clear up.
        ob_end_clean();
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

        $this->perform($this->_data);
    }

    /**
     * Do what this service is meant to do.
     */
    public abstract function perform($data);
}
