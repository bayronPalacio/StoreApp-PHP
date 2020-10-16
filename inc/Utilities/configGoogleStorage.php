<?php
 
// load GCS library
require __DIR__.'/../../vendor/autoload.php';
 
use Google\Cloud\Storage\StorageClient;
 
// Please use your own private key (JSON file content) which was downloaded in step 3 and copy it here
// your private key JSON structure should be similar like dummy value below.
// WARNING: this is only for QUICK TESTING to verify whether private key is valid (working) or not.  
// NOTE: to create private key JSON file: https://console.cloud.google.com/apis/credentials  

$privateKeyFileContent = '{
  "type": "service_account",
  "project_id": "testapril11",
  "private_key_id": "eca3a154959a0950e9cf3e0db8d08522c7941027",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDpZ8lrOmHdlLmE\novgkFGkNZheuw4DhR584y5AA9jwjEb2emJQSNvxmyvRnXcKchvfmn15OSbZFlKEP\nN1ltKPf8KtdJSNIM8uf/mmrjUdLF3WG8nlkOCXetQh3QEQCMwZZX+zqNhNgarjmv\nmNStmDqMc4BwtvRV0NeGD61Dhzv8tu5ZN+NIUF0goC9Zrg+r/HKe6KjCIu8epK3h\nzVFc42JIAXkZ+q97ROHEqsM1SfvUJ3i0Gns5YylMRDoZ5cY0Pyrzq0sYTCIuqpST\nVgIaCfLiPgWHhNFDiaNf0vYM0goHNwdlFvItyK0ECVOwZmoL96W8CiqAY+k/g2vs\niott88WRAgMBAAECggEADLUJ0jyrd1TRhNcai1xRG9mjEHC0Y49yq7TpciP1wjMF\nD81ewl8b/ryt8bK5FayDcpral4iL093zptBsnfTNC32JmwPL68iNuOy/RQD/aKZU\n2+ADGTCKw3QGC33hechQVxnmZi4FVe6GVXkMmybeiScApyJOyx1Dnpjb5gFG0stI\nMNJqAP1vHRfNHfDVt1IdkxuTwT83BvHJVMynLmpbRmhVFQW0LE8LrWNKc+WM/szQ\nZ1/dUlIlJgyJFOQhdwO96fcs0KIjnUy6ofQ3WIll3TjMV3sboQWPvxVHc7V9EXc+\n85BvhP4iVbXK5qjaFaQex3aTRJhxqJfHx2Ju2rppwQKBgQD1gB+uI27isyU+ktDc\no6IEdC5gcMEvWHIi3chwA6svDoMtmCE+gw+ac1e7XyKm5WLlQ9t2BMysEo4yYPdL\nDJzfY1m0jXKZ6kNzXPDihKWtGyTWjDWPmPs6fiAxqT5z9Kww7wVvciJtlVUBpjPT\niJMUYHzXwFxtyuF2dZbL7ifkNwKBgQDzYz1Qc47Q60Ma1xUwJslu8KwDqrYbK7xH\n9dBciVMObzCOR60ogJZIuLd9kCBhP5IfMRaBB9rVCDocSKEcOHrpAHSwYr7S7SGu\ngj6SnV/Lnd3zRD72LVdAXnfATNYdCQyHn3CSak+flkz1Ts6o/TUu8rOVrfgcGUPM\nMn0O6eTQdwKBgFLEemnMOmMlwKXC+7WXWVUlEkmjWg4rRHeqzBuvsPPxcZpaksKv\njwAkaOyb22PnLBtdcEzuu048B0LG3DiqLO1HYoPzuDJZ/5mSOQg1bmHZJ+dJZ/lc\nuz5yLUpw48RWNgNdYji7gw3s7bbboNdtUSDqksxuZvsBDlhQtqFUTpCrAoGAUfGa\npiiR7/MVn8bEgna8oRZ0w7NJJrK97Hhtk4pEpXp4HZ8yRI2ui4Nte9/3luZhWxEa\n44xyeAESF424eJjngOU/ZFeKqdde3LNLP++uXg6juJDEpfu0AZ8MsHjuKc5vIoZi\nz1jtv+xJstm6qJi/vDPIlN+CxmsXMXvaMRg8ipcCgYB3Y9iUGbACv2n0Q2q78kVZ\nGUftnx3C2Dql1CKExxSWlQ/UmTJuJwhdMvjtub+TfplR4Gf/d95nIyAUWaNYBQJD\nUhcguI0Av4UgNVDtKt8nEpVf+vBGg9xbfH8//Gnwef2TF6ySGffluQLaRWPDmUc7\nygwZLaM7d93RxwZccHMkkA==\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-upload@testapril11.iam.gserviceaccount.com",
  "client_id": "111195165999105393699",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-upload%40testapril11.iam.gserviceaccount.com"
}';



// $privateKeyFileContent ='{
//     "type": "service_account",
//     "project_id": "rapidcartimages",
//     "private_key_id": "4db37ff7bd7c0577080642b5c9990ce1ebec2ff1",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDnH7/9p3AWlhQV\nAA6rRZOJS1KqMalx/ENhB94r/CVx36jXCFFEZO8Lx5GzEjBePWCT58cbqY89s0vA\n3EiTWlOirLRJUng1csc/Z4RdBx5pP1lOQVluyXo9M/xRPDabjymo3FbBYPaaCHUP\n2wJwjDtyq7idouSiTUUS5Yju+Rf+RufZmYV/LfG4w74TcI5Zpqk5wsAc4MGTlDT6\nfaA06a/8o3uIaNbss7Tgx44cxVLs1asLzxEGjL62eQgKVqBkU8UK/5q+dhns4N/E\nbVsS9zTF7EznwD9ZLTCxDShjXPcsSgDMzL/GXXfDphGdh/cczX67CAzqMejanuos\nrvJqc/U/AgMBAAECggEAILxgAqrtFt3h8UmFSl/78iXPiximoeTznDIzlzJLEXJ2\n5SIh9JiBPT9ilST94dM6syhQjMv65GfZUnNA+lIAZ5mjnYEHUraznvjoCA8ikRCu\ncwWRh74UyMdstQrcxzLDWU4AIbq8ovuQrRK9qThLkUXeS8GPNafRIUiOmR6B9ftL\nGPYepJZb/bQbe+9GBPCQc52HKPpB9YnoI0pHiMH9rY5ausTSQ9aJA4OTmRR9CDhR\n4waV6LXIa+xBfA0AuGCVJVbvAnzgV1F7ugyrLttdqWN2OtCfk0snMeZU7mDR9BLy\nLS18VkSZ76Lno341QDkD8frBsZVrUIxojkeDBE7aBQKBgQD7aMIk4hDlMpF413Ta\nbT8sCUvzXg0kJYEoduuj/8yZGqmdHRzbJSAy+gtrLz9MAKxRKKDUD2tjkd4Eiymc\ne2mNr/Y1vNz8sslUkA7ZtGWePhYK44VZksKgO+Be97ZQDF8BHUpOypmk1/0obVWs\n8s1c7+weHKH7JW75ZnL5cGsXYwKBgQDrWCqGR9b/JZJH4XZqdiTnLvKiexTlSsb7\nU7IeisJCrTB7ECzVFQllobduYQjtNp1RIqrv+JYwnGARCxVU19JOBwsYRTz6asmC\nJN9uAiafircsX5M1NEioEE4JpUwoYCNOxzH617+udHDuo0z6rxaeLH98yG3wxRgU\n/nAdqVQ3dQKBgBG8Bw3xAH4afhaHJFAQKsehdEEbnlOxbZX8JY0xCy61qcdSeWmg\nCdBuwQVzlMR4u68R7Z5n5AxnCsFJS8slDYIYKocJ5pDQz9DuRI3o4s4x1P45pHt4\nFdCVyLMKSEsHRGBgv5AlTIdLUSlb5RJJImFu7zoEjQyZTGW9a1jbLEK/AoGBAIyf\nygkitB0RXi8FUbSbk8FwFBtyYDICVJOoly4Yy6r7wZRHPgK050CLOQgDg0n68FWi\n2XE6oMO7LMwCS2S+8TMekkqWVLFtTasuTTN2pY1/XaRuF5AbBa8mzvqEgtU9XNud\nuwegEzEy8qgle8HvDRzGigcN3GOZ4aHpxyRyIZhhAoGAVXudJdQZSwvGWybvMzC9\npHcoSZuD6FWQZjvyEPm/dKMjIWnfqpPexzwWzdCI51yqqnnUHJsJ/sodWMOymTGp\nK8n0QPtxtoVbW0R7P4V+5N56KOTyOyKmafV0FbWcvruWGT+zgXLdjKlfxi0h1COS\nRAfi8p68f4OUcCF0cdueGCA=\n-----END PRIVATE KEY-----\n",
//     "client_email": "serviceaccountimageresearch@rapidcartimages.iam.gserviceaccount.com",
//     "client_id": "112586040046585705453",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/serviceaccountimageresearch%40rapidcartimages.iam.gserviceaccount.com"
//   }';
 
/*
 * NOTE: if the server is a shared hosting by third party company then private key should not be stored as a file,
 * may be better to encrypt the private key value then store the 'encrypted private key' value as string in database,
 * so every time before use the private key we can get a user-input (from UI) to get password to decrypt it.
 */
 
function uploadFile($bucketName, $fileContent, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }
 
    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);
 
    // upload/replace file 
    $storageObject = $bucket->upload(
            $fileContent,
            ['name' => $cloudPath]
            // if $cloudPath is existed then will be overwrite without confirmation
            // NOTE: 
            // a. do not put prefix '/', '/' is a separate folder name  !!
            // b. private key MUST have 'storage.objects.delete' permission if want to replace file !
    );
 
    // is it succeed ?
    return $storageObject != null;
}
 
function getFileInfo($bucketName, $cloudPath) {
    $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
    // connect to Google Cloud Storage using private key as authentication
    try {
        $storage = new StorageClient([
            'keyFile' => json_decode($privateKeyFileContent, true)
        ]);
    } catch (Exception $e) {
        // maybe invalid private key ?
        print $e;
        return false;
    }
 
    // set which bucket to work in
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->object($cloudPath);
    return $object->info();
}
//this (listFiles) method not used in this example but you may use according to your need 
function listFiles($bucket, $directory = null) {
 
    if ($directory == null) {
        // list all files
        $objects = $bucket->objects();
    } else {
        // list all files within a directory (sub-directory)
        $options = array('prefix' => $directory);
        $objects = $bucket->objects($options);
    }
 
    foreach ($objects as $object) {
        print $object->name() . PHP_EOL;
        // NOTE: if $object->name() ends with '/' then it is a 'folder'
    }
}