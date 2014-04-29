<!--<li><a href="#">Watch Now!</a></li>-->
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $username; ?> <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="#">Notifications</a></li>
		<li class="divider"></li>
		<li><a href="#">Account Settings</a></li>
		<li><a href="#">Privacy</a></li>
		<li><a href="<?php echo $this->Html->url('/users/logout/'.$logout_key); ?>">Log Out</a></li>
	</ul>
</li>