<?php
/**
 * EGroupware - Knowledgebase - setup definitions
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <rb-AT-egroupware.org>
 * @package knowledgebase
 * @subpackage setup
 * @copyright (c) 2023-24 by Ralf Becker <rb-AT-egroupware.org>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 */

$setup_info['knowledgebase']['name']      = 'knowledgebase';
$setup_info['knowledgebase']['version']   = '26.1';
$setup_info['knowledgebase']['app_order'] = 5;
$setup_info['knowledgebase']['enable']    = 1;
$setup_info['knowledgebase']['tables']    = array('egw_knowledgebase','egw_knowledgebase_extra');
$setup_info['knowledgebase']['index']     = 'knowledgebase.EGroupware\\Knowledgebase\\Ui.index&ajax=true';

$setup_info['knowledgebase']['author'] =
$setup_info['knowledgebase']['maintainer'] = array(
	'name'  => 'Ralf Becker',
	'email' => 'rb@egroupware.org',
);
$setup_info['knowledgebase']['license']  = 'GPL';
$setup_info['knowledgebase']['description'] =
'Knowledgebase: browse, search and edit documents organized in categories.';

// Hooks we implement
$setup_info['knowledgebase']['hooks']['search_link'] = 'EGroupware\\Knowledgebase\\Hooks::search_link';
$setup_info['knowledgebase']['hooks']['admin'] = 'EGroupware\\Knowledgebase\\Hooks::all_hooks';
$setup_info['knowledgebase']['hooks']['sidebox_menu'] = 'EGroupware\\Knowledgebase\\Hooks::all_hooks';

/* Dependencies for this app to work */
$setup_info['knowledgebase']['depends'][] = array(
	 'appname' => 'api',
	 'versions' => Array('26.1')
);