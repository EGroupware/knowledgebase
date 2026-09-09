<?php
/**
 * EGroupware - Knowledgebase - User interface
 *
 * @link http://www.egroupware.org
 * @author Ralf Becker <rb-AT-egroupware.org>
 * @package knowledgebase
 * @subpackage setup
 * @copyright (c) 2019 by Ralf Becker <rb-AT-egroupware.org>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 */

namespace EGroupware\Knowledgebase;

use EGroupware\Api;

class Ui
{
	/**
	 * Methods callable via menuaction GET parameter
	 *
	 * @var array
	 */
	public $public_functions = [
		'index' => true,
		'edit'  => true,
	];

	/**
	 * Instance of our business object
	 *
	 * @var Bo
	 */
	protected $bo;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->bo = new Bo();
	}

	/**
	 * Edit a document
	 *
	 * @param ?array $content =null
	 */
	public function edit(?array $content=null)
	{
		if (!is_array($content))
		{
			if (!empty($_GET['kb_id']))
			{
				if (!($content = $this->bo->read(['kb_id' => $_GET['kb_id']])))
				{
					Api\Framework::window_close(lang('Entry not found!'));
				}
			}
			else
			{
				$content = $this->bo->init();
			}
		}
		else
		{
			$button = key($content['button']);
			unset($content['button']);
			switch($button)
			{
				case 'save':
				case 'apply':
					if (!$this->bo->save($content))
					{
						Api\Framework::refresh_opener(lang('Entry saved.'),
							Bo::APP, $this->bo->data['kb_id'],
							empty($content['kb_id']) ? 'add' : 'edit');

						$content = array_merge($content, $this->bo->data);
					}
					else
					{
						Api\Framework::message(lang('Error storing entry!'));
						unset($button);
					}
					if ($button === 'save')
					{
						Api\Framework::window_close();	// does NOT return
					}
					Api\Framework::message(lang('Entry saved.'));
					break;

				case 'delete':
					if (!$this->bo->delete(['kb_id' => $content['kb_id']]))
					{
						Api\Framework::message(lang('Error deleting entry!'));
					}
					else
					{
						Api\Framework::refresh_opener(lang('Entry deleted.'),
							Bo::APP, $content['kb_id'], 'delete');

						Api\Framework::window_close();	// does NOT return
					}
			}
		}
		$content['link_to'] = [
			'to_id'  => $content['kb_id'],
			'to_app' => Bo::APP,
		];
		$readonlys = [
			'button[delete]' => !$content['kb_id'],
		];
		$tmpl = new Api\Etemplate('knowledgebase.edit');
		$tmpl->exec('knowledgebase.'.self::class.'.edit', $content, [], $readonlys, $content, 2);
	}

	/**
	 * Fetch rows to display
	 *
	 * @param array $query
	 * @param array& $rows =null
	 * @param array& $readonlys =null
	 */
	public function get_rows($query, ?array &$rows=null, ?array &$readonlys=null)
	{
		return $this->bo->get_rows($query, $rows, $readonlys);
	}

	/**
	 * Index
	 *
	 * @param ?array $content =null
	 */
	public function index(?array $content=null)
	{
		if (!is_array($content) || empty($content['nm']))
		{
			$content = [
				'nm' => [
					'get_rows'       =>	Bo::APP.'.'.self::class.'.get_rows',
					'no_filter2'     => true,	// disable the diverse filters we not (yet) use
					'order'          =>	'kb_modified',// IO name of the column to sort after (optional for the sortheaders)
					'sort'           =>	'DESC',// IO direction of the sort: 'ASC' or 'DESC'
					'row_id'         => 'kb_id',
					'row_modified'   => 'kb_modified',
					'actions'        => $this->get_actions(),
					'placeholder_actions' => array('add')
				]
			];
		}
		elseif(!empty($content['nm']['action']))
		{
			try {
				Api\Framework::message($this->action($content['nm']['action'],
					$content['nm']['selected'], $content['nm']['select_all']));
			}
			catch (\Exception $ex) {
				Api\Framework::message($ex->getMessage(), 'error');
			}
		}
		$tmpl = new Api\Etemplate('knowledgebase.index');
		$tmpl->exec('knowledgebase.'.self::class.'.index', $content, [], [], ['nm' => $content['nm']]);
	}

	/**
	 * Return actions for document list
	 *
	 * @return array
	 */
	protected function get_actions()
	{
		return [
			'view' => [
				'caption' => 'View',
				'default' => true,
				'allowOnMultiple' => false,
				'onExecute' => 'javaScript:app.knowledgebase.view',
				'group' => $group=0,
			],
			'edit' => [
				'caption' => 'Edit',
				'allowOnMultiple' => false,
				'url' => 'menuaction=knowledgebase.'.self::class.'.edit&kb_id=$id',
				'popup' => '640x480',
				'group' => $group,
			],
			'add' => [
				'caption' => 'Add',
				'url' => 'menuaction=knowledgebase.'.self::class.'.edit',
				'popup' => '640x320',
				'group' => $group,
			],
			'delete' => [
				'caption' => 'Delete',
				'confirm' => 'Delete this document(s)',
				'group' => $group=5,
			],
		];
	}

	/**
	 * Execute action on list
	 *
	 * @param string $action
	 * @param array|int $selected
	 * @param boolean $select_all
	 * @returns string with success message
	 * @throws Api\Exception\AssertionFailed
	 */
	protected function action($action, $selected, $select_all)
	{
		unset($action, $selected, $select_all);

		throw new Api\Exception\AssertionFailed('To be implemented ;)');
	}
}
