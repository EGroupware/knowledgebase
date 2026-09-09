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

class Bo extends Api\Storage
{
	const APP = 'knowledgebase';
	const TABLE = 'egw_knowledgebase';
	const EXTRA_TABLE = 'egw_knowledgebase_extra';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct(self::APP, self::TABLE, self::EXTRA_TABLE, '', '_extra_name', '_extra_value');
	}

	/**
	 * saves the content of data to the db
	 *
	 * Reimplemented to set owner and modifier(ed) and save links for new entries.
	 *
	 * @param array $keys =null if given $keys are copied to data before saveing => allows a save as
	 * @param string|array $extra_where =null extra where clause, eg. to check an etag, returns true if no affected rows!
	 * @return int|boolean 0 on success, or errno != 0 on error, or true if $extra_where is given and no rows affected
	 */
	public function save($keys = null, $extra_where = null)
	{
		$this->data_merge($keys);

		if (empty($this->data['kb_id']))
		{
			$this->data['account_id'] = $GLOBALS['egw_info']['user']['account_id'];
			$this->data['kb_created'] = $this->now;
		}
		$this->data['kb_modifier'] = $GLOBALS['egw_info']['user']['account_id'];
		$this->data['kb_modified'] = $this->now;

		if (($ret = parent::save(null, $extra_where)) == 0 &&
			// check if we have links to save (new entries only)
			is_array($keys['link_to']['to_id']) && count($keys['link_to']['to_id']))
		{
			Api\Link::link(self::APP, $this->data['kb_id'], $keys['link_to']['to_id']);
		}
		return $ret;
	}

	/**
	 * Deletes a knowledgebase document identified by $keys or the loaded one
	 *
	 * Reimplemented to notify the link class (unlink)
	 *
	 * @param array $keys if given array with col => value pairs to characterise the rows to delete
	 * @param boolean $only_return_query =false * NOT supported, but required by PHP 8 *
	 * @return int affected rows, should be 1 if ok, 0 if an error
	 */
	function delete($keys=null, $only_return_query=false)
	{
		if (!is_array($keys) && (int) $keys)
		{
			$keys = array('kb_id' => (int) $keys);
		}
		$kb_id = is_null($keys) ? $this->data['kb_id'] : $keys['kb_id'];

		if (($ret = parent::delete($keys)) && $kb_id)
		{
			// delete all links to the document $kb_id
			Api\Link::unlink(0, self::APP, $kb_id);
		}
		return $ret;
	}

	/**
	 * get title for a knowledgebase document identified by $entry
	 *
	 * Is called as hook to participate in the linking
	 *
	 * @param int|array $entry int kb_id or array with document
	 * @return string|boolean string with title, null if document not found, false if no perms to view it
	 */
	function link_title( $entry )
	{
		if (!is_array($entry))
		{
			// need to preserve the $this->data
			$backup =& $this->data;
			unset($this->data);
			$entry = $this->read(['kb_id' => $entry]);
			// restore the data again
			$this->data =& $backup;
		}
		if (!$entry)
		{
			return $entry;
		}
		return $entry['kb_title'];
	}

	/**
	 * query knowledgebase app for documents matching $pattern
	 *
	 * Is called as hook to participate in the linking
	 *
	 * @param string $pattern pattern to search
	 * @param array $options Array of options for the search
	 * @return array with kb_id - title pairs of the matching documents
	 */
	function link_query($pattern, Array &$options = array() )
	{
		$limit = false;
		$need_count = false;
		if($options['start'] || $options['num_rows'])
		{
			$limit = array($options['start'], $options['num_rows']);
			$need_count = true;
		}
		$result = [];
		foreach($this->search($pattern,false,'','','%',false,'OR', $limit, null, '', $need_count) as $row)
		{
			$result[$row['kb_id']] = $this->link_title($row);
		}
		$options['total'] = $need_count ? $this->total : count($result);
		return $result;
	}
}
