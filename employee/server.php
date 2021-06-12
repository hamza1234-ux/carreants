<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'carrental');

	// initialize variables
	$name = "";
	$address = "";
	$phoneno="";
	$email="";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phoneno = $_POST['phone_no'];
		$email = $_POST['email'];

		mysqli_query($db, "INSERT INTO employees (name, address,phone_no,email) VALUES ('$name', '$address','$phoneno','$email')"); 
		$_SESSION['message'] = "employe saved"; 
		header('location: index.php');
	}
	if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$phoneno = $_POST['phone_no'];
	$email = $_POST['email'];

	mysqli_query($db, "UPDATE employees SET name='$name', address='$address',phone_no='$phoneno',email='$email' WHERE id=$id");
	$_SESSION['message'] = "Employee updated!"; 
	header('location: index.php');
}

if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM employees WHERE id=$id");
	$_SESSION['message'] = "Employee deleted!"; 
	header('location: index.php');
}




