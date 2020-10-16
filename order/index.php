<?php
require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../inc/Classes/Product.php';
require_once __DIR__.'/../inc/Classes/Order.php';
require_once __DIR__.'/../inc/Classes/ProductOrdered.php';
require_once __DIR__.'/../inc/Classes/Customer.php';
require_once __DIR__.'/../inc/Classes/Notification.php';

//Utilities
require_once __DIR__.'/../inc/Utilities/FileAgent.inc.php';
require_once __DIR__.'/../inc/Utilities/FileParse.inc.php';
require_once __DIR__.'/../inc/Utilities/FileComFirebase.php';
require_once __DIR__.'/../inc/Utilities/configGoogleStorage.php';

$dataToFirebase = new Communication();

//$dataToFirebase->sendFCM("fggI92lgRjQ:APA91bFEJ4GYSEgXfoKgQNUlWvz5_vlhA_du1MccyuR2QVZXtuFFgZxdVacUYuH5cXpf-LaL_CPhS3TutBQRfDPITl1-hqlhtaJ8q9talZu8dg53uOQEB3BLV4XP8YZ4lvbCHG_7A5bY", "test", "testeando");

//Insert new order
if(isset($_POST['newOrder'])){
    $tokenAdmin = $dataToFirebase->getTokenByEmail("admin@gmail.com");
    $data = (array)json_decode($_POST["newOrder"]);
    $isValid = true;
    $productsNotAvailable = "";
    
    //verify inventory before placing order
    $products = (array) $data["productsOrdered"];
    for($i = 0; $i < count($products); $i++){
        $prod = (array)$products[$i];
        $response = $dataToFirebase->updateInventorylevel($prod["id"], $prod["quantity"]);
        if(strcmp($response, "valid") != 0){
            $productsNotAvailable = $response;
            $isValid = false;
        break;
        }               
    }
    
    if($isValid == true){
        $datePlusFiveDays = strtotime("+5 day");
    
        //Current date plus five days
        $shippingDate = date('m/d/Y', $datePlusFiveDays);
        
        $answer = $dataToFirebase->qtyOrder();
            if($answer == null) {
                //this is for the first order to create idx
                $newOrder = new Order();
                $newOrder->setCustomer($data["customer"]);
                $newOrder->setCustomer_message("Please ship ASAP");
                $newOrder->setDate_created($data["date_created"]);
                $newOrder->setDate_shipped($shippingDate);
                $newOrder->setId(0);
                $newOrder->setPayment_method("Credit Card");
                $newOrder->setProductsOrdered($data["productsOrdered"]);
                $newOrder->setShipping_cost($data["shipping_cost"]);
                $newOrder->setStatus("Awaiting Fulfillment");
                $newOrder->setTotal($data["total"]);
                $newOrder->setTotal_tax($data["total_tax"]);
            
                $dataToFirebase->insertOrder($newOrder,0);
                
                //Remove Cart
                $customerEmail = (array)$newOrder->getCustomer();
                $customerEmail = $customerEmail["email"];
                $email = str_replace(".","",$customerEmail);
                $dataToFirebase->deleteCart($email);
                
                echo "true";
            }
            else {
                $newOrder = new Order();
                $newOrder->setCustomer($data["customer"]);
                $newOrder->setCustomer_message("Please ship ASAP");
                $newOrder->setDate_created($data["date_created"]);
                $newOrder->setDate_shipped($shippingDate);
                $newOrder->setId($answer);
                $newOrder->setPayment_method("Credit Card");
                $newOrder->setProductsOrdered($data["productsOrdered"]);
                $newOrder->setShipping_cost($data["shipping_cost"]);
                $newOrder->setStatus("Awaiting Fulfillment");
                $newOrder->setTotal($data["total"]);
                $newOrder->setTotal_tax($data["total_tax"]);
            
                $index = $dataToFirebase->qtyOrder();
                $dataToFirebase->insertOrder($newOrder,$index);
                
                //Remove Cart
                $customerEmail = (array)$newOrder->getCustomer();
                $customerEmail = $customerEmail["email"];
                $email = str_replace(".","",$customerEmail);
                $dataToFirebase->deleteCart($email);
                
                echo "true";
            }
            $dataToFirebase->sendFCM($tokenAdmin, "Order ".$newOrder->getId(), "A new order has been placed.");
            
            //save notification
            $email = "admin@gmail.com";
            $title = "Order ".$newOrder->getId();
            $message = "A new order has been placed.";
            $date = date('m/d/Y H:i:s', strtotime("now"));
            $idx = $dataToFirebase->qtyNotifications($email);
            $newNotification = new Notification();
            $newNotification->setDateSent($date);
            $newNotification->setMessage($message);
            $newNotification->setRead("false");
            $newNotification->setTitle($title);
        
            $dataToFirebase->insertNotification($email, $idx, $newNotification);
    }
    else{
        return $productsNotAvailable;
    }
}

//Get Order by Email
elseif(isset($_POST['getOrderByEmail'])){
    $email = $_POST['getOrderByEmail'];
    $order = $dataToFirebase->getOrderByCustomer($email);
    for($index = (count($order)-1); $index >=0;$index-- ){
        $arrayOfOrders []= $order[$index];
    }
    echo json_encode($arrayOfOrders);
}

//Get Order By Id
elseif(isset($_POST['getOrderById'])){
    $order = $dataToFirebase->getOrderById($_POST['getOrderById']);
    echo json_encode($order);
}

//Send notification to customer if order changes
elseif(isset($_POST['statusChange'])){
    $data = explode(',', $_POST['statusChange']);
    $status = $data[0];
    $tokenCustomer = $data[1];
    $email = $data[2];
    $orderId = $data[3];
    $dataToFirebase->updateOrderStatus($orderId, $status);
    $dataToFirebase->sendFCM($tokenCustomer, "Order ".$orderId, "Status has changed to ".$status);

    //save notification
    $title = "Order ".$orderId;
    $message = "Status has changed to ".$status;
    $date = date('m/d/Y H:i:s', strtotime("now"));
    $idx = $dataToFirebase->qtyNotifications($email);
    $newNotification = new Notification();
    $newNotification->setDateSent($date);
    $newNotification->setMessage($message);
    $newNotification->setRead("false");
    $newNotification->setTitle($title);
        
    $dataToFirebase->insertNotification($email, $idx, $newNotification);   
    echo "true";
}

//Get all orders
elseif(isset($_POST['orders'])){
    $orders = $dataToFirebase->getOrders();
    for($index = (count($orders)-1); $index >=0;$index-- ){
        $arrayOfOrders [] = $orders[$index];
    }
    echo json_encode($arrayOfOrders);
}


?>