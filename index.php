<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/inc/Utilities/FileComFirebase.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

$dataToFirebase = new Communication();

if(isset($_POST['searchByImage'])){

//*********************** GET IMAGE *******************************/
	$imageFromAPP = $_POST['searchByImage'];
	$image = base64_decode($imageFromAPP);
//*******************************************************************/

	putenv("GOOGLE_APPLICATION_CREDENTIALS=" . __DIR__ . '/visionappPicture-cde1e5301e6c.json');
	$imageAnnotator = new ImageAnnotatorClient();

	# performs label detection on the image file
	$response = $imageAnnotator->labelDetection($image);
	$labels = $response->getLabelAnnotations();

	$availableProducts = $dataToFirebase->getProductsAvailables();

	$searchArray = null;
	if ($labels) {
		foreach ($labels as $label) {
			$word = $label->getDescription();
			$explodeWord = explode(" ",$word);
			if(count($explodeWord) == 1){
				foreach($availableProducts as $key => $products){
					if(stristr($products['name'],$word) != null){
						unset($availableProducts[$key]);
						$searchArray [] = $products;
					}                
				}
			}
			else{
				foreach($explodeWord as $eachWord){
					foreach($availableProducts as $key => $products){
						if(stristr($products['name'],$eachWord) != null){
							unset($availableProducts[$key]);
							$searchArray [] = $products;
						}                
					}
				}
			}
		}
	} else {
		echo('No label found' . PHP_EOL);
	}

	if($searchArray != null){
		$finalProducts["products"] = $searchArray;
		echo json_encode($finalProducts);
	}
	else{
		echo "no results";
	}

	// if ($labels) {
	// 		echo("Labels:" . PHP_EOL);
	// 		// var_dump($labels);
    // 		foreach ($labels as $label) {
    //     		echo($label->getDescription() . PHP_EOL);
    // 		}
	// } else {
    // 		echo('No label found' . PHP_EOL);
	// }

}


?>