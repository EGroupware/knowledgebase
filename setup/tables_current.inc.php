<?php
/**
 * EGroupware - Knowledgebase - Database schema
 *
 * @link http://www.egroupware.org
 * @package knowledgebase
 * @subpackage setup
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 */

$phpgw_baseline = array(
	'egw_knowledgebase' => array(
		'fd' => array(
			'kb_id' => array('type' => 'auto','nullable' => False),
			'kb_title' => array('type' => 'varchar','precision' => '255','nullable' => False),
			'cat_id' => array('type' => 'int','meta' => 'category','precision' => '4','default' => 0),
			'kb_content_type' => array('type' => 'varchar','precision' => '16','nullable' => False,'default' => 'markdown','comment' => "'markdown' or 'html'"),
			'kb_content' => array('type' => 'longtext'),
			'kb_summary' => array('type' => 'varchar','precision' => '500'),
			'kb_status' => array('type' => 'varchar','precision' => '16','nullable' => False,'default' => 'published','comment' => "'draft', 'published' or 'archived'"),
			'account_id' => array('type' => 'int','meta' => 'account','precision' => '4','nullable' => False,'comment' => 'owner'),
			'kb_created' => array('type' => 'int','meta' => 'timestamp','precision' => '8','nullable' => False),
			'kb_modified' => array('type' => 'int','meta' => 'timestamp','precision' => '8','nullable' => False),
			'kb_modifier' => array('type' => 'int','meta' => 'account','precision' => '4','nullable' => False,'comment' => 'last editor'),
			'kb_views' => array('type' => 'int','precision' => '4','nullable' => False,'default' => 0)
		),
		'pk' => array('kb_id'),
		'fk' => array(),
		'ix' => array('cat_id','account_id','kb_status'),
		'uc' => array()
	),
	'egw_knowledgebase_extra' => array(
		'fd' => array(
			'kb_id' => array('type' => 'int','precision' => '4','nullable' => False),
			'kb_extra_name' => array('type' => 'varchar','meta' => 'cfname','precision' => '64','nullable' => False),
			'kb_extra_value' => array('type' => 'text','meta' => 'cfvalue','nullable' => False)
		),
		'pk' => array(),
		'fk' => array(),
		'ix' => array(),
		'uc' => array(array('kb_id','kb_extra_name'))
	)
);
