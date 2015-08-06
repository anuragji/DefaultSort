DefaultSort
===========

This plugin can be used to change the Omeka default sorting, which is set to `date added`. Any metadata element may be used as the new default sort when browsing either collections or items. Public collections may be exluded from the specified item sort and instead use an alternate sort method. This can be used for example to display items of a specific collection in chronological order (when accessed via 'items/browse?collection=foo'), while the rest of the items will be sorted by title.

#### Please note

It would probably be a good idea to add whichever sort field you select to sort by to the list of sort links in your browse views. Take a look at the Omeka default theme for an example of how this can be done:

`/Omekaroot/themes/default/items/browse`

###### Thanks

Thanks to @patrickmj from the Omeka Dev Team, for the basic filter implementation.
