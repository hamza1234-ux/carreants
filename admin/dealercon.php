<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'carrental');

	// initialize variables
	$name = "";
	$address = "";
	$phoneno="";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phoneno = $_POST['phone_no'];
		

		mysqli_query($db, "INSERT INTO dealer (name,address,phone_no) VALUES ('$name', '$address','$phoneno')"); 
		$_SESSION['message'] = "employe saved"; 
		header('location: dealerindex.php');
	}
	if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$phoneno = $_POST['phone_no'];

	mysqli_query($db, "UPDATE dealer SET name='$name', address='$address',phone_no='$phoneno' WHERE id=$id");
	$_SESSION['message'] = "dealer updated!"; 
	header('location: dealerindex.php');
}

if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM dealer WHERE id=$id");
	$_SESSION['message'] = "dealer deleted!"; 
	header('location: dealerindex.php');
}






