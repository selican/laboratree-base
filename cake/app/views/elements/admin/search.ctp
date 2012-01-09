<?php if($session->check('Auth.User')): ?>
<div class="navSearch">Search</div>
<form method="get" action="<?php echo $html->url('/admin/search/index'); ?>">
<div class="search">
	<input type="text" name="query" id="SearchQuery" class="searchinput" />
	<input type="submit" value="GO" class="searchsubmit" />
</div>
</form>
<?php endif; ?>
