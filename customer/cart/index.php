<?php
require __DIR__.'/../../vendor/autoload.php';

require_once __DIR__.'/../../inc/Classes/Product.php';
require_once __DIR__.'/../../inc/Classes/Customer.php';
require_once __DIR__.'/../../inc/Utilities/FileAgent.inc.php';
require_once __DIR__.'/../../inc/Utilities/FileParse.inc.php';
require_once __DIR__.'/../../inc/Utilities/FileComFirebase.php';

$dataToFirebase = new Communication();

//Save Cart for a specific customer
if(isset($_POST['saveCart'])){
    
    $cartInfo = (array)json_decode($_POST["saveCart"]);
    
    $email = str_replace(".","",$cartInfo["email"]);
    $dataToFirebase->insertCart($cartInfo["productsCart"],$email);
    echo "success";
}

//Get Cart by Customer email
elseif(isset($_POST["getCartByEmail"])){
    
    $email = str_replace(".","",$_POST["getCartByEmail"]);   
    $data = $dataToFirebase->getCart($email);    
    echo json_encode($data);
}

else{
}

?>