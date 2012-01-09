<feed xmlns="http://www.w3.org/2005/Atom"
	xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
	xmlns:relevance="http://a9.com/-/opensearch/extensions/relevance/1.0/">
	<title><?php echo Configure::read('Site.name'); ?> Search: <?php echo $response['query']; ?></title>
	<link><?php echo $html->url('/search/index', true) . '?query=' . $response['query']; ?></link>
	<updated><?php echo date('c'); ?></updated>
	<author>
		<name><?php echo Configure::read('Site.name'); ?></name>
	</author>
	<id><?php echo Configure::read('Site.domain'); ?></id>
	<generator>Laboratree</generator>
	<rights>Copyright 2010 Selican Technologies, Inc.</rights>
	<opensearch:totalResults><?php echo $response['total']; ?></opensearch:totalResults>
	<opensearch:startIndex><?php echo $response['start']; ?></opensearch:startIndex>
	<opensearch:itemsPerPage><?php echo $response['limit']; ?></opensearch:itemsPerPage>
	<opensearch:Query role="request" searchTerms="<?php echo $response['query']; ?>" startPage="<?php echo $response['start']; ?>" pageOffset="<?php echo $response['limit']; ?>" />
	<link rel="alternate"
		href="<?php echo $html->url('/search/index', true) . '?query=' . $response['query'] . '&amp;start=' . $response['start'] . '&amp;limit=' . $response['limit']; ?>"
		type="text/html" />
	<link rel="self"
		href="<?php echo $html->url('/search/index.atom', true) . '?query=' . $response['query'] . '&amp;start=' . $response['start'] . '&amp;limit=' . $response['limit']; ?>"
		type="application/atom+xml" />
	<link rel="first"
		href="<?php echo $html->url('/search/index.atom', true) . '?query=' . $response['query'] . '&amp;start=0&amp;limit=' . $response['limit']; ?>"
		type="application/atom+xml" />
	<?php if(!empty($response['previous'])): ?>
	<link rel="previous"
		href="<?php echo $html->url('/search/index.atom', true) . '?query=' . $response['query'] . '&amp;start=' . $response['previous'] . '&amp;limit=' . $response['limit']; ?>"
		type="application/atom+xml" />
	<?php endif; ?>
	<?php if(!empty($response['next'])): ?>
	<link rel="next"
		href="<?php echo $html->url('/search/index.atom', true) . '?query=' . $response['query'] . '&amp;start=' . $response['next'] . '&amp;limit=' . $response['limit']; ?>"
		type="application/atom+xml" />
	<?php endif; ?>
	<link rel="last"
		href="<?php echo $html->url('/search/index.atom', true) . '?query=' . $response['query'] . '&amp;start=' . $response['last'] . '&amp;limit=' . $response['limit']; ?>"
		type="application/atom+xml" />
	<link rel="search"
		href="<?php echo $html->url('/search/opensearch.xml', true); ?>"
		type="application/opensearchdescription+xml"
		title="<?php echo Configure::read('Site.name'); ?> Search" />
	<?php
		if(!empty($response['results'])):
			foreach($response['results'] as $model => $results):
				foreach($results as $result):
	?>
	<entry>
		<title><?php echo $model . ': ' . $result['title']; ?></title>
		<link href="<?php echo $html->url($result['view'], true); ?>" />
		<id>tag:<?php echo Configure::read('Site.domain'); ?>,<?php echo date('Y-m-d', strtotime($result['date'])); ?>:<?php echo $result['model'] . $result['id']; ?></id>
		<updated><?php echo date('c', strtotime($result['date'])); ?></updated>
		<content type="text">
			<?php echo $result['description']; ?>
		</content>
		<relevance:score><?php echo $result['score']; ?></relevance:score>
	</entry>
	<?php
				endforeach;
			endforeach;
		endif;
	?>
</feed>
