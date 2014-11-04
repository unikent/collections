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

defined("VERDI_INTERNAL") || die("This page cannot be accessed directly.");

define('PARAM_ALPHA',    'alpha');
define('PARAM_ALPHAEXT', 'alphaext');
define('PARAM_ALPHANUM', 'alphanum');
define('PARAM_ALPHANUMEXT', 'alphanumext');
define('PARAM_BOOL',     'bool');
define('PARAM_EMAIL',   'email');
define('PARAM_FILE',   'file');
define('PARAM_FLOAT',  'float');
define('PARAM_INT',      'int');
define('PARAM_NOTAGS',   'notags');
define('PARAM_PATH',     'path');
define('PARAM_RAW', 'raw');
define('PARAM_RAW_TRIMMED', 'raw_trimmed');
define('PARAM_SAFEDIR',  'safedir');
define('PARAM_SAFEPATH',  'safepath');
define('PARAM_SEQUENCE',  'sequence');
define('PARAM_TEXT',  'text');


/**
 * Abort execution by throwing of a general exception,
 * default exception handler displays the error message in most cases.
 */
function print_error($message) {
    die($message);
}

/**
 * Returns a particular value for the named variable, taken from
 * POST or GET.  If the parameter doesn't exist then an error is
 * thrown because we require this variable.
 *
 * This function should be used to initialise all required values
 * in a script that are based on parameters.  Usually it will be
 * used like this:
 *    $id = required_param('id', PARAM_INT);
 *
 * @param string $parname the name of the page parameter we want
 * @param string $type expected type of parameter
 * @return mixed
 * @throws Exception
 */
function required_param($parname, $type) {
    if (func_num_args() != 2 or empty($parname) or empty($type)) {
        $err = 'required_param() requires $parname and $type to be specified';
        $err .= ' (parameter: ' . $parname . ')';
        throw new Exception($err);
    }
    // POST has precedence.
    if (isset($_POST[$parname])) {
        $param = $_POST[$parname];
    } else if (isset($_GET[$parname])) {
        $param = $_GET[$parname];
    } else {
        print_error('Could not find required parameter: ' . $parname);
    }

    return clean_param($param, $type);
}


/**
 * Used by {@link optional_param()} and {@link required_param()} to
 * clean the variables and/or cast to specific types, based on
 * an options field.
 * <code>
 * $course->format = clean_param($course->format, PARAM_ALPHA);
 * $selectedgradeitem = clean_param($selectedgradeitem, PARAM_INT);
 * </code>
 *
 * @param mixed $param the variable we are cleaning
 * @param string $type expected format of param after cleaning.
 * @return mixed
 * @throws coding_exception
 */
function clean_param($param, $type) {
    if (is_array($param)) {
        throw new Exception('clean_param() can not process arrays, please use clean_param_array() instead.');
    } else if (is_object($param)) {
        if (method_exists($param, '__toString')) {
            $param = $param->__toString();
        } else {
            throw new Exception('clean_param() can not process objects, please use clean_param_array() instead.');
        }
    }

    switch ($type) {
        case PARAM_RAW:
            // No cleaning at all.
            $param = fix_utf8($param);
            return $param;

        case PARAM_RAW_TRIMMED:
            // No cleaning, but strip leading and trailing whitespace.
            $param = fix_utf8($param);
            return trim($param);

        case PARAM_INT:
            // Convert to integer.
            return (int)$param;

        case PARAM_FLOAT:
            // Convert to float.
            return (float)$param;

        case PARAM_ALPHA:
            // Remove everything not `a-z`.
            return preg_replace('/[^a-zA-Z]/i', '', $param);

        case PARAM_ALPHAEXT:
            // Remove everything not `a-zA-Z_-` (originally allowed "/" too).
            return preg_replace('/[^a-zA-Z_-]/i', '', $param);

        case PARAM_ALPHANUM:
            // Remove everything not `a-zA-Z0-9`.
            return preg_replace('/[^A-Za-z0-9]/i', '', $param);

        case PARAM_ALPHANUMEXT:
            // Remove everything not `a-zA-Z0-9_-`.
            return preg_replace('/[^A-Za-z0-9_-]/i', '', $param);

        case PARAM_SEQUENCE:
            // Remove everything not `0-9,`.
            return preg_replace('/[^0-9,]/i', '', $param);

        case PARAM_BOOL:
            // Convert to 1 or 0.
            $tempstr = strtolower($param);
            if ($tempstr === 'on' or $tempstr === 'yes' or $tempstr === 'true') {
                $param = 1;
            } else if ($tempstr === 'off' or $tempstr === 'no'  or $tempstr === 'false') {
                $param = 0;
            } else {
                $param = empty($param) ? 0 : 1;
            }
            return $param;

        case PARAM_NOTAGS:
            // Strip all tags.
            $param = fix_utf8($param);
            return strip_tags($param);

        case PARAM_TEXT:
            // Leave only tags needed for multilang.
            $param = fix_utf8($param);
            // If the multilang syntax is not correct we strip all tags because it would break xhtml strict which is required
            // for accessibility standards please note this cleaning does not strip unbalanced '>' for BC compatibility reasons.
            do {
                if (strpos($param, '</lang>') !== false) {
                    // Old and future mutilang syntax.
                    $param = strip_tags($param, '<lang>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</lang>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<lang lang="[a-zA-Z0-9_-]+"\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;

                } else if (strpos($param, '</span>') !== false) {
                    // Current problematic multilang syntax.
                    $param = strip_tags($param, '<span>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</span>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<span(\s+lang="[a-zA-Z0-9_-]+"|\s+class="multilang"){2}\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;
                }
            } while (false);
            // Easy, just strip all tags, if we ever want to fix orphaned '&' we have to do that in format_string().
            return strip_tags($param);

        case PARAM_SAFEDIR:
            // Remove everything not a-zA-Z0-9_- .
            return preg_replace('/[^a-zA-Z0-9_-]/i', '', $param);

        case PARAM_SAFEPATH:
            // Remove everything not a-zA-Z0-9/_- .
            return preg_replace('/[^a-zA-Z0-9\/_-]/i', '', $param);

        case PARAM_FILE:
            // Strip all suspicious characters from filename.
            $param = fix_utf8($param);
            $param = preg_replace('~[[:cntrl:]]|[&<>"`\|\':\\\\/]~u', '', $param);
            if ($param === '.' || $param === '..') {
                $param = '';
            }
            return $param;

        case PARAM_PATH:
            // Strip all suspicious characters from file path.
            $param = fix_utf8($param);
            $param = str_replace('\\', '/', $param);

            // Explode the path and clean each element using the PARAM_FILE rules.
            $breadcrumb = explode('/', $param);
            foreach ($breadcrumb as $key => $crumb) {
                if ($crumb === '.' && $key === 0) {
                    // Special condition to allow for relative current path such as ./currentdirfile.txt.
                } else {
                    $crumb = clean_param($crumb, PARAM_FILE);
                }
                $breadcrumb[$key] = $crumb;
            }
            $param = implode('/', $breadcrumb);

            // Remove multiple current path (./././) and multiple slashes (///).
            $param = preg_replace('~//+~', '/', $param);
            $param = preg_replace('~/(\./)+~', '/', $param);
            return $param;

        case PARAM_EMAIL:
            $param = fix_utf8($param);
            if (validate_email($param)) {
                return $param;
            } else {
                return '';
            }

        default:
            // Doh! throw error, switched parameters in optional_param or another serious problem.
            print_error("unknownparamtype : " . $type);
    }
}

/**
 * Makes sure the data is using valid utf8, invalid characters are discarded.
 *
 * Note: this function is not intended for full objects with methods and private properties.
 *
 * @param mixed $value
 * @return mixed with proper utf-8 encoding
 */
function fix_utf8($value) {
    if (is_null($value) or $value === '') {
        return $value;

    } else if (is_string($value)) {
        if ((string)(int)$value === $value) {
            // Shortcut.
            return $value;
        }
        // No null bytes expected in our data, so let's remove it.
        $value = str_replace("\0", '', $value);

        // Note: this duplicates min_fix_utf8() intentionally.
        static $buggyiconv = null;
        if ($buggyiconv === null) {
            $buggyiconv = (!function_exists('iconv') or @iconv('UTF-8', 'UTF-8//IGNORE', '100'.chr(130).'€') !== '100€');
        }

        if ($buggyiconv) {
            if (function_exists('mb_convert_encoding')) {
                $subst = mb_substitute_character();
                mb_substitute_character('');
                $result = mb_convert_encoding($value, 'utf-8', 'utf-8');
                mb_substitute_character($subst);

            } else {
                // Warn admins on admin/index.php page.
                $result = $value;
            }

        } else {
            $result = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
        }

        return $result;

    } else if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = fix_utf8($v);
        }
        return $value;

    } else if (is_object($value)) {
        // Do not modify original.
        $value = clone($value);
        foreach ($value as $k => $v) {
            $value->$k = fix_utf8($v);
        }
        return $value;

    } else {
        // This is some other type, no utf-8 here.
        return $value;
    }
}

/**
 * Validates an email to make sure it makes sense.
 *
 * @param string $address The email address to validate.
 * @return boolean
 */
function validate_email($address) {

    return (preg_match('#^[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+'.
                 '(\.[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+)*'.
                  '@'.
                  '[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
                  '[-!\#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$#',
                  $address));
}

/**
 * Set config.
 */
function set_config($name, $value) {
    global $DB;

    return $DB->update_or_insert('config', array('name' => $name), array(
        'name' => $name,
        'value' => $value
    ));
}

/**
 * Get config.
 */
function get_config($name) {
    global $DB;

    return $DB->get_field('config', 'value', array(
        'name' => $name
    ));
}
