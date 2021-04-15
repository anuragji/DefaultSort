<?php
/**
 * DefaultSort Plugin
 *
 * @copyright   Copyright 2014-2021 The Digital Ark, Corp.
 * @author	  	Anuragji
 * @contributor DBinaghi
 * @license	 	http://www.gnu.org/licenses/gpl-3.0.txt GPLv3 or any later version
 * @package	 	DefaultSort
 *
 */
?>
<?php $view = get_view(); ?>

<p>
	<?php echo __('This plugin can be used to override Omeka\'s default sort criteria, which is set to %s.', '<em><code>' . __('date added') . '</code></em>');?>
	<?php echo __('You can also specify Collections whose Items are to be excluded or can have their own sort.');?>
</p>

<p>
	<b><?php echo __('Please note');?></b>: <?php echo __('in most cases, you will want to add whichever field you select here to the list of sort links in your browse views; check the %s file for an example.', '<em>README.md</em>');?>
</p>

<h3><?php echo __('Items'); ?></h3>

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Enable for Items');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, applies the new default sort to Items.'); ?>
		</p>
		<?php echo $view->formCheckbox('defaultsort_items_enabled', null, array('checked'=> (bool) get_option('defaultsort_items_enabled') ? 'checked' : '', ) ); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Sort Field');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the field all Items will be sorted by.'); ?>
		</p>
		<select name="defaultsort_items_option">
			<option value=""><?php echo __('Please select'); ?></option>
			<?php $currentItemsSort =  get_option('defaultsort_items_option'); ?>

			<option value="added" <?php if ($currentItemsSort == 'added') echo 'selected'; ?>><?php echo __('Date Added'); ?></option>
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

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Sort Direction');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the sorting direction.'); ?>
		</p>
		<?php $currentItemsSortDir = get_option('defaultsort_items_direction'); ?>
		<input type="radio" name="defaultsort_items_direction" value="a" <?php if( $currentItemsSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
		<input type="radio" name="defaultsort_items_direction" value="d" <?php if( $currentItemsSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
	</div>
</div>

<h3><?php echo __('Collections'); ?></h3>

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Enable for Collections');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, applies the new default sort to Collections.'); ?>
		</p>
		<?php echo $view->formCheckbox('defaultsort_collections_enabled', null, array('checked'=> (bool) get_option('defaultsort_collections_enabled') ? 'checked' : '', ) ); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Sort Field');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the field all Collections will be sorted by.'); ?>
		</p>
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

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Sort Direction');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the sorting direction.'); ?>
		</p>
		<?php $currentCollectionSortDir = get_option('defaultsort_collections_direction'); ?>
		<input type="radio" name="defaultsort_collections_direction" value="a" <?php if( $currentCollectionSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
		<input type="radio" name="defaultsort_collections_direction" value="d" <?php if( $currentCollectionSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
	</div>
</div>

<h3><?php echo __('Excluded Collections'); ?></h3>

<p><?php echo __('This set of options allows you to specify a different Items sort when browsing items that are part of specific public Collections. For instance, you might want to display one Collection\'s Items in chronological order, while all other Items should be sorted by Title.');?></p>

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Collections to exclude');?></label>
	</div>
	<div class="inputs five columns omega">
		<div class="input-block">
			<?php
			$currentExludedCollections = unserialize(get_option('defaultsort_excluded_collections'));
			if(empty($collections)) :?>
			<?php echo __('There are no public Collections at the moment.'); ?>

			<?php else: ?>
				<ul style="list-style:none; padding-left: 0; margin-bottom: 0;">
					<?php
					foreach($collections as $collection) {
						$collectionId = $collection['id'];
						$collectionTitle = metadata($collection, array('Dublin Core', 'Title'));

						?>
						<li><?php
							echo $view->formCheckbox('defaultsort_excluded_collections[]', $collectionId,
								array('checked'=> in_array($collectionId, $currentExludedCollections) ? 'checked' : '')
							) . ' ' . $collectionTitle;
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
	<div class="two columns alpha">
		<label><?php echo __('Sort Field');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the field all Items belonging to excluded Collections will be sorted by.'); ?>
		</p>
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

<div class="field">
	<div class="two columns alpha">
		<label><?php echo __('Sort Direction');?></label>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('Choose the sorting direction.'); ?>
		</p>
		<?php $currentExcludedSortDir = get_option('defaultsort_exluded_collections_direction'); ?>
		<input type="radio" name="defaultsort_exluded_collections_direction" value="a" <?php if( $currentExcludedSortDir == 'a' ) { echo 'checked'; }  ?>> <?php echo __('Ascending'); ?> <br />
		<input type="radio" name="defaultsort_exluded_collections_direction" value="d" <?php if( $currentExcludedSortDir == 'd' ) { echo 'checked'; }  ?>> <?php echo __('Descending'); ?>
	</div>
</div>
