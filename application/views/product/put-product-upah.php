<?php                                                   
$data_string = json_encode($result);                                                                                   
print_r($data_string);

//$ch = curl_init('https://tukangemas.my/api/public/product/add');                                                                      
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
//    'Content-Type: application/json',                                                                                
//    'Content-Length: ' . strlen($data_string))                                                                       
//);   
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//$result = curl_exec($ch);
//curl_close($ch);