DefaultSort
===========

This plugin can be used to change Omeka's default sorting, which is set to `date added`. Any metadata element may be used as the new default sort when browsing either Collections or Items. Public Collections may be exluded from the specified Item sort and instead use an alternate sort method. This can be used for example to display Items of a specific collection in chronological order (when accessed via 'items/browse?collection=foo'), while the rest of the items will be sorted by Title.

### Please note

It would probably be a good idea to add whichever sort field you select to sort by to the list of sort links in your browse views. Take a look at the Omeka default theme for an example of how this can be done in Items/Browse file:

	$sortLinks[__('Title')] = 'Dublin Core,Title';
	$sortLinks[__('Creator')] = 'Dublin Core,Creator';
	$sortLinks[__('Date Added')] = 'added';
	// begin added code
	if (get_option('defaultsort_items_enabled')) {
		$newSortField = get_option('defaultsort_items_option');
		if (!in_array($newSortField, array('Dublin Core,Title','Dublin Core,Creator','added'))) {
			$array = explode(',', $newSortField);
			$sortLinks[__($array[1])] = $newSortField;
		}
	}
	// end added code

### Thanks

Thanks to @patrickmj from the Omeka Dev Team, for the basic filter implementation.
