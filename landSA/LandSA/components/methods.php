<?php
function getLandInfo($Land_REUN){
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true)
$url="http://localhost:8080/api/query/$Land_REUN";
$ch = curl_init();
// set URL and other appropriate options  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_HEADER, 0);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

// grab URL and pass it to the browser  
$output = curl_exec($ch);  
if (curl_errno($ch)) {
  echo 'Error:' . curl_error($ch);
}

// close curl resource, and free up system resources  
curl_close($ch);

//Format the Curl output
$formatOutput= str_replace("\\\"","",$output);
$formatOutput=str_replace('{"response":"{',"",$formatOutput);
$formatOutput=str_replace('}"}',"",$formatOutput);
$LandArray=explode(',',$formatOutput);

//Contnue formating and storing raw Output in Array
$infoStack=array();
foreach ($LandArray as $i){
  $a=explode(':',$i);
  array_push($infoStack,$a[1]);
}
// foreach ($infoStack as $H){
//   print($H);
//   echo "<br>";
// }
return $infoStack; //the return type is array
}


function registerLand($Land_Data){
// Prepare new cURL resource
$url="http://localhost:8080/api/createland";
$ch = curl_init();
// set URL and other appropriate options  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $Land_Data);


// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
// Submit the POST request
$output = curl_exec($ch);
if (curl_errno($ch)) {
  echo 'Error:' . curl_error($ch);
}
if($output=="Transaction has been submitted"){
  $output= 1;
}else {$output= 0;}
// $output= str_replace("Transaction has been submitted","تم ارسال العمليه بنجاح.",$output);

// Close cURL session handle
curl_close($ch);

return $output; //the return type is string
}

function UpdateLandOwner($Land_REUN,$Owner_Data){
    $url="http://localhost:8080/api/UpdateLandOwner/$Land_REUN";
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $Owner_Data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    
    if($output=="Transaction has been submitted"){
      $output= 1;
    }else {$output= 0;}
    // $output= str_replace("Transaction has been submitted","تم ارسال العمليه بنجاح.",$output);

    // Close cURL session handle
    curl_close($ch);
    
    
    return $output; //Return boolean value, True if Transaction is submitted and false otherwise
}

?>