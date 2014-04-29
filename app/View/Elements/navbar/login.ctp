<li class="dropdown">
	<a href="<?php echo $this->Html->url('/users/login'); ?>" class="dropdown-toggle" data-toggle="dropdown">Login <b class="caret"></b></a>
	<ul class="dropdown-menu dropdown-login">
		<p>Login via MyPlex</p>
		<form action="<?php echo $this->Html->url('/users/login'); ?>" autocomplete="off" method="POST">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="username" placeholder="Username">
			</div>
			<span class="help-block"></span>

			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input  type="password" class="form-control" name="password" placeholder="Password">
			</div>

			<span class="help-block"></span>

			<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
		</form>
	</ul>
</li>