<?php

  namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class EtablissementPublicApi {
  public function getCityFacilities($cityCode):array {
    $url = 'https://etablissements-publics.api.gouv.fr/v3/communes/'.$cityCode.'/mairie';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    try {
      $response = curl_exec ($ch);
    }
    catch (\Exception $error) {
      throw new \Exception('Aucune info sur cette mairie !');
    }

    curl_close($ch);
    $clearedResponse = json_decode($response);

    return get_object_vars(get_object_vars($clearedResponse)['features'][0]->properties);
  }
}