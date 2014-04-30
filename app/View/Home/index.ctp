<?php
$this->Html->script('jquery.film_roll.min.js', array('inline' => false));
?>

<?php if ( isset($setup_mediabox_warning) && $setup_mediabox_warning && in_array('Administrator', $userperms) ) : ?>
<div class="alert alert-dismissable alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Warning!</h4>
	<p>Yo your sys is insecure.</p>
</div>
<?php endif; ?>

<div class="jumbotron">
	<h1>Welcome to the MediaBox!</h1>
	<p>Welcome to your MediaBox homepage! From here you can easily <a href="#latest">view the latest media the server has</a>, <a href="#recent">see what your friends are watching</a>, <a href="#">request new media for the server</a>, and more!</p>
	 <p>
	 	<a href="#" class="btn btn-primary btn-lg" role="button">Get the grand tour! &raquo;</a>
	 	<a href="#" class="btn btn-danger btn-lg" role="button">Hide this message</a>
	 </p>
</div>

<?php if ( isset($latest_media) && !empty($latest_media) ) : ?>
<div class="latest">
	<h2><a name="latest">Recently Added</a></h2>
	<div id="latest-roll">
		<?php
		foreach ( $latest_media AS $i => $item ) :
		if ( $i > 10 ) break; //10 max
		
		$url = 
			($item->getType() == 'season') ? 
			'/tv/view/'.$item->getParentRatingKey().'/'.$item->getRatingKey() : 
			'/movies/view/'.$item->getRatingKey();
		?>
		<div><a href="<?php echo $this->Html->url($url); ?>"><img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$item->getRatingKey()); ?>" /></a></div>
		<?php endforeach; ?>
	</div>
</div>

<script>
$(document).ready(function() {
	new FilmRoll({
		container: '#latest-roll',
		height: 300,
		no_css: true
	});
});
</script>
<?php endif; ?>

<?php if ( isset($recently_watched) && !empty($recently_watched) ) : ?>
<div class="recent">
	<h2><a name="recent">Recently Watched</a></h2>
	<div id="recent-roll">
		<?php
		foreach ( $recently_watched AS $item ) :

		if ( $item['item']->getType() == 'episode' ) {
			$id = $item['item']->getParentRatingKey();
		} else {
			$id = $item['item']->getRatingKey();
		}

		$url = 
			($item['item']->getType() == 'episode') ? 
			'/tv/view/'.$item['item']->getGrandparentRatingKey().'/'.$item['item']->getParentRatingKey().'/'.$item['item']->getRatingKey() : 
			'/movies/view/'.$item['item']->getRatingKey();
		?>
		<div><a href="<?php echo $this->Html->url($url); ?>"><img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$id); ?>" /></a></div>
		<?php endforeach; ?>
	</div>
</div>

<script>
$(document).ready(function() {
	new FilmRoll({
		container: '#recent-roll',
		height: 300,
		no_css: true
	});
});
</script>
<?php endif; ?>