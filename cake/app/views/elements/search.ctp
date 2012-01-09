<div id="search-div">
<?php if($session->check('Auth.User')): ?>
<form method="get" action="<?php echo $html->url('/search/index'); ?>">
<div class="search">
	<input type="text" name="query" id="SearchQuery" class="searchinput" />
	<input type="image" src="http://static.selican.com/img/search-button.png" class="searchsubmit" />
</div>
</form>
<?php endif; ?>
</div>
