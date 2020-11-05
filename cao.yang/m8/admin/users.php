<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "../lib/php/functions.php";

$filename = "../data/users.json";
$users = file_get_json("$filename");

$empty_user = (object)[
	"name"=>"",
	"type"=>"",
	"email"=>"",
	"classes"=>[]

];


//print_p([$_GET,$_POST]);


if(isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "update":
			$users[$_GET['id']]->name = $_POST['user-name'];
			$users[$_GET['id']]->type = $_POST['user-type'];
			$users[$_GET['id']]->email = $_POST['user-email'];
			$users[$_GET['id']]->classes = explode(",", $_POST['user-classes']);


			file_put_contents($filename, json_encode($users));
			header("location:{$_SERVER['PHP_SELF']}?id={$_GET['id']}");
			break;
		case "create":
			$empty_user->name = $_POST['user-name'];
			$empty_user->type = $_POST['user-type'];
			$empty_user->email = $_POST['user-email'];
			$empty_user->classes = explode(",", $_POST['user-classes']);

			$id = count($users);

			$users[] = $empty_user;

			file_put_contents($filename, json_encode($users));
			header("location:{$_SERVER['PHP_SELF']}?id=$id");
			break;
		case "delete":
			break;


	}
}



function showUserPage($user) {

$id = $_GET['id'];
$addoredit = $id == "new" ? "Add" : "Edit";
$createorupdate = $id == "new" ? "create" : "update";
$classes = implode(", ", $user->classes);



//heredoc
echo <<<HTML
<nav class="nav nav-crumbs">
	<ul>
		<li><a href="users.php">Back</a></li>
	</ul>	
</nav>

<form method="post" action="{$_SERVER['PHP_SELF']}?id=$id&action=$createorupdate">
	<h2>$addoredit User</h2>
	<div class="form-control">
		<label class="form-label" for="user-name">Name</label>
		<input type="text" placeholder="Enter the User Name" value="$user->name" class="form-input" name="user-name" id="user-name">
	</div>
	<div class="form-control">
		<label class="form-label" for="user-type">Type</label>
		<input type="text" placeholder="Enter the User Type" value="$user->type" class="form-input" name="user-type" id="user-type">
	</div>
	<div class="form-control">
		<label class="form-label" for="user-email">Email</label>
		<input type="text" placeholder="Enter the User Email" value="$user->email" class="form-input" name="user-email" id="user-email">
	</div>
	<div class="form-control">
		<label class="form-label" for="user-classes">Classes</label>
		<input type="text" placeholder="Enter the User Classes, comma separated" value="$classes" class="form-input" name="user-classes" id="user-classes">
	</div>
	<div class="form-control">
		<input type="submit" class="form-button" value="Save Changes">
	</div>
</form>
HTML;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>User Admin Page</title>

	
	<link rel="stylesheet" type="text/css" href="../lib/css/styleguide.css">

	<link rel="stylesheet" type="text/css" href="../css/storetheme.css">
	
</head>
<body>

	<header class="navbar">
		<div class="container display-flex">
			<div class="flex-none">
				<h1>User Admin</h1>
			</div>
			<div class="flex-stretch"></div>
			<nav class="nav nav-flex flex-none">
				<ul>
					<li><a href="<?= $_SERVER['PHP_SELF'] ?>">User List</a></li>
					<li><a href="<?= $_SERVER['PHP_SELF'] ?>?id=new">Add New User</a></li>
				</ul>
			</nav>
		</div>
		
	</header>

	<div class="container">
		
		<div class="card soft">

			<?php

			if(isset($_GET['id'])) {
				showUserPage($_GET['id'] == "new" ? $empty_user : $users[$_GET['id']]);
			} else {



			?>
			<h2>User List</h2>

			<nav class="nav">
				<ul>
			<?php

			for($i=0;$i<count($users);$i++){
				echo "<li>
					<a href='users.php?id=$i'>{$users[$i]->name}</a>
				</li>";
			}

			?>
				</ul>
			</nav>

			<?php } ?>
		</div>
	</div>

</body>
</html>