<div class="jumbotron">
	<h1>Movies - Coming Soon</h1>
	<p>There is currently <?php echo count($soon); ?> movie<?php echo (count($soon) > 1 ? 's' : ''); ?> coming soon.</p>
</div>

<ul class="list-group media-list">
	<?php foreach ( $soon AS $movie ) : ?>
	<li>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<a href="http://www.imdb.com/title/<?php echo $movie['imdb']; ?>">
							<img src="<?php echo $movie['poster']; ?>" wdith="200" height="300" />
						</a>
					</div>
					<div class="col-md-6">
						<h3><?php echo $movie['title']; ?></h3>
						<p><?php echo $movie['plot']; ?></p>

						<p><strong>Estimated Release</strong>: <?php echo date('m/d/Y', $movie['release']); ?></p>
					</div>
					<div class="col-md-3 buttons">
						<p><strong>Rated</strong>: <?php echo !empty($movie['rating']) ? $movie['rating'] : 'Unknown'; ?></p>
						<p><strong>Rating</strong>: <?php echo !empty($movie['rated']) ? $movie['rated'].'/10' : 'N/A'; ?></p>
						<p><strong>Runtime</strong>: <?php echo !empty($movie['runtime']) ? $movie['runtime'] : 'Unknown'; ?> minutes</p>
					</div>
				</div>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>