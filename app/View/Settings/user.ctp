<?php
$this->Html->script('bootstrap-editable.min.js', array('inline' => false));
$this->Html->css('bootstrap-editable.css', null, array('inline' => false));
$perms = array();
foreach ( $user['Permission'] AS $perm ) {
	$perms[] = $perm['id'];
}
?>
<div class="jumbotron">
	<h2>MediaBox User Setup - Editing User</h2>
	<p class="edituser-info"><strong>NOTE</strong>: MyPlex authentication is automatically enabled when the user's password is EMPTY. To enable a user to use MyPlex authentication, simply edit the password to nothing.</p>
	<p class="edituser-info"><strong>NOTE</strong>: If using MyPlex authentication, the username MUST be the same as the MyPlex username!</p>
	
	<br />

	<p>User Permissions:</p>
	<p class="edituser-info"><strong>Administrator</strong>: Can access everything.<p>
	<p class="edituser-info"><strong>Super User</strong>: Automatic request approval</p>
	<p class="edituser-info"><strong>User</strong>: Has login access to MediaBox</p>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Data</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ( $user['User'] AS $k => $v ) :
					if ( in_array($k, array('id', 'homepage', 'last_seen')) ) continue;
			?>
			<tr>
				<td><?php echo $k; ?></td>
				<td>
					<a 
						class="settings-editable" 
						href="#" 
						id="<?php echo $k; ?>" 
						data-type="text" 
						data-pk="<?php echo $user['User']['id']; ?>" 
						data-url="<?php echo $this->Html->url('/settings/user/'.$user['User']['id']); ?>" 
						data-title="Enter new value"
					>
						<?php echo ($k != 'password' || empty($v)) ? $v : 'Password Hidden'; ?>
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr>
				<td>permissions</td>
				<td>
					<a 
						class="permissions-editable" 
						href="#" 
						id="permissions" 
						data-type="checklist" 
						data-pk="<?php echo $user['User']['id']; ?>" 
						data-url="<?php echo $this->Html->url('/settings/user/'.$user['User']['id']); ?>" 
						data-title="Select Permissions" 
						data-value="<?php echo implode(',', $perms); ?>"
					>
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
    $('.settings-editable').editable();

    $('.permissions-editable').editable({
    	source: [
    		<?php foreach ( $permissions AS $perm ) : ?>
    		{value: <?php echo $perm['Permission']['id']; ?>, text: '<?php echo $perm['Permission']['name']; ?>'},
    		<?php endforeach; ?>
    	]
    }); 
});
</script>