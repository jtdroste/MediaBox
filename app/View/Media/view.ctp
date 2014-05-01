<?php
$this->Html->script('moment.min.js', array('inline' => false));
$this->Html->script('bootstrap-sortable.js', array('inline' => false));

$this->Html->css('bootstrap-sortable.css', null, array('inline' => false));

list(,,,$artid,) = explode('/', $media->getArt());
$thumbid = ($media->getType() == 'episode' ? $media->getParentRatingKey() : $media->getRatingKey());
?>
<div class="media-header carousel">
	<div class="carousel-inner">
		<div class="item active">
		<img src="<?php echo $this->Html->url('/home/plex_proxy/art/'.$artid); ?>" alt="" class="img-responsive">
			<div class="carousel-caption">
				<div class="row">
					<div class="col-md-3">
						<?php if ( isset($thumb_link) ) : ?>
							<a href="<?php echo $this->Html->url('/tv/view/'.$thumb_link); ?>">
						<?php endif; ?>
						
						<img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$thumbid); ?>" />

						<?php if ( isset($thumb_link) ) : ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="col-md-9">
						<h1><?php echo $media->getTitle(); ?></h1>
						<p><?php echo $media->getSummary(); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
echo $this->element($view_type);
?>