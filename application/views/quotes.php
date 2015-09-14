<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome</title>
	<style>
	 #quotes, #favorites{
	 	width: 300px;
	 	display: inline-block;
	 }
	</style>

</head>
<body>
	 <?php 
	 $id = $this->session->userdata('id'); 
	 ?>
	<a href="<?php echo base_url().'main/logout';?>">Logout</a>

	<h1>Welcome, <?php echo $id = $this->session->userdata('alias');?> !</h1>
	<fieldset id="quotes">
		<legend>Quotes:</legend>
		
		<?php foreach ($get_quotes as $row){
			echo '<p>'.$row['quote'].'</p>';
			echo '<p>'.$row['person'].'</p>';
			echo '<button><a href="'.base_url().'main/add_to_list/'.$row['id'].'">Add to My List</a></button>';
		 }?>
	</fieldset>

	<fieldset id="favorites" >
		<legend>Your Favorites:</legend>
		<?php foreach ($get_favorites as $row) {
			echo '<p>'.$row['quote'].'</p>';
			echo '<p>'.$row['person'].'</p>';
			echo '<button><a href="'.base_url().'main/unfavored/'.$row['id'].'">Remove from Favorites</a></button>';
		 }?>
	</fieldset>

	<fieldset >
		<legend>Contribute a Quote:</legend>
		
		<form action="<?php echo base_url().'main/add_quote/'.$id;?>" method="post">
			<label for="person">Quoted By: </label>
			<input type="text" name="person">
			<label for="quote">Message: </label>
			<textarea name="quote" id="" cols="30" rows="10"></textarea>
			<input type="submit" value="Submit">
		</form>
		<div></div>
	</fieldset>


</body>
</html>