<?php
define('COOKIE_PATH', '/spsoni/spitech-php-libraries/');
function debug($args, $die = 1)
{
    echo "<pre>";
    print_r($args);
    echo "</pre>";
}



//SpiTech API & PDF
require_once('SpiTechApi/dompdf/SpiTechPdf.php');
require_once('SpiTechApi/spitech/SpiTechApi.php');

//SpiTechPDF
$config = [
    'base_path' => 'D:\wamp64\www\spsoni\billing.spitech.in\\',
    'pdf_dir' => ''
];
$pdf = new SpiTechPdf($config);
//$pdf->generatePDF("Menu/demo.html", "demp.pdf", 1);

//SpiTechApi
$params = array(
    "logPath" => "../logs/",
    'cookiePath' => COOKIE_PATH,  // this constant is coming from Conn.php file
    'apiKey' => '61w5W4Rp0ZcB9k9i9A5smx0b11gzP1'
);
$objSpiTechApi = SpiTechApi::getInstance($params);

function testMail()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://api.spitech.in/api/emailServices/sendmail',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('to' => 'spsoni.acc@gmail.com', 'subject' => 'Demo Hello World', 'message' => 'Lorem Ipsum'),
        CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=455827589780da229a2351e54d4f03b6'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}

//testMail();

$res = $objSpiTechApi->sendMail('spitechsoft@gmail.com', "Test from SpiTech PHP Library", "Demo Message" . __DIR__);
debug($res);
