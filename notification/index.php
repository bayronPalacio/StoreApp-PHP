<?php
require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../inc/Classes/Notification.php';
require_once __DIR__.'/../inc/Classes/Customer.php';
require_once __DIR__.'/../inc/Utilities/FileAgent.inc.php';
require_once __DIR__.'/../inc/Utilities/FileParse.inc.php';
require_once __DIR__.'/../inc/Utilities/FileComFirebase.php';

$dataToFirebase = new Communication();

if(isset($_POST['newNotification'])){
    $data = (array)explode(",",$_POST["newNotification"]);
    $email = $data[0];
    $title = $data[1];
    $message = $data[2];
    $date = date('m/d/Y H:i:s', strtotime("now"));
    $idx = $dataToFirebase->qtyNotifications($email);
    $newNotification = new Notification();
    $newNotification->setDateSent($date);
    $newNotification->setMessage($message);
    $newNotification->setRead("false");
    $newNotification->setTitle($title);

    $dataToFirebase->insertNotification($email, $idx, $newNotification);
    echo $email.",".$idx.",".$title.",".$message;
}
    
//Get Order by Notification
elseif(isset($_POST['getNotificationsByEmail'])){
    $email = $_POST['getNotificationsByEmail'];
    $notifications = $dataToFirebase->getNotificationsByCustomer($email);
    $dateNow = strtotime(date('m/d/Y H:i:s', strtotime("now")));
    for($index = (count($notifications)-1); $index >=0;$index-- ){
        $dateSent = strtotime($notifications[$index]["dateSent"]);
        $seconds = $dateNow- $dateSent;
        $minutes = $seconds/60;
        $hours = $minutes/60;
        if($seconds > 86400){
            $shortDate = date('M d', strtotime($notifications[$index]["dateSent"]));
            $notifications[$index]["dateSent"] = $shortDate;
        }
        elseif($seconds < 60)
            $notifications[$index]["dateSent"] = "<1m";           
        elseif($minutes < 60)
            $notifications[$index]["dateSent"] = (int)$minutes."m";
        elseif($hours < 24)
            $notifications[$index]["dateSent"] = (int)$hours."h";

        $arrayOfNotifications []= $notifications[$index];       
    }
    echo json_encode($arrayOfNotifications);
}

elseif(isset($_POST['updateNotification'])){
    $data = explode(",", $_POST['updateNotification']);
    $email = $data[0];
    $notificationId = $data[1];
    return $dataToFirebase->updateNotification($email, $notificationId);
}

?>