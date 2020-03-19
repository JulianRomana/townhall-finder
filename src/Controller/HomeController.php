<?php

namespace App\Controller;

use App\Form\SearchFacilitiesType;
use App\Service\EtablissementPublicApi;
use App\Service\GeoApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\Error;

class HomeController extends AbstractController
{
  /**
   * @Route("/", name="home")
   * @param Request $request
   * @param GeoApi $geoApi
   * @param EtablissementPublicApi $facilitiesApi
   * @return Response
   */
    public function index(Request $request, GeoApi $geoApi, EtablissementPublicApi $facilitiesApi)
    {
        $form = $this->createForm(SearchFacilitiesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
            $zip_code = $data['zip_code'];

            try {
              $city = $geoApi->getCity($name, $zip_code);
              $cityCode = get_object_vars($city)['code'];
              $townHallInfos = $facilitiesApi->getCityFacilities($cityCode);

            } catch (\Exception $error) {

              return $this->render('base.html.twig', [
                'search_form' => $form->createView(),
                'error' => $error
              ]);
            }
            if (count($townHallInfos)) {
              $clearedTownHallInfos = [
                'name' => $townHallInfos['nom'],
                'address' => $townHallInfos['adresses'][0]->lignes[0] . ' ' . $townHallInfos['adresses'][0]->codePostal,
                'email' => $townHallInfos['email'],
                'website' => $townHallInfos['url'],
                'phone' => $townHallInfos['telephone'],
              ];
              return $this->render('base.html.twig', [
                'search_form' => $form->createView(),
                'townHallInfos' => $clearedTownHallInfos,
              ]);
            }

            $form->clearErrors(true);
        }

        return $this->render('base.html.twig', [
            'search_form' => $form->createView()
        ]);
    }
}
