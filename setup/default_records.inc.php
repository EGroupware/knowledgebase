<?php
/**
 * EGroupware - Knowledgebase - setup definitions
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <rb-AT-egroupware.org>
 * @package knowledgebase
 * @subpackage setup
 * @copyright (c) 2019 by Ralf Becker <rb-AT-egroupware.org>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 */

// give Default group rights for Knowledgebase app
$defaultgroup = $GLOBALS['egw_setup']->add_account('Default', 'Default', 'Group', false, false);
$GLOBALS['egw_setup']->add_acl('knowledgebase', 'run', $defaultgroup);
