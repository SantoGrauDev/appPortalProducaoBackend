<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://santograu.vtexcommercestable.com.br/api/oms/pvt/orders?f_creationDate=creationDate%3A%5B2016-01-01T02%3A00%3A00.000Z%20TO%202021-01-01T01%3A59%3A59.999Z%5D&f_hasInputInvoice=false', [
  'headers' => [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'X-VTEX-API-AppKey' => 'vtexappkey-santograu-LFUJLD',
    'X-VTEX-API-AppToken' => 'GFPBGKKCOJCIITAPXLYMKTNJUDXKSVNPIWFUICXOSNDGNREPQPVZUTVVASRIHDFCDTXDLWIWTSQKIMZKEZNICGMTUAUTTBLQSIUUMIMKEYFRLJZPKYGTXFTITLERNGEL',
  ],
]);

echo $response->getBody();