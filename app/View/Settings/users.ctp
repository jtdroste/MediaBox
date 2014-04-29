<div class="jumbotron">
	<h2>MediaBox User Setup</h2>
	<?php if ( !$is_setup ) : ?>
	<p>Hi there! To get rid of this message, and all other messages telling you to setup MediaBox, please edit your user (admin).</p>
	<?php endif; ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>UserID</th>
				<th>Username</th>
				<th>User Permissions</th>
				<th>Edit User</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ( $users AS $user ) :
					$permissions = array();
					foreach ( $user['Permission'] AS $perm ) {
						$permissions[] = $perm['name'];
					}
			?>
			<tr>
				<td><?php echo $user['User']['id']; ?></td>
				<td><?php echo $user['User']['username']; ?></td>
				<td><?php echo implode(', ', $permissions); ?></td>
				<td><?php echo $this->Html->link('Edit User', '/settings/user/'.$user['User']['id']); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>