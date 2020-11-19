<?php
header('Access-Control-Allow-Origin: https://www.jessicargordon.com', false);
$admin_email = 'jessicargordon7@gmail.com';
define('AMOUNT', 13.99);

$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$address = $_POST['address'] ?? null;
$phone = $_POST['phone'] ?? null;
$stripe_token = $_POST['stripe_token'] ?? null;

if (empty($name)) {
    json_response(['status' => false, 'msg' => 'Please Provide Name']);
} elseif (empty($email)) {
    json_response(['status' => false, 'msg' => 'Please Provide Email']);
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['status' => false, 'msg' => 'Please Provide Valid Email']);
} elseif (empty($address)) {
    json_response(['status' => false, 'msg' => 'Please Provide Address']);
} elseif (empty($phone)) {
    json_response(['status' => false, 'msg' => 'Please Provide Phone Number']);
} elseif (empty($stripe_token)) {
    json_response(['status' => false, 'msg' => 'Please Enter Card Details Correctly!']);
}

$name = htmlentities(trim($name), ENT_NOQUOTES);
$email = htmlentities(trim($email), ENT_NOQUOTES);
$phone = htmlentities(trim($phone), ENT_NOQUOTES);
$address = htmlentities(trim($address), ENT_NOQUOTES);
$resp = stripe_pay($stripe_token);

$user_email_body = '<p>Your book is on the way. We will get back to you shortly with the provided details.</p>';
$user_email_subject = 'Thanks for purchasing';

$admin_email_subject = 'New Book Purchased';
$admin_email_body = '
    <table cellpadding=0 cellspacing=0 style="width: 100%;">
        <tr>
            <th style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">Name</th>
            <td style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">' . $name . '</td>
        </tr>
        <tr>
            <th style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">Email</th>
            <td style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">' . $email . '</td>
        </tr>
        <tr>
            <th style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">Phone No</th>
            <td style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">' . $phone . '</td>
        </tr>
        <tr>
            <th style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">Address</th>
            <td style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">' . $address . '</td>
        </tr>
        <tr>
            <th style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">Stripe Charge ID</th>
            <td style="width:50%;border:1px solid #333;padding: 10px; text-align:left;">' . $resp->id . '</td>
        </tr>
    </table>
';

$uemail = @sendEmail($email, $user_email_subject, $user_email_body);
$aemail = @sendEmail($admin_email, $admin_email_subject, $admin_email_body);

if ($uemail && $aemail) {
    json_response(['status' => true, 'msg' => 'Success! Your order has been booked and email will be sent to you with details also we will contact you soon via email address and will give you the access to book!']);
} else {
    json_response(['status' => true, 'msg' => 'Success! Your order has been booked and email will be sent to you with details.']);
}

function sendEmail($to_email, $subject, $msg)
{
    $from_email = 'jessicargordon7@gmail.com';

    $headers = "From: " . strip_tags($from_email) . "\r\n";
    $headers .= "Reply-To: " . strip_tags($from_email) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to_email, $subject, $msg, $headers);
}

function json_response($arr)
{
    echo json_encode($arr);
    exit;
}

function stripe_pay($token)
{
    //$apiKey = 'sk_test_YwfixAuvPJpgKZVRMewyXxBv';
    $apiKey = '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';
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "https://api.stripe.com/v1/charges",
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $apiKey
        ],
        CURLOPT_POSTFIELDS => http_build_query([
            "amount" => AMOUNT * 100,
            "currency" => 'usd',
            "source" => $token,
            "description" => "Book Sale For Jessica Gordon"
        ])
    ]);
    $resp = curl_exec($curl);
    curl_close($curl);
    $resp = json_decode($resp) ?? null;

    if ($resp === null) {
        json_response(['status' => false, 'msg' => 'Something went wrong try again later']);
    }

    if (isset($resp->error)) {
        json_response(['status' => false, 'msg' => $resp->error->message]);
    }
    return $resp;
}
