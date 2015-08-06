<?php
/**
 * Default Sort Plugin
 *
 * @creator Copyright 2014 The Digital Ark, Corp.
 * @author  Anuragji
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 * @package DefaultSort Plugin
 */
?>
<?php $view = get_view(); ?>
<h2><?php echo __('Set the Default Sort'); ?></h2>
<p><?php echo __('This plugin can be used to override the Omeka default sort which is set to');?> <em><code><?php echo __('date added'); ?></code></em>. <?php echo __("You can also specify collections who's items are to be excluded or can have their own sort.");?></p>
<p><b><?php echo __('Please note');?></b>: <?php echo __('In most cases you will want to add whichever field you select here, to the list of sort links in your browse views. Check '); ?> <em>(see /Omekaroot/themes/default/items/browse</em> <?php echo __(' for an example.');?></p>

<h3><?php echo __('Items'); ?></h3>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Enable Default Sort for Items?');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php echo $view->formCheckbox('defaultsort_items_enabled', null, array('checked'=> (bool) get_option('defaultsort_items_enabled') ? 'checked' : '', ) ); ?>
        </div>
    </div>
</div>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Items Sort Field');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <select name="defaultsort_items_option">
                <option value=""><?php echo __('Please select'); ?></option>
                <?php $currentItemsSort =  get_option('defaultsort_items_option'); ?>

                <option value="added" <?php if($currentItemsSort == 'added') { echo 'selected'; }?>><?php echo __('Date Added'); ?></option>
                <?php
                foreach ($elements as $element) :
                    $val = $allElements[]=$element->set_name . ',' . $element->name;
                    $checked = ($val == $currentItemsSort ? 'selected' : '');
                    ?>
                    <option value="<?php echo __($val);?>" <?php echo $checked; ?>><?php echo __($val);?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Collections Sort Direction');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php $currentItemsSortDir = get_option('defaultsort_items_direction'); ?>
            <input type="radio" name="defaultsort_items_direction" value="a" <?php if( $currentItemsSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
            <input type="radio" name="defaultsort_items_direction" value="d" <?php if( $currentItemsSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
        </div>
    </div>
</div>

<h3><?php echo __('Collections'); ?></h3>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Enable Default Sort for Collections?');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php echo $view->formCheckbox('defaultsort_collections_enabled', null, array('checked'=> (bool) get_option('defaultsort_collections_enabled') ? 'checked' : '', ) ); ?>
        </div>
    </div>
</div>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Collections Sort Field');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <select name="defaultsort_collections_option">
                <option value=""><?php echo __('Please select'); ?></option>
                <?php $currentCollectionSort =  get_option('defaultsort_collections_option'); ?>

                <option value="added" <?php if($currentCollectionSort == 'added') { echo 'selected'; }?>><?php echo __('Date Added'); ?></option>
                <?php
                foreach ($elements as $element) :
                    $val = $allElements[]=$element->set_name . ',' . $element->name;
                    $checked = ($val == $currentCollectionSort ? 'selected' : '');
                    ?>
                    <option value="<?php echo __($val);?>" <?php echo $checked; ?>><?php echo __($val);?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Collections Sort Direction');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php $currentCollectionSortDir = get_option('defaultsort_collections_direction'); ?>
            <input type="radio" name="defaultsort_collections_direction" value="a" <?php if( $currentCollectionSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
            <input type="radio" name="defaultsort_collections_direction" value="d" <?php if( $currentCollectionSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
        </div>
    </div>
</div>

<h3><?php echo __('Excluded Collections'); ?></h3>
<p><?php echo __('These options allow you to specify a different sort when browsing items that are part of specific public collections. This is helpful for example when you want to display the items for one collection in chronological order, while the rest of the items should be sorted by title.');?></p>

<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Specify collections');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php
            $currentExludedCollections = unserialize(get_option('defaultsort_excluded_collections'));
            if(empty($collections)) :?>
            <?php echo __('There are currently no public collections.'); ?>

            <?php else: ?>
                <ul>
                    <?php
                    foreach($collections as $collection) {
                        $collectionId = $collection['id'];
                        $collectionTitle = metadata($collection, array('Dublin Core', 'Title'));

                        ?>
                        <li><?php
                            echo $view->formCheckbox('defaultsort_excluded_collections[]', $collectionId,
                                array('checked'=> in_array($collectionId, $currentExludedCollections) ? 'checked' : '')
                            );
                            echo $collectionTitle;
                        ?>
                        </li><?php
                    }
                    ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Excluded Collections Sort Field');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <select name="defaultsort_excluded_collections_option">
                <option value=""><?php echo __('Please select'); ?></option>
                <?php $currentExcludedSort =  get_option('defaultsort_excluded_collections_option'); ?>

                <option value="added" <?php if($currentExcludedSort == 'added') { echo 'selected'; }?>><?php echo __('Date Added'); ?></option>
                <?php
                foreach ($elements as $element) :
                    $val = $allElements[]=$element->set_name . ',' . $element->name;
                    $checked = ($val == $currentExcludedSort ? 'selected' : '');
                    ?>
                    <option value="<?php echo __($val);?>" <?php echo $checked; ?>><?php echo __($val);?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="field">
    <div class="three columns alpha">
        <label><?php echo __('Excluded Collections Sort Direction');?></label>
    </div>
    <div class="inputs four columns omega">
        <div class="input-block">
            <?php $currentExcludedSortDir = get_option('defaultsort_exluded_collections_direction'); ?>
            <input type="radio" name="defaultsort_exluded_collections_direction" value="a" <?php if( $currentExcludedSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
            <input type="radio" name="defaultsort_exluded_collections_direction" value="d" <?php if( $currentExcludedSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
        </div>
    </div>
</div>
