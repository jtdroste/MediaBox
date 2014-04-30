<div class="media-header carousel">
	<div class="carousel-inner">
		<div class="item active">
		<img src="<?php echo $this->Html->url('/movies/art/'.$movie->getRatingKey()); ?>" alt="" class="img-responsive">
			<div class="carousel-caption">
				<div class="row">
					<div class="col-md-3">
						<img src="<?php echo $this->Html->url('/home/plex_proxy/'.$movie->getRatingKey()); ?>" />
					</div>
					<div class="col-md-9">
						<h1><?php echo $movie->getTitle(); ?></h1>
						<p><?php echo $movie->getSummary(); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
debug($movie);
?>