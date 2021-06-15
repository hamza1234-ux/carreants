<?php 
// Include configuration file  
require_once 'config.php'; 
 // require_once'../includes/config.php';

 
$payment_id = $statusMsg = ''; 
$ordStatus = 'error';
$itemPrice = $_POST['price'];
$itemName = $_POST['title'];
$itemNumber = $_POST['id'];
$couponCode = $_POST['coupon_code'];
 
// Check whether stripe token is not empty 
if(!empty($_POST['stripeToken'])){ 
     
    // Retrieve stripe token, card and user info from the submitted form data 
    $token  = $_POST['stripeToken']; 
    $name = $_POST['name']; 
    $email = $_POST['email']; 
     
    // Include Stripe PHP library 
    require_once 'stripe-php/init.php'; 
     
    // Set API key 
    \Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
     
    // Add customer to stripe 
    try {  
        $customer = \Stripe\Customer::create(array( 
            'email' => $email, 
            'source'  => $token 
        )); 
    }catch(Exception $e) {
        $api_error = $e->getMessage();  
    } 
     
    if(empty($api_error) && $customer){  
        include('../includes/config.php');

        // query where select coupon code from coupon table where status is 1
        $couponsql = "SELECT discount_percentage FROM coupons WHERE status= 1 and code=:coupon_code";
        $couponQuery = $dbh->prepare($couponsql);
        $couponQuery->bindParam(':coupon_code',$couponCode, PDO::PARAM_STR);
        $couponQuery->execute();
        $couponresult=$couponQuery->fetchAll(PDO::FETCH_OBJ);
        // var_dump($couponresult->discount_percentage);die();

        if (count($couponresult) > 0) {
            $discount = ($couponresult[0]->discount_percentage / 100) * $itemPrice;
            
            $itemPriceCents = $itemPrice - $discount;
            // echo $itemPriceCents;die();
        }
        else {
            $itemPriceCents = ($itemPrice); 
        }

        
         
        // Convert price to cents 
        
         
        // Charge a credit or a debit card 
        try {  
            $charge = \Stripe\Charge::create(array( 
                'customer' => $customer->id, 
                'amount'   => $itemPriceCents, 
                'currency' => $currency, 
                'description' => $itemName 
            )); 
        }catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 
         
        if(empty($api_error) && $charge){ 
         
            // Retrieve charge details 
            $chargeJson = $charge->jsonSerialize(); 
         
            // Check whether the charge is successful 
            if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){ 
                // Transaction details  
                $transactionID = $chargeJson['balance_transaction']; 
                $paidAmount = $chargeJson['amount']; 
                $paidAmount = ($paidAmount); 
                $paidCurrency = $chargeJson['currency']; 
                $payment_status = $chargeJson['status'];
                // var_dump($chargeJson);die();
                 
                // Include database connection file  
                include_once 'dbConnect.php'; 
                 
                // Insert tansaction data into the database 
                $sql = "INSERT INTO orders(name,email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,created,modified, code) VALUES('".$name."','".$email."','".$itemName."','".$itemNumber."','".$itemPrice."','".$currency."','".$paidAmount."','".$paidCurrency."','".$transactionID."','".$payment_status."',NOW(),NOW(),'".$couponCode . "')"; 
                // echo  $sql;die();
                $insert = $db->query($sql); 
                $payment_id = $db->insert_id; 
                
                 
                // If the order is successful 
                if($payment_status == 'succeeded'){ 
                    $ordStatus = 'success'; 
                    $statusMsg = 'Your Payment has been Successful!'; 
                }else{ 
                    $statusMsg = "Your Payment has Failed!"; 
                } 
            }else{ 
                $statusMsg = "Transaction has been failed!"; 
            } 
        }else{ 
            $statusMsg = "Charge creation failed! $api_error";  
        } 
    }else{  
        $statusMsg = "Invalid card details! $api_error";  
    } 
}else{ 
    $statusMsg = "Error on form submission."; 
} 
?>


<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<head>
    <style>
        body {
    margin-top: 20px;
}
    </style>
</head>
<div class="container">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <address>
                       <?php if(!empty($payment_id)){ ?>
            <h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>
            
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
            <p><b>Transaction ID:</b> <?php echo $transactionID; ?></p>
            <p><b>Paid Amount:</b> <?php echo $paidAmount.' '.$paidCurrency; ?></p>
            <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>
            
            <h4>Product Information</h4>
            <p><b>Name:</b> <?php echo $itemName; ?></p>
            <p><b>Price:</b> <?php echo $itemPrice.' '.$currency; ?></p>
        <?php }else{ ?>
            <h1 class="error">Your Payment has Failed</h1>
        <?php } ?>
                    </address>
                </div>
                
            </div>
            <div class="row">
                <div class="text-center">
                    <h1>Receipt</h1>
                </div>
                </span>
                <table class="table table-hover">
                    <tbody>
                      <tr>
                <td>
                    <p><b>Reference Number:</b></p>
                </td>
                <td>
                    <p><?php echo $payment_id; ?></p>
                </td>
            </tr>
<tr>
                <td>
                 <p><b>Transaction ID:</b></p>
                </td>
                <td>
                    <p><?php echo $transactionID; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Paid Amount:</b></p>
                </td>
                <td>
                    <p><?php echo $paidAmount.' '.$paidCurrency; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                 <p><b>Payment Status:</b></p>
                </td>
                <td>
                    <p><?php echo $payment_status; ?></p>
                </td>
            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>