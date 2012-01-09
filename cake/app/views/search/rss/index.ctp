<rss version="2.0"
	xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
	xmlns:relevance="http://a9.com/-/spec/opensearch/extensions/relevance/1.0/"
	xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php echo Configure::read('Site.name'); ?> Search: <?php echo $response['query']; ?></title>
		<link><?php echo $html->url('/search/index', true) . '?query=' . $response['query'] . '&amp;start=' . $response['start'] . '&amp;limit=' . $response['limit']; ?></link>
		<description><?php echo Configure::read('Site.name'); ?> Search: <?php echo $response['query']; ?></description>
		<generator>Laboratree</generator>
		<webMaster>support@selican.com</webMaster>
		<copyright>Copyright 2010, Selican Technologies, Inc.</copyright>
		<opensearch:totalResults><?php echo $response['total']; ?></opensearch:totalResults>
		<opensearch:startIndex><?php echo $response['start']; ?></opensearch:startIndex>
		<opensearch:itemsPerPage><?php echo $response['limit']; ?></opensearch:itemsPerPage>
		<atom:link rel="search"
			href="<?php echo $html->url('/search/opensearch.xml', true); ?>"
			type="application/opensearchdescription+xml"
			title="<?php echo Configure::read('Site.name'); ?> Search" />
		<opensearch:Query role="request" searchTerms="<?php echo $response['query']; ?>" startPage="<?php echo $response['start']; ?>" pageOffset="<?php echo $response['limit']; ?>" />
		<?php
			if(!empty($response['results'])):
				foreach($response['results'] as $model => $results):
					foreach($results as $result):
		?>
		<item>
			<title><?php echo $model . ': ' . $result['title']; ?></title>
			<link><?php echo $html->url($result['view'], true); ?></link>
			<description>
				<?php echo $result['description']; ?>
			</description>
			<relevance:score><?php echo $result['score']; ?></relevance:score>
		</item>
		<?php
					endforeach;
				endforeach;
			endif;
		?>
	</channel>
</rss>
