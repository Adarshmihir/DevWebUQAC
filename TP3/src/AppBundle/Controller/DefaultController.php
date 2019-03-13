<?php

namespace AppBundle\Controller;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    //Page d'accueil
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    // Contrôleur qui permet à l'utilisateur de publier un trajet.
    /**
     * @Route("/newTrip", name="newTrip")
     */
    public function newTripAction(Request $request){
        return $this->render('default/newTrip.html.twig', []);
    }

    /**
     * @Route("/searchTrip", name="searchTrip")
     */
    public function searchTripAction(Request $request){
        return $this->render('default/searchTrip.html.twig', []);
    }

  /*  /**
     * @Route("/registerCustom", name="register")
     */
 /* public function registerAction(Request $request){
        dump($request->request->get("fos_user_registration_form")["phoneNumber"]);
        $phoneNumber = $this->container->get('libphonenumber.phone_number_util')
            ->parse($request->request->get("fos_user_registration_form")["phoneNumber"], "FR");
        dump($phoneNumber);
        $data = $request->request->get("fos_user_registration_form");
        $data['phoneNumber']=$phoneNumber;
        $request->request->set("fos_user_registration_form", $data);
        dump($request);
        return $this->forward($this->routeToControllerName('register'));
        //["phoneNumber"]);
    }
    private function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }*/

}
