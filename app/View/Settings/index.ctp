<?php
$this->Html->script('bootstrap-editable.min.js', array('inline' => false));
$this->Html->css('bootstrap-editable.css', null, array('inline' => false));

$setupValues = array(
	'sickbeard_enabled', 'couchpotato_enabled', 'plex_enabled', 'plexwatch_enable'
);
?>

<div class="jumbotron">
	<h2>MediaBox Config</h2>
	<?php if ( !$is_setup ) : ?>
	<p>Hi there! It seems you are seting up MediaBox for the first time! Please edit the rows that are highlighted, as those are the ones that need to be edited first.</p>
	<?php endif; ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Value</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $settings AS $setting ) : ?>
			<tr<?php echo (!$is_setup && in_array($setting['Config']['name'], $setupValues) ? ' class="info"' : ''); ?>>
				<td><?php echo $setting['Config']['name']; ?></td>
				<td>
					<a 
						class="settings-editable" 
						href="#" 
						id="<?php echo $setting['Config']['name']; ?>" 
						data-type="text" 
						data-pk="<?php echo $setting['Config']['id']; ?>" 
						data-url="<?php echo $this->Html->url('/settings/save'); ?>" 
						data-title="Enter new value"
					>
						<?php echo $setting['Config']['value']; ?>
					</a>
				</td>
				<td><?php echo $setting['Config']['description']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
    $('.settings-editable').editable();
});
</script>