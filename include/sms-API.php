<?php
function SentSMS($mobile, $senderId = 'Pharma C.', $message = "Waiting..!")
{
    $MSISDN = $mobile;
    $SRC = $senderId;
    $MESSAGE = (urldecode($message));
    $AUTH = "172|ArgYLDuxabueuIWBcItBUkrwp2ZrNov5I3ujWKLY";  //Replace your Access Token

    $msgdata = array("recipient" => $MSISDN, "sender_id" => $SRC, "message" => $MESSAGE);

    $curl = curl_init();

    //IF you are running in locally and if you don't have https/SSL. then uncomment bellow two lines
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://sms.send.lk/api/v3/sms/send",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($msgdata),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Bearer $AUTH",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $error = array('status' => 'error', 'message' => $err);
    } else {
        $responseArray = json_decode($response, true);
        if ($responseArray['status'] === "success") {
            $error = array('status' => 'success', 'message' => $responseArray['message']);
        } else {
            $error = array('status' => 'success', 'message' => $responseArray['message']);
        }
    }

    return json_encode($error);
}
