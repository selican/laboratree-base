<?php
	header('Pragma: no-cache');
	header('Cache-Control: no-store, no-cache, max-age=0, must-revalidate');
	header('Content-Type: application/rss+xml');
?>
<?xml version="1.0" encoding="utf-8"?>
<?php echo $content_for_layout; ?>
