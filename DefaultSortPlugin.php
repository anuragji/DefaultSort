<?php
/**
 * DefaultSort Plugin
 *
 * @copyright   Copyright 2014 The Digital Ark, Corp.
 * @author      Anuragji
 * @license     http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 * @package     Favorites
 *
 */

define('DEFAULT_SORT_PLUGIN_DIR', PLUGIN_DIR . '/DefaultSort');

class DefaultSortPlugin extends Omeka_Plugin_AbstractPlugin
{

    protected $_hooks = array(
        'install',
        'uninstall',
        'config',
        'config_form'
    );

    protected $_options = array(

        'defaultsort_items_enabled' => '1',
        'defaultsort_items_option' => 'added',
        'defaultsort_items_direction' => 'd',

        'defaultsort_collections_enabled' => '1',
        'defaultsort_collections_option' => 'added',
        'defaultsort_collections_direction' => 'd',
    );

    protected $_filters = array('items_browse_params');

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
        $table = get_db()->getTable('Element');
        $select = $table->getSelect()
            ->order('elements.element_set_id')
            ->order('ISNULL(elements.order)')
            ->order('elements.order');

        $elements = $table->fetchObjects($select);
        include 'config_form.php';
    }

    public function hookConfig($args)
    {
        $post = $args['post'];
        foreach($post as $key=>$value) {
            set_option($key, $value);
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

            // Browse Items
            if ($requestParams['controller'] == 'items' && $requestParams['action'] == 'browse') {
                // If no sort, sort by Dublin Core Date, ascendant.
                if (get_option('defaultsort_items_enabled')
                    && !isset($params['sort_field'])
                    || empty($params['sort_field'])
                    || $params['sort_field'] == 'added'
                ) {
                    $params['sort_field'] = get_option('defaultsort_items_option');
                    $params['sort_dir'] = get_option('defaultsort_items_direction');

                    // Set the params itself, so that the correct class may be added in browse_sort_links
                    $req->setParam($sortParam, get_option('defaultsort_items_option'));
                    $req->setParam($sortDirParam, get_option('defaultsort_items_direction'));
                }

            }

            // Browse Collections
            if ($requestParams['controller'] == 'collection' && $requestParams['action'] == 'browse') {
                // If no sort, sort by Dublin Core Date, ascendant.
                if (get_option('defaultsort_collection_enabled')
                    && !isset($params['sort_field'])
                    || empty($params['sort_field'])
                    || $params['sort_field'] == 'added'
                ) {
                    $params['sort_field'] = get_option('defaultsort_collections_option');
                    $params['sort_dir'] = get_option('defaultsort_collections_direction');

                    // Set the param itself, so that the correct class may be added in browse_sort_links

                    $req->setParam($sortParam, get_option('defaultsort_collections_option'));
                    $req->setParam($sortDirParam, get_option('defaultsort_items_direction'));
                }
            }

        }

        return $params;
    }
}