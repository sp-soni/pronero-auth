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


$res = $objSpiTechApi->sendMail('spitechsoft@gmail.com', "Test from SpiTech PHP Library", "Demo Message" . __DIR__);
debug($res);
