<?php
/**
 * EGroupware - Knowledgebase - Business logic
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <rb-AT-egroupware.org>
 * @package knowledgebase
 * @subpackage setup
 * @copyright (c) 2023 by Ralf Becker <rb-AT-egroupware.org>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 */

namespace EGroupware\Knowledgebase;

use EGroupware\Api;

class Hooks
{
	/**
	 * Hook called by link-class to include knowledgebase documents in the appregistry of the linkage
	 *
	 * @param array|string $location location and other parameters (not used)
	 * @return array with method-names
	 */
	static function search_link($location)
	{
		unset($location);	// not used, but required by function signature

		return array(
			'query' => Bo::APP.'.'.Bo::class.'.link_query',
			'title' => Bo::APP.'.'.Bo::class.'.link_title',
			'view'  => array(
				'menuaction' => Bo::APP.'.'.Ui::class.'.edit',
			),
			'view_id' => 'kb_id',
			'view_popup'  => '630x480',
			'edit'  => array(
				'menuaction' => Bo::APP.'.'.Ui::class.'.edit',
			),
			'edit_id' => 'kb_id',
			'edit_popup'  => '630x480',
			'list' => array(
				'menuaction' => Bo::APP.'.'.Ui::class.'.index',
				'ajax' => 'true'
			),
			'add' => array(
				'menuaction' => Bo::APP.'.'.Ui::class.'.edit',
			),
			'add_popup'  => '630x480',
		);
	}

	/**
	 * hooks to build knowledgebase app's sidebox-menu plus the admin and preferences sections
	 *
	 * @param string|array $args hook args
	 */
	static function all_hooks($args)
	{
		$appname = Bo::APP;
		$location = is_array($args) ? $args['location'] : $args;

		if ($GLOBALS['egw_info']['user']['apps']['admin'])
		{
			$file = Array(
				//'Site Configuration' => Api\Egw::link('/index.php','menuaction=admin.admin_config.index&appname=' . $appname,'&ajax=true'),
				'Custom fields' => Api\Egw::link('/index.php','menuaction=admin.admin_customfields.index&appname='.$appname.'&ajax=true'),
				/*'Global Categories'  => Api\Egw::link('/index.php',array(
					'menuaction' => 'admin.admin_categories.index',
					'appname'    => $appname,
					'global_cats'=> True,
					'ajax' => 'true',
				)),*/
			);
			if ($location == 'admin')
			{
				display_section($appname,$file);
			}
			else
			{
				display_sidebox($appname,lang('Admin'),$file);
			}
		}
	}
}