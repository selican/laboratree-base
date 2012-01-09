<?php
	echo $html->meta('atom', '/search/index.atom?query=' . urlencode($query), array('title' => 'Atom (1.0)', 'rel' => 'alternate'), false);
	echo $html->meta('rss', '/search/index.rss?query=' . urlencode($query), array('title' => 'RSS (2.0)'), false);
	echo $html->meta('xml', null, array('link' => '/search/index.xml?query=' . urlencode($query), 'title' => 'XML', 'type' => 'text/xml', 'rel' => 'alternate'), false);
	echo $html->meta('json', null, array('link' => '/search/index.json?query=' . urlencode($query), 'title' => 'JSON', 'type' => 'application/json', 'rel' => 'alternate'), false);

	$html->addCrumb('Search', '/search/index');
	if(!empty($query))
	{
		$html->addCrumb($query, '/search/index?query=' . urlencode($query));
	}
?>
<div id="searchpage-div"></div>
<script type="text/javascript">
	laboratree.search.makeSearch('searchpage-div', '<?php echo $html->url('/search/index.extjs'); ?>', '<?php echo addslashes($query); ?>');
</script>
