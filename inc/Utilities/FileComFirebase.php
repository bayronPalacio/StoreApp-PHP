<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Communication {
    
    protected $database;
    protected $dbnameCustomer = 'customer';
    protected $dbnameProduct = 'product';
    protected $dbnameProductOrder = 'productOrder';
    protected $dbnameOrder = 'order';
    protected $dbnameCart = 'cart';
    protected $dbnameNotification = 'notification';

    public function __construct(){

        $acc = ServiceAccount::fromJsonFile(__DIR__ . '/research-project-dc-0c3ba7f477ae.json');//json test1
        // $acc = ServiceAccount::fromJsonFile(__DIR__ . '/phpandroid-954d76d1b771.json');//json test2
        $firebase = (new Factory)->withServiceAccount($acc)
        ->withDatabaseUri('https://research-project-dc.firebaseio.com')//firebase test1
        // ->withDatabaseUri('https://phpandroid.firebaseio.com')//firebase test2
        ->create();
        $this->database = $firebase->getDatabase();
    }
    
    //**************************************************  CUSTOMER ************************************************************************
    
    public function insertCustomerPush(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else{
            var_dump($data);
            $this->database->getReference()->getChild($this->dbnameCustomer)->push($data);
            // getChild($key)->set($value);
            return TRUE;
        }       
    }
    
    public function insertCustomerByEmail(Customer $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else{                        
            $this->database->getReference()->getChild($this->dbnameCustomer)->getChild(str_replace(".","",$data->getEmail()))->set($data);
            return TRUE;
        }       
    } 
    
    public function insertCustomer(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        foreach ($data as $key => $value){
            $this->database->getReference()->getChild($this->dbnameCustomer)->getChild($key)->set($value);
        }
        return TRUE;
    }

    public function getCustomer(string $email = NULL){    
        if (empty($email) || !isset($email)) { return FALSE; }
        if ($this->database->getReference($this->dbnameCustomer)->getSnapshot()->hasChild($email)){
            return $this->database->getReference($this->dbnameCustomer)->getChild($email)->getValue();
        } else {
            return FALSE;
        }
    }

    public function updateCustomerByEmail(Customer $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else{                        
            $this->database->getReference()->getChild($this->dbnameCustomer)->getChild(str_replace(".","",$data->getEmail()))->set($data);
            return TRUE;

            $reference = $this->database->getReference($this->dbname."/".$data['record_id'])->update($data);
        }       
    }
    
    public function getTokenByEmail(string $email = NULL){
        return $this->database->getReference($this->dbnameCustomer)->getChild(str_replace(".","",$email))->getChild("id")->getValue();
    }

    public function updateToken(string $email, string $token){ 
        // $this->database->getReference($this->dbNameCustomer)->getChild("admin@gmailcom")->getChild("id")->set($token);
        return $this->database->getReference($this->dbnameCustomer)->getChild(str_replace(".","",$email))->getChild("id")->set($token);
    }

    
     //**************************************************  PRODUCTS ************************************************************************
    
    public function getProducts(){          
        return $this->database->getReference($this->dbnameProduct)->getSnapshot()->getValue();
    }
    
    public function getProductById($id){                 
        return $this->database->getReference($this->dbnameProduct)->getChild("products")->orderByChild("id")->equalTo((int)$id)->getValue();
    }

    public function insertProducts(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else{
            $this->database->getReference()->getChild($this->dbnameProduct)->set($data);
            return TRUE;
            }
    }
    
    public function addAProduct($item,$index){                 
        return $this->database->getReference($this->dbnameProduct)->getChild("products")->getChild($index)->set($item);
    }
    
    public function updateProduct(Product $data,$index) {
        $this->database->getReference()->getChild($this->dbnameProduct)->getChild("products")->getChild($index[0])->set($data);
    }
   
    public function getQtyProducts(){          
        $allProducts = $this->database->getReference($this->dbnameProduct)->getSnapshot()->getChild("products")->getValue();
        return count($allProducts);
    }

    public function updateInventorylevel($id, $qty){
        $product = $this->database->getReference($this->dbnameProduct)->getChild("products")->orderByChild("id")->equalTo((int)$id)->getValue();
        $index = key($product);
        $updInvLevel = $product[$index]["inventory_level"] - $qty;
        if($updInvLevel == 0){
            $product[$index]["availability"] = "false";
            $product[$index]["inventory_level"] = $updInvLevel;
            $this->database->getReference($this->dbnameProduct)->getChild("products")->getChild($index)->set($product[$index]);
            return "valid";
        }           
        elseif($updInvLevel > 0){
            $product[$index]["inventory_level"] = $updInvLevel;
            $this->database->getReference($this->dbnameProduct)->getChild("products")->getChild($index)->set($product[$index]);
            return "valid";
        }           
        elseif($updInvLevel < 0)
            echo $product[$index]["name"]."\nQuantity Available: ".$product[$index]["inventory_level"];
    }

    public function insertProductOrder(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }
        foreach ($data as $key => $value){
            $this->database->getReference()->getChild($this->dbnameProductOrder)->getChild($key)->set($value);
        }
        return TRUE;
    }  
    
    public function searchProductsByName($word,$availableProducts){
        $searchArray = null;
        if($availableProducts != null){
            foreach($availableProducts as $products){
                if(stristr($products['name'],$word) != null){
                    $searchArray [] = $products;
                }                
            }
            if($searchArray != null)
            {
                $finalProducts["products"] = $searchArray;
                echo json_encode($finalProducts);
            }
            else echo "no results";
        }
        else echo "no products";
    }

    public function getProductsAvailables(){
        return $this->database->getReference($this->dbnameProduct)->getChild("products")->orderByChild("availability")->equalTo(true)->getValue();
    }
       
    //**************************************************  ORDER   ************************************************************************
    public function insertOrder(Order $data,$index) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else {
            $this->database->getReference()->getChild($this->dbnameOrder)->getChild($index)->set($data);
            return TRUE;
        }
    }
    
    public function qtyOrder() {
        $allOrders = $this->database->getReference($this->dbnameOrder)->getSnapshot()->getValue();
        if($allOrders == null){
            return null;
        }
        else {
            return count($allOrders);
        }
    }
    
    public function getOrderByCustomer(string $email) {
        $allOrders = $this->database->getReference($this->dbnameOrder)->getSnapshot()->getValue();
        $orders = null;
        foreach($allOrders as $order){
            if(strcmp($order["customer"]["email"],$email) == 0){
                $orders[] = $order;
            }
        }
        return $orders;
    }

    public function getOrderById(int $id) {
        return $this->database->getReference($this->dbnameOrder)->getChild($id)->getValue();
    }
    
    public function getOrders() {
        return $this->database->getReference($this->dbnameOrder)->getSnapshot()->getValue();
    }

    public function updateOrderStatus(int $orderId, string $status){
        $this->database->getReference()->getChild($this->dbnameOrder)->getChild($orderId)->getChild("status")->set($status);
    }
    
    //**************************************************  CART   ************************************************************************
    public function insertCart(array $data,$email) {
        if (empty($data) || !isset($data)) { return FALSE; }
        else {
            $this->database->getReference()->getChild($this->dbnameCart)->getChild($email)->set($data);
            return TRUE;
        }
    }
    
    public function getCart($email) {
        if (empty($email) || !isset($email)) { return FALSE; }
        else {
            return $this->database->getReference($this->dbnameCart)->getChild($email)->getValue();
        }
    }
    
    public function deleteCart($email){
        if (empty($email) || !isset($email)) { return FALSE; }
        else {
            $data = null;
            return $this->database->getReference($this->dbnameCart)->getChild($email)->set($data);
        }
    }

    //**************************************************  NOTIFICATIONS   ************************************************************************

    function request($url, $body, $method = "POST", $header = "Content-type: application/x-www-form-urlencoded\r\n")
    {
        switch ($method) {
            case 'POST':
            case 'GET':
            case 'PUT':
            case 'DELETE':
                break;
            default:
                $method = 'POST';
                break;
        }
        $options = array(
            'http' => array(
            'header' => "$header",
            'method' => "$method",
            'content' => $body
            )
        );
        $context = stream_context_create($options);
        $data = file_get_contents($url, false, $context);
        $data = json_decode($data, true);
        return $data;   
    }

    function sendFCM($id, $title, $body) {
        $API_KEY = "AAAA_ILXKAI:APA91bEt-HnOmgWfSPM5ckOvizUV3yrOaAPpoTpT2lqgKMCNqZ0rRdlEJAdV4EWOAixjhPatbD-p4mI5SPeCrAOgPC2cfAFR0Gy_diozq6Hp7GwOPVz8HKEv3vEW9EunYhq5tFEf3caX";
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = [
            'body'              =>  $body,
            'title'             => $title,
            'notification_type' =>  'Test'
        ];
    
        $notification = [
            'body' => $body,
            'title' => $title,
    
        ];
        $fields = array (
            // 'registration_ids' => array (
            //         $id
            // ),
            'to' => $id,
            'notification'      => $notification,
            'data'              => $message,
            'priority'          => 'high',            
        );
        $fields = json_encode ( $fields );
        $headers = array (
            'Authorization: key=' . $API_KEY,
            'Content-Type: application/json'
        );
    
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
        $result = curl_exec ( $ch );
    
        curl_close ( $ch );
        return 'success';
    }

    public function insertNotification($email, $index, $newNotification) {
        $this->database->getReference()->getChild($this->dbnameNotification)->getChild(str_replace(".","",$email))->getChild($index)->set($newNotification);
    }
    
    public function qtyNotifications($email) {
        $allNotifications = $this->database->getReference($this->dbnameNotification)->getChild(str_replace(".","",$email))->getSnapshot()->getValue();
        if($allNotifications == null){
            return 0;
        }
        else {
            return count($allNotifications);
        }
    }

    public function getNotificationsByCustomer(string $email) {
        return $this->database->getReference($this->dbnameNotification)->getChild(str_replace(".","",$email))->getValue();
    }

    public function updateNotification($email, $notificationId){
        return $this->database->getReference($this->dbnameNotification)->getChild(str_replace(".","",$email))->getChild($notificationId)->getChild("read")->set("true");
    }
}

?>