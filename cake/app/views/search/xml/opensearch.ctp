<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName><?php echo Configure::read('Site.name'); ?></ShortName>
	<LongName><?php echo Configure::read('Site.name'); ?> Search</LongName>
	<Description>Search <?php echo Configure::read('Site.name'); ?></Description>
	<Tags>selican laboratree <?php echo low(Configure::read('Site.name')); ?></Tags>
	<Contact>support@selican.com</Contact>
	<Url type="application/atom+xml"
		template="<?php echo $html->url('/search/index.atom', true) . '?query={searchTerms}&amp;start={startPage?}&amp;limit={count?}'; ?>" />
	<Url type="application/rss+xml"
		template="<?php echo $html->url('/search/index.rss', true) . '?query={searchTerms}&amp;start={startPage?}&amp;limit={count?}'; ?>" />
	<Url type="application/json"
		template="<?php echo $html->url('/search/index.json', true) . '?query={searchTerms}&amp;start={startPage?}&amp;limit={count?}'; ?>" />
	<Url type="text/xml"
		template="<?php echo $html->url('/search/index.xml', true) . '?query={searchTerms}&amp;start={startPage?}&amp;limit={count?}'; ?>" />
	<Url type="text/html"
		template="<?php echo $html->url('/search/index', true) . '?query={searchTerms}&amp;start={startPage?}&amp;limit={count?}'; ?>" />
	<Image height="64" width="64" type="image/png"><?php echo $html->url('/img/search.png', true); ?></Image>
	<Image height="16" width="16" type="image/vnd.microsoft.icon"><?php echo $html->url('/favicon.ico', true); ?></Image>
	<Query role="example" searchTerms="laboratree" />
	<Developer>Selican Technologies, Inc.</Developer>
	<Attribution>
		Search data Copyright 2010, Selican Technologies, Inc., All Rights Reserved
	</Attribution>
	<SyndicationRight>closed</SyndicationRight>
	<AdultContent>false</AdultContent>
	<Language>en-us</Language>
	<InputEncoding>UTF-8</InputEncoding>	
	<OutputEncoding>UTF-8</OutputEncoding>
</OpenSearchDescription>
