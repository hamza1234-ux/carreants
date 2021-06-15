<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','carrental');

$currency = "USD"; 
 
// Stripe API configuration  
// define('STRIPE_API_KEY', 'sk_test_51IzjGCLGGre7P6tESSOoOrZXJcojXNYmtVaTZ9c5PaupMYtjCgTY29nKiXiJvBiVpHKeXfvsD0Su7Os31h1Z6Erv007ltUwNkC'); 
// define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51IzjGCLGGre7P6tEygiJ8UF7KNNCCywrhOfu4xNi5kO3Zc9lypOzBCXk7NOCPFeyqiIJJhsb8A42oV24Q8hEfduv00uQ08gagF');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>