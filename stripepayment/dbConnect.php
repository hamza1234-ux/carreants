 <?php
 // Connect with the database  
 $db = new mysqli(db_host, db_username, db_password, db_name);  
  
// Display error if failed to connect  
if ($db->connect_errno) {  
    printf("Connect failed: %s\n", $db->connect_error);  
    exit();  
 } 