<?php 
// Product Details 
// Minimum amount is $0.50 US 
$itemName = "Demo Product"; 
$itemNumber = "PN12345"; 
$itemPrice = 25; 
$currency = "USD"; 
 
// Stripe API configuration  
define('STRIPE_API_KEY', 'sk_test_51IzjGCLGGre7P6tESSOoOrZXJcojXNYmtVaTZ9c5PaupMYtjCgTY29nKiXiJvBiVpHKeXfvsD0Su7Os31h1Z6Erv007ltUwNkC'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51IzjGCLGGre7P6tEygiJ8UF7KNNCCywrhOfu4xNi5kO3Zc9lypOzBCXk7NOCPFeyqiIJJhsb8A42oV24Q8hEfduv00uQ08gagF'); 
  
// Database configuration  
define('DB_HOST', "localhost"); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'carrental');
?>