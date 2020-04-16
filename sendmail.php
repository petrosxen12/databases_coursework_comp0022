<?php

function dataToSend($email)
{
    preg_match('/([^@]+)/', $email, $output_array);
    $username = $output_array[0];

    $data = sprintf('{
        "from":{
           "email":"ebaytool@group2.net"
        },
        "personalizations":[
           {
              "to":[
                 {
                    "email":"%s"
                 }
              ],
              "dynamic_template_data":{
                 "username":"%s",
               }
           }
        ],
        "template_id":"d-7b374370d2944bd9922b903f02abbc18"
     }', $email, $username);

    return $data;
}

function sendMail($email)
{

    $url = 'https://api.sendgrid.com/v3/mail/send';
    $request = $url;

    // Generate curl request
    $session = curl_init($request);

    // Tell curl to use HTTP POST
    curl_setopt($session, CURLOPT_POST, true);

    curl_setopt($session, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer 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',
        'Content-Type: application/json'
    ));

    // Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, dataToSend($email));

    // Tell curl not to return headers, but do return the response
    // curl_setopt($session, CURLOPT_HEADER, false);
    // curl_setopt($session, CURLOPT_RETURNTRANSFER, true);


    // obtain response
    $response = curl_exec($session);
    curl_close($session);

    // print everything out
    // print_r($response);
    return $response;
}

sendMail("petros.x12@gmail.com");
/*
curl --request POST \
  --url https://api.sendgrid.com/v3/mail/send \
  --header 'Authorization: Bearer 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' \                   
  --header 'Content-Type: application/json' \
  --data '{"personalizations": [{"to": [{"email": "petros.x12@gmail.com"}]}],"from": 
  {"email": "sendeexampexample@example.com"},"subject": "Hello, World!","content": 
  [{"type": "text/plain", "value": "Heya!"}]}'
*/
