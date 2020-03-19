<?php


namespace App\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GeoApi {
    public function getCity($name, $zip_code, NotFoundHttpException $error)
    {
      $clearedName = str_replace(' ', '%20', $name);
      $url = 'https://geo.api.gouv.fr/communes?nom='.$clearedName.'&codePostal='.$zip_code.'&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre';
      $ch = curl_init();
        if ($zip_code && $name) {
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0);

          try {
            $response = curl_exec($ch);
            $clearedResponse = json_decode($response)[0];
            curl_close($ch);
          } catch ( \Exception $e) {
             throw $error->('Une erreur est survenue');
          }

        } else {
          dd('errors');
        }
      return $clearedResponse;
    }
}

