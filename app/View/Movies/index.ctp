<div class="jumbotron">
	<h1>Movie List</h1>
	<p>There is currently <?php echo count($movies); ?> movie<?php echo (count($movies) > 1 ? 's' : ''); ?> on this media library.</p>
</div>

<ul class="list-group media-list">
	<?php foreach ( $movies AS $movie ) : ?>
	<li>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<a href="<?php echo $this->Html->url('/movies/view/'.$movie->getRatingKey()); ?>">
							<img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$movie->getRatingKey()); ?>" />
						</a>
					</div>
					<div class="col-md-6">
						<h3><?php echo $movie->getTitle(); ?></h3>
						<p><?php echo $movie->getSummary(); ?></p>

						<p>Rated: <?php echo round($movie->getRating(), 1); ?>/10</p>
					</div>
					<div class="col-md-3 buttons">
						<div class="btn-group-vertical">
							<a href="<?php echo $this->Html->url('/movies/view/'.$movie->getRatingKey()); ?>">
								<button type="button" class="btn btn-info btn-lg">More Information</button>
							</a>
							<?php if ( $logged_in ) : ?>
							<a href="<?php echo $this->Html->url('/movies/watch/'.$movie->getRatingKey()); ?>">
								<button type="button" class="btn btn-info btn-lg">Watch Now!</button>
							</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>