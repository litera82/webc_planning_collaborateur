<?php
/**
* @package  Reghalal
* @subpackage
* @author
* @contributor
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

define ('JELIX_APP_PATH', dirname (__FILE__).DIRECTORY_SEPARATOR); // don't change

define ('JELIX_APP_TEMP_PATH',    realpath(JELIX_APP_PATH.'../../jelix/temp/webcalendar-imcos-info/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_VAR_PATH',     realpath(JELIX_APP_PATH.'./var/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_LOG_PATH',     realpath(JELIX_APP_PATH.'./var/log/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_CONFIG_PATH',  realpath(JELIX_APP_PATH.'./var/config/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_WWW_PATH',     realpath(JELIX_APP_PATH.'./www/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_CMD_PATH',     realpath(JELIX_APP_PATH.'./scripts/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_MODULE_PATH',  realpath(JELIX_APP_PATH.'./modules/').DIRECTORY_SEPARATOR);
define ('NEOV_LIB_PATH',          realpath(JELIX_APP_PATH.'../../jelix/neov/').DIRECTORY_SEPARATOR);

define ('JELIX_LIB_RESPONSE_PATH', JELIX_LIB_CORE_PATH . "response/") ;

include (JELIX_APP_PATH . './var/config/webcalendar.config.ini.php');

?>