<?php
require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../inc/Classes/Product.php';
require_once __DIR__.'/../inc/Classes/Customer.php';
require_once __DIR__.'/../inc/Utilities/FileAgent.inc.php';
require_once __DIR__.'/../inc/Utilities/FileParse.inc.php';
require_once __DIR__.'/../inc/Utilities/FileComFirebase.php';

$dataToFirebase = new Communication();


//Create a new Customer
if(isset($_POST['customer'])){
  
    $data = (array)json_decode($_POST["customer"]);
    $key = str_replace(".","",$data["email"]);
    $dataFromFirebase = $dataToFirebase->getCustomer($key);
    //Check customer not exists
    if($dataFromFirebase == NULL){
        $newCustomer = new Customer();
        $newCustomer->setEmail($data["email"]);
        $newCustomer->setFirst_name($data["first_name"]);
        $newCustomer->setId($data["id"]);
        $newCustomer->setLast_name($data["last_name"]);
        $newCustomer->setPassword($data["password"]);
        
        $dataToFirebase->insertCustomerByEmail($newCustomer);
        
        // echo "The user has been created";
        echo "true";
    }
    //Customer exists
    else{
        echo "The user already exists";
        echo "false";
    }

}

//Login Customer
elseif(isset($_POST['customerInfo'])){
    $info = $_POST["customerInfo"];
    $username = explode(",",$info);
    
    $key = str_replace(".","",$username[0]);
    $dataFromFirebase = $dataToFirebase->getCustomer($key);
    if($dataFromFirebase != NULL){
        if(strcmp($username[1],$dataFromFirebase["password"]) === 0){
            echo $dataFromFirebase["first_name"];
        }
        else {
            echo "false";
            // echo "Username or password not exist";
            // echo "Username exists but not password";
        }
    }
    else {
        echo "false";
        // echo "Username or password not exist";
    }
}

//Get Customer Details
elseif(isset($_POST['customerDetails'])){
    $email = str_replace(".","",$_POST['customerDetails']);
    $customer = $dataToFirebase->getCustomer($email);
    echo json_encode($customer);
}

//Add Customer Details
elseif(isset($_POST['addCustomerDetails'])){
    $data = (array)json_decode($_POST["addCustomerDetails"]);
    $key = str_replace(".","",$data["email"]);
    $dataFromFirebase = $dataToFirebase->getCustomer($key);
    //var_dump($data["shippingAddresses"][0]);
    //Check customer exists
    if(!$dataFromFirebase == NULL){
        $newCustomer = new Customer();
        $newCustomer->setEmail($data["email"]);
        $newCustomer->setFirst_name($data["first_name"]);
        $newCustomer->setId($data["id"]);
        $newCustomer->setLast_name($data["last_name"]);
        $newCustomer->setPassword($data["password"]);
        $newCustomer->setAddress($data["address"]);
        $newCustomer->setCard($data["card"]);
        $dataToFirebase->updateCustomerByEmail($newCustomer);
        
        // echo "The user has been created";
        echo "true";
    }
    //Customer does not exist
    else{
        echo "false";
    }
}

//Change token admin
elseif(isset($_POST['updateToken'])){
    $data = explode(",",$_POST['updateToken']);
    $email = $data[0];
    $token = $data[1];
    echo $dataToFirebase->updateToken($email, $token);
}

    


?>