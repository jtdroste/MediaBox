<div class="media-history panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			Watching history for <?php echo $media->getTitle(); ?> (Watched <?php echo count($history); ?> time<?php echo count($history) != 1 ? 's' : ''; ?>)
		</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-hover sortable">
			<thead>
				<th>Date</th>
				<th>User</th>
				<th>Platform</th>
				<th>IP Address</th>
				<th>Stream Information</th>
				<th>Started</th>
				<th>Paused</th>
				<th>Stopped</th>
				<th>Duration</th>
				<th>Completed</th>
			</thead>
			<tbody>
				<?php
				foreach ( $history AS $data ) :

				// Times
				$viewed   = round(($data['stopped'] - $data['time'] - $data['paused_counter'])/60);
				$paused   = round($data['paused_counter']/60);
				$duration = floor(floor($media->getMedia()->getDuration()/1000)/60);
				$percent  = round(($viewed/$duration)*100);
				
				if ( $percent >= 90 )
					$percent = 100;

				// Steam information
				$stream   = simplexml_load_string($data['xml']);
				?>
				<tr>
					<td><?php echo date('m/d/Y', $data['time']); ?></td>
					<td><?php echo $data['user']; ?></td>
					<td><?php echo $data['platform']; ?></td>
					<td><?php echo $data['ip_address']; ?></td>
					<td>
						<a href="#stream-<?php echo $data['id']; ?>" data-toggle="modal" data-target="#stream-<?php echo $data['id']; ?>">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td><?php echo date('h:i A', $data['time']); ?></td>
					<td><?php echo $paused; ?> minute<?php echo ($paused == 1) ? '' : 's'; ?></td>
					<td><?php echo date('h:i A', $data['stopped']); ?></td>
					<td><?php echo $viewed; ?> minute<?php echo $viewed > 1 ? 's' : ''; ?></td>
					<td><span class="label label-success"><?php echo $percent; ?>%</span></td>
				</tr>

				<div 
					class="modal fade stream-information" 
					id="stream-<?php echo $data['id']; ?>" 
					tabindex="-1" 
					role="dialog" 
					aria-labelledby="stream-<?php echo $data['id']; ?>-title" 
					aria-hidden="true"
				>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="stream-<?php echo $data['id']; ?>-title">
									Stream Information for <strong><?php echo $media->getTitle(); ?></strong> watched by <strong><?php echo $data['user']; ?></strong>
								</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4">
										<h3 class="header">Stream Information</h3>
										<h4>Video</h4>
										<p>Stream Type: <?php echo $stream->TranscodeSession['videoDecision']; ?></p>
										<p>Video Resolution: <?php echo $stream->TranscodeSession['height']; ?>p</p>
										<p>Video Codec: <?php echo $stream->TranscodeSession['videoCodec']; ?></p>
										<p>Video Width: <?php echo $stream->TranscodeSession['width']; ?></p>
										<p>Video Height: <?php echo $stream->TranscodeSession['height']; ?></p>

										<h4>Audio</h4>
										<p>Stream Type: <?php echo $stream->TranscodeSession['audioDecision']; ?></p>
										<p>Audo Codec: <?php echo $stream->TranscodeSession['audioCodec']; ?></p>
										<p>Audio Channels: <?php echo $stream->TranscodeSession['audioChannels']; ?></p>
									</div>
									<div class="col-md-4">
										<h3 class="header">Media Information</h3>
										<p>Container: <?php echo $stream->Media['container']; ?></p>
										<p>Resolution: <?php echo $stream->Media['videoResolution']; ?></p>
										<p>Bitrate: <?php echo $stream->Media['bitrate']; ?></p>
									</div>
									<div class="col-md-4">
										<h3 class="header">A/V Information</h3>
										<h4>Video</h4>
										<p>Height: <?php echo $stream->Media['height']; ?></p>
										<p>Width: <?php echo $stream->Media['width']; ?></p>
										<p>Aspect Ratio: <?php echo $stream->Media['aspectRatio']; ?></p>
										<p>Video Frame Rate: <?php echo $stream->Media['videoFrameRate']; ?></p>
										<p>Video Codec: <?php echo $stream->Media['videoCodec']; ?></p>

										<h4>Audio</h4>
										<p>Audo Codec: <?php echo $stream->Media['audioCodec']; ?></p>
										<p>Audio Channels: <?php echo $stream->Media['audioChannels']; ?></p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-large" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>