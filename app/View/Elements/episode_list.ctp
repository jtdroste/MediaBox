<div class="media-list panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Episode List
		</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-hover sortable">
			<thead>
				<th class="ep-number">Episode Number</th>
				<th class="ep-name">Episode Name</th>
				<th class="ep-summary">Episode Summary</th>
			</thead>
			<tbody>
				<?php foreach ( $season->getEpisodes() AS $data ) : ?>
				<tr class="clickable">
					<td class="ep-number">
						<a href="<?php echo $this->Html->url('/tv/view/'.$media->getRatingKey().'/'.$season->getRatingKey().'/'.$data->getRatingKey()); ?>">
							<?php echo $data->getIndex(); ?>
						</a>
					</td>
					<td class="ep-name"><?php echo $data->getTitle(); ?></td>
					<td class="ep-summary"><?php echo $data->getSummary(); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
$(document).ready(function() {
	$('.clickable').click(function() {
		var href = $(this).find('a').attr('href');
		if ( href ) {
			window.location = href;
		}
	})
});
</script>