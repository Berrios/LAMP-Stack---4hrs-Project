<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome</title>
</head>
<body>
	<h1>Welcome</h1>
	<div id="register">
	<fieldset>
		<legend>Register</legend>
		<form action="<?= base_url().'main/register'?>" method="post">
			<input type="hidden" name="action" value="register">
			<label for="name">Name</label>
			<input type="text" name="name" placeholder="name">
			<label for="alias">Alias</label>
			<input type="text" name="alias" placeholder="Any other names?">
			<label for="email">Email</label>
			<input type="text" name="email" placeholder="my@email.com">
			<label for="register_password">Password</label>
			<input type="text" name="register_password" placeholder="xxxxxxxx">
			<label for="confirm_pass">Confirm Password</label>
			<input type="text" name="confirm_pass" placeholder="xxxxxxxx">
			<label for="bday">Date of Birth: </label>
			<input type="date" name="bday">
			<div class="register_errors"><?= $this->session->flashdata('register_errors');?> </div>
			<input type="submit" value="Register">
			<div class="register_errors"><?= $this->session->flashdata('success');?> </div>
		</form>
	</fieldset>
	</div>
	<div id="login">
	<fieldset>
		<legend>Login</legend>
		<?php $id = $this->session->set_userdata('id');?>
		<form action="<?= base_url().'/main/login/'.$id ?>" method="post">
			<input type="hidden" name="action" value="login">
			<label for="email">Email</label>
			<input type="text" name="email" placeholder="my@email.com">
			<label for="login_passowrd">Password</label>
			<input type="text" name="login_password" placeholder="xxxxxxxx">
			<div class="login_errors"><?= $this->session->flashdata('login_errors');?> </div>
			<input type="submit" value="login">
		</form>
	</fieldset>
	</div>
</body>
</html>