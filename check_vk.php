<?php


$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL            => 'vk.com',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING       => '',
	CURLOPT_MAXREDIRS      => 10,
	CURLOPT_TIMEOUT        => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST  => 'GET',
	CURLOPT_HTTPHEADER     => [
		'Authorization: OAuth XELv1RTe1wRPIQnEYhuP',
		'Cookie: remixlang=0'
	],
]);

$response = curl_exec($curl);

curl_close($curl);
echo $response;
