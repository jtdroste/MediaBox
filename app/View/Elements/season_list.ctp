<div class="media-list panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Season List
		</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<?php foreach ( $media->getSeasons() AS $data) : ?>
				<a href="<?php echo $this->Html->url('/tv/view/'.$media->getRatingKey().'/'.$data->getRatingKey()); ?>">
					<div class="thumbnail">
						<img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$data->getRatingKey()); ?>" />
						<div class="caption">
							<h3><?php echo $data->getTitle(); ?></h3>
						</div>
					</div>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>