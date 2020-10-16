<?php
require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../inc/Classes/Product.php';
require_once __DIR__.'/../inc/Classes/Customer.php';
require_once __DIR__.'/../inc/Utilities/FileAgent.inc.php';
require_once __DIR__.'/../inc/Utilities/FileParse.inc.php';
require_once __DIR__.'/../inc/Utilities/FileComFirebase.php';
require_once __DIR__.'/../inc/Utilities/configGoogleStorage.php';

$dataToFirebase = new Communication();

//Insert new products into firebase through csv file
if(isset($_POST['products'])){
    
    $data = (array)json_decode($_POST["products"]);
    $products = $dataToFirebase->getProducts();
    
    if(($products) == null){
        // echo "Products do not exist in the database   ";
        $dataToFirebase->insertProducts($data);
        echo "true";
    }
    else{
        // echo "products exist in the database";
        foreach($data["products"] as $item){
            $newItem = (array)($item);
            
            $item = createProduct($newItem);
            array_push($products["products"],$item);
        }
        $dataToFirebase->insertProducts($products);
        echo "true";
    }
}

//Set Availability to false => remove
elseif(isset($_POST['productDelete'])){
    $id = $_POST['productDelete'];
    $productById = $dataToFirebase->getProductById($id);

    $index = array_keys($productById);
    $infoProduct = $productById[$index[0]];

    $item = new Product();
    
    $item->setBarcode($infoProduct["barcode"]);
    $item->setBrand_id($infoProduct["brand_id"]);
    $item->setCategories($infoProduct["categories"]);
    $item->setCost_price((double)$infoProduct["cost_price"]);
    $item->setDescription($infoProduct["description"]);
    $item->setId($infoProduct["id"]);
    $item->setInventory_level((int)$infoProduct["inventory_level"]);
    $item->setInventory_warning_level((int)$infoProduct["inventory_warning_level"]);
    $item->setName($infoProduct["name"]);
    $item->setReview_message($infoProduct["review_message"]);
    $item->setPrice((double)$infoProduct["price"]);
    $item->setReviews_count($infoProduct["reviews_count"]);
    $item->setReviews_rating_sum($infoProduct["reviews_rating_sum"]);
    $item->setUrl_image($infoProduct["url_image"]);
    $item->setAvailability((boolean)false);

    $dataToFirebase->updateProduct($item,$index);
}

//Edit product by ID 
elseif(isset($_POST["editProduct"])){

        $newProduct = (array)json_decode($_POST["editProduct"]);
        
        $id=$newProduct["id"];
        
        $productById = $dataToFirebase->getProductById($id);
        $index = array_keys($productById);
        
        //Check if a new image was added 
        $chars = substr(($newProduct["url_image"]),0,5);
        if((substr_compare($chars,"https",0)) == 0 ){
             $item = createProduct($newProduct);
        }
        else{
            $item = imageToGoogleCloud($newProduct);
        }    
        $dataToFirebase->updateProduct($item,$index);
        echo "true";
}


//Edit products by List 
elseif(isset($_POST["editProductByList"])){
    $productsFromList = (array)json_decode($_POST["editProductByList"]);

    foreach($productsFromList["products"] as $product){
        
        $editProduct = (array)$product;
        $id=$editProduct["id"];
        
        $productById = $dataToFirebase->getProductById($id);
        
        $index = array_keys($productById);
        $infoProduct = $productById[$index[0]];

        $item = new Product();
        $item->setBarcode($infoProduct["barcode"]);
        $item->setBrand_id($infoProduct["brand_id"]);
        $item->setCategories($infoProduct["categories"]);
        $item->setCost_price((double)$editProduct["cost_price"]);
        $item->setDescription($editProduct["description"]);
        $item->setId($infoProduct["id"]);
        $item->setInventory_level((int)$editProduct["inventory_level"]);
        $item->setInventory_warning_level((int)$editProduct["inventory_warning_level"]);
        $item->setName($infoProduct["name"]);
        $item->setReview_message($infoProduct["review_message"]);
        $item->setPrice((double)$editProduct["price"]);
        $item->setReviews_count($infoProduct["reviews_count"]);
        $item->setReviews_rating_sum($infoProduct["reviews_rating_sum"]);
        $item->setUrl_image($infoProduct["url_image"]);
        $item->setAvailability($editProduct["availability"]);
        
        $dataToFirebase->updateProduct($item,$index);
    }   
    echo "true";
}

//Add a new product
elseif(isset($_POST["addProduct"])){
    
    $newProduct = (array)json_decode($_POST["addProduct"]);
    if(isset($newProduct["url_image"]) == null){
        echo "false";
    }
    else {
        $item = imageToGoogleCloud($newProduct);
        
        $index = $dataToFirebase->getQtyProducts();
        $dataToFirebase->addAProduct($item,$index);
        echo "true";
    }
}

//Get the products by ID from Cart Request
elseif(isset($_POST["productsCart"])){
    
    $cartID = explode(",",$_POST["productsCart"]);
    foreach($cartID as $id){
        $productById = $dataToFirebase->getProductById($id);
        $index = array_keys($productById);
        $infoProduct = $productById[$index[0]];
        $cart[] = $infoProduct;
    }
    $cartProducts["products"] = $cart;
    echo json_encode($cartProducts);
}

//Search products by Name
elseif(isset($_POST["searchProductsByName"])){
    
    $word = $_POST["searchProductsByName"];
    
    if($word == null){
        echo "no results";
    }
    else{
        $availableProducts = $dataToFirebase->getProductsAvailables();
        $dataToFirebase->searchProductsByName($word,$availableProducts);
    }
}

// Get the product list from firebase to show only the available items on the APP
else{
    $products = $dataToFirebase->getProductsAvailables();
    
    if($products != null){      
            $finalProducts["products"] = array_values($products);
            echo json_encode($finalProducts);
    }
    else echo "empty";
}

function createProduct(array $newItem){
    
    $item = new Product();

    $item->setBarcode($newItem["barcode"]);
    $item->setBrand_id($newItem["brand_id"]);
    $item->setCategories($newItem["categories"]);
    $item->setCost_price((double)$newItem["cost_price"]);
    $item->setDescription($newItem["description"]);
    $item->setId($newItem["id"]);
    $item->setInventory_level((int)$newItem["inventory_level"]);
    $item->setInventory_warning_level((int)$newItem["inventory_warning_level"]);
    $item->setName($newItem["name"]);
    $item->setReview_message($newItem["review_message"]);
    $item->setPrice((double)$newItem["price"]);
    $item->setReviews_count($newItem["reviews_count"]);
    $item->setReviews_rating_sum($newItem["reviews_rating_sum"]);
    $item->setUrl_image($newItem["url_image"]);
    $item->setAvailability($newItem["availability"]);
    
    return $item;
}


function imageToGoogleCloud($newProduct){
    
        $image = base64_decode($newProduct["url_image"]);
        $imageName = $newProduct["barcode"].".jpg";

        // $bucketName = "researchprojectimage";
        $bucketName = "myphppics";
        // NOTE: if 'folder' or 'tree' is not exist then it will be automatically created !
        $cloudPath = 'uploads/' . $imageName;
        
        //********************ADD IMAGE TO GOOGLE CLOUD****************************
        // $isSucceed = uploadFile($bucketName, $fileContent, $cloudPath);
        $isSucceed = uploadFile($bucketName, $image, $cloudPath);
        // echo "before load image to google";
        if ($isSucceed == true) {
            
            //URL address where image is stored    
            // $urlImage = "https://storage.googleapis.com/researchprojectimage/uploads/".$imageName;
            $urlImage = "https://storage.googleapis.com/myphppics/uploads/".$imageName;
            
            $item = new Product();
            $item->setBarcode($newProduct["barcode"]);
            $item->setBrand_id($newProduct["brand_id"]);
            $item->setCategories($newProduct["categories"]);
            $item->setCost_price((double)$newProduct["cost_price"]);
            $item->setDescription($newProduct["description"]);
            $item->setId($newProduct["id"]);
            $item->setInventory_level((int)$newProduct["inventory_level"]);
            $item->setInventory_warning_level((int)$newProduct["inventory_warning_level"]);
            $item->setName($newProduct["name"]);
            $item->setReview_message($newProduct["review_message"]);
            $item->setPrice((double)$newProduct["price"]);
            $item->setReviews_count($newProduct["reviews_count"]);
            $item->setReviews_rating_sum($newProduct["reviews_rating_sum"]);
            $item->setUrl_image($urlImage);
            $item->setAvailability(true);
            
            return $item;
            
            } else {
                $response['code'] = "201";
                echo "Failed to upload";
                $response['msg'] = 'FAILED: to upload ' . $cloudPath . PHP_EOL;
            }
}
    
?>