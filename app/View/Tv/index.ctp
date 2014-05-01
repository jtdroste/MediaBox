<div class="jumbotron">
	<h1>TV Show List</h1>
	<p>There is currently <?php echo count($shows); ?> TV Show<?php echo (count($shows) > 1 ? 's' : ''); ?> on this media library.</p>
</div>

<ul class="list-group media-list">
	<?php foreach ( $shows AS $show ) : ?>
	<li>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<a href="<?php echo $this->Html->url('/tv/view/'.$show['show']->getRatingKey()); ?>">
							<img src="<?php echo $this->Html->url('/home/plex_proxy/thumb/'.$show['show']->getRatingKey()); ?>" />
						</a>
					</div>
					<div class="col-md-6">
						<h3><?php echo $show['show']->getTitle(); ?></h3>
						<p><?php echo $show['show']->getSummary(); ?></p>

						<p><strong>Seasons Available</strong>: <?php echo count($show['seasons']); ?></p>
					</div>
					<div class="col-md-3 buttons">
						<div class="btn-group-vertical">
							<a href="<?php echo $this->Html->url('/tv/view/'.$show['show']->getRatingKey()); ?>">
								<button type="button" class="btn btn-primary btn-lg">More Information</button>
							</a>
							<?php foreach ( $show['seasons'] AS $season ) : ?>
							<a href="<?php echo $this->Html->url('/tv/view/'.$show['show']->getRatingKey()); ?>/<?php echo $season->getRatingKey(); ?>">
								<button type="button" class="btn btn-info btn-lg"><?php echo $season->getTitle(); ?></button>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>