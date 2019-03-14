<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use AppBundle\Form\TripType;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        /** @var User $user */
        $user = $this -> getUser();

        $newTrip = new Trip();
        $form = $this->createForm(TripType::class, $newTrip)
            ->add('Create', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $newTrip->setUnitPricePlusFees($newTrip->getUnitPrice()); //TODO : Une fois qu'on aura la distance, calculer ça.
            $newTrip->setNumberPlacesRemaining($newTrip->getInitialNumberPlaces());
            $newTrip->setIdDriver($user->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTrip);
            $em->flush();
        }

        return $this->render('default/newTrip.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/searchTrip", name="searchTrip")
     */
    public function searchTripAction(Request $request){
        $em = $this->getDoctrine()->getRepository(Trip::class);
        $trips = $em->findAll();
        return $this->render('default/searchTrip.html.twig', [
            'trips' => $trips
        ]);
    }

    /**
     * @Route("/editPrivateInfo", name="editPrivateInfo")
     */
    public function editPrivateInfoAction(Request $request){
        $user = $this->getUser();

        $form = $this->createForm(UserType::class)
            ->add('Send', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $data = $form->getData();
            /** @var $user User */
            $user->setPreference([
                User::SMOKE_AUTHORIZED => $data["smoke"],
                User::ACCESS_PHONENUMBER => $data["AccessPhoneNumber"],
                User::ACCESS_MAIL => $data["AccessMail"],
                User::CONDITIONING_AIR => $data["ConditioningAir"],
                User::ANIMALS => $data["Animals"],
                User::BIKE_RACK => $data["bikeRack"],
                User::SKI_RACK => $data["skiRack"]
            ]);
            $user->setPhoneNumber($data["phoneNumber"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('default/editPrivateInfo.html.twig', [
            'form' => $form->createView()
        ]);
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
