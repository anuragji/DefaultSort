<?php
/**
 * DefaultSort Plugin
 *
 * @copyright   Copyright 2014 The Digital Ark, Corp.
 * @author	  	Anuragji
 * @license	 	http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 * @package	 	DefaultSort
 *
 */

define('DEFAULT_SORT_PLUGIN_DIR', PLUGIN_DIR . '/DefaultSort');

class DefaultSortPlugin extends Omeka_Plugin_AbstractPlugin
{

	protected $_hooks = array(
		'install',
		'uninstall',
		'config',
		'config_form',
		'upgrade'
	);

	protected $_options = array(

		'defaultsort_items_enabled' => '1',
		'defaultsort_items_option' => 'added',
		'defaultsort_items_direction' => 'd',

		'defaultsort_collections_enabled' => '1',
		'defaultsort_collections_option' => 'added',
		'defaultsort_collections_direction' => 'd',

		'defaultsort_excluded_collections' =>'',
		'defaultsort_excluded_collections_option'=> 'added',
		'defaultsort_exluded_collections_direction' => 'a'

	);

	protected $_filters = array('items_browse_params', 'collections_browse_params');

	public function hookInstall()
	{
		$this->_installOptions();
	}

	public function hookUninstall()
	{
		$this->_uninstallOptions();
	}

	public function hookConfigForm()
	{
		// get all elements
		$elementsTable = get_db()->getTable('Element');
		$select = $elementsTable->getSelect()
			->order('elements.element_set_id')
			->order('ISNULL(elements.order)')
			->order('elements.order');

		$elements = $elementsTable->fetchObjects($select);

		// get all public collections
		$collectionsTable = get_db()->getTable('Collection');
		$collections = $collectionsTable->findBy(array('public' => 1));

		include 'config_form.php';
	}

	public function hookConfig($args)
	{
		$post = $args['post'];

		// manually check exluded collections in case $_POST came back empty
		if (!isset($post['defaultsort_excluded_collections'])) {
			$post['defaultsort_excluded_collections'] = array();
		}

		foreach($post as $key=>$value) {

			// serialize our excluded collections
			if ($key == 'defaultsort_excluded_collections') {
				$value = serialize($value);
			}

			set_option($key, $value);
		}
	}

	public function hookUpgrade($args) {

		$old = $args['old_version'];
		$new = $args['new_version'];

		if(version_compare($old, $new, '<')) {
			if (!get_option('defaultsort_excluded_collections')) {
				$excludedCollections = array();
				set_option('defaultsort_excluded_collections', serialize($excludedCollections));
			}

			if (!get_option('defaultsort_excluded_collections_option')) {
				set_option('defaultsort_excluded_collections_option', 'added');
			}

			if (!get_option('defaultsort_exluded_collections_direction')) {
				set_option('defaultsort_exluded_collections_direction', 'a');
			}
		}
	}

	public function filterItemsBrowseParams($params)
	{
		// Only apply to public side.
		if (!is_admin_theme()) {
			$req = Zend_Controller_Front::getInstance()->getRequest();
			$requestParams = $req->getParams();

			$sortParam = Omeka_Db_Table::SORT_PARAM;
			$sortDirParam = Omeka_Db_Table::SORT_DIR_PARAM;
			
			// Default values
			$exludedCollections = array();
			$collectionId = null;

			if (isset($params['collection'])) {
				// When browsing items from a specific collection
				$collectionId = $params['collection'];
				$exludedCollections = unserialize(get_option('defaultsort_excluded_collections'));
			}

			// Browse Items
			if (array_key_exists('controller',$params) & array_key_exists('action',$params)) {
				if ($requestParams['controller'] == 'items' && $requestParams['action'] == 'browse') {
					// Only apply the Default Sort if enabled and no other sort has been defined
					if (get_option('defaultsort_items_enabled') && !isset($_GET['sort_field'])) {
						// See if there are any exceptions specified
						if (in_array($collectionId, $exludedCollections)) {
							// Use modified sort for exluded collections
							$newSortField = get_option('defaultsort_excluded_collections_option');
							$newSortDir = get_option('defaultsort_exluded_collections_direction');
						} else {
							// Use default specified for items
							$newSortField = get_option('defaultsort_items_option');
							$newSortDir = get_option('defaultsort_items_direction');
						}

						$params['sort_field'] = $newSortField;
						$params['sort_dir'] = $newSortDir;

						// Apply the default sort from the plugin
						$req->setParam($sortParam, $newSortField);
						$req->setParam($sortDirParam, $newSortField);
					}
				}
			}
		}

		return $params;
	}

	public function filterCollectionsBrowseParams($params)
	{
		// Only apply to public side
		if (!is_admin_theme()) {
			$req = Zend_Controller_Front::getInstance()->getRequest();
			$requestParams = $req->getParams();

			$sortParam = Omeka_Db_Table::SORT_PARAM;
			$sortDirParam = Omeka_Db_Table::SORT_DIR_PARAM;

			// Browse Collections
			if (array_key_exists('controller', $params) & array_key_exists('action', $params)) {
				if ($requestParams['controller'] == 'collections' && $requestParams['action'] == 'browse') {
					// Only apply the Default Sort if enabled and no other sort has been defined
					if (get_option('defaultsort_collections_enabled') && !isset($_GET['sort_field'])) {
						$params['sort_field'] = get_option('defaultsort_collections_option');
						$params['sort_dir'] = get_option('defaultsort_collections_direction');

						// Apply the default sort from the plugin
						$req->setParam($sortParam, get_option('defaultsort_collections_option'));
						$req->setParam($sortDirParam, get_option('defaultsort_collections_direction'));
					}
				}
			}
		}

		return $params;
	}
}
