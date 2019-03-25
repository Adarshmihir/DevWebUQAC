<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Form\UserType;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use mysql_xdevapi\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('Proposer', SubmitType::class, ['attr' => [ 'class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $newTrip->setUnitPricePlusFees($newTrip->getUnitPrice()); //TODO : Une fois qu'on aura la distance, calculer ça.
            $newTrip->setNumberPlacesRemaining($newTrip->getInitialNumberPlaces());
            $newTrip->setIdDriver($user->getId());


            // Récupération des coordonnées GPS des adresse données grâce à l'API de Google Maps.
            $urlStart = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $urlEnd = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $optionsStart = array("address"=>$newTrip->getStartingPlace(),"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $optionsEnd = array("address"=>$newTrip->getEndingPlace(),"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $urlStart .= http_build_query($optionsStart,'','&');
            $urlEnd .= http_build_query($optionsEnd,'','&');

            $coordStart = json_decode((file_get_contents(htmlspecialchars_decode($urlStart))))->results[0]->geometry->location;
            $newTrip->setLatStart($coordStart->lat);
            $newTrip->setLngStart($coordStart->lng);

            $coordEnd = json_decode((file_get_contents(htmlspecialchars_decode($urlEnd))))->results[0]->geometry->location;
            $newTrip->setLatEnding($coordEnd->lat);
            $newTrip->setLngEnding($coordEnd->lng);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTrip);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Le voyage a bien été proposé.');
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
        $trips = $em->findAllOrderedByDateSupToCurrentDate();

        $form = $this->createFormBuilder()
            ->add('startPlace', TextType::class, ['label' => 'Adresse de départ souhaitée', 'attr' => ['placeholder' => 'Entrer la ville de départ']])
            ->add('endPlace', TextType::class, ['label' => 'Adresse d\'arrivée souhaitée', 'attr' => ['placeholder' => 'Entrer la ville d\'arrivée']])
            ->add('dateTime', DateType::class, ['label' => 'Date de départ', 'widget' => 'single_text'])
            ->add('Rechercher', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-block' ]])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Récupération des coordonnées GPS des adresse données grâce à l'API de Google Maps.
            $urlStart = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $urlEnd = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $optionsStart = array("address"=>$data["startPlace"],"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $optionsEnd = array("address"=>$data["endPlace"],"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $urlStart .= http_build_query($optionsStart,'','&');
            $urlEnd .= http_build_query($optionsEnd,'','&');

            $coordStart = json_decode((file_get_contents(htmlspecialchars_decode($urlStart))))->results[0]->geometry->location;

            $coordEnd = json_decode((file_get_contents(htmlspecialchars_decode($urlEnd))))->results[0]->geometry->location;

            //TODO : Faire quelque chose avec ça derrière pour retourner des résultats pertinents.

            $searchTrip = [];

            /** @var Trip $trip */
            foreach ($trips as $trip){
                if (self::distance($coordStart->lat, $coordStart->lng, $trip->getLatStart(), $trip->getLngStart(), 'k') + self::distance($coordEnd->lat, $coordEnd->lng, $trip->getLatEnding(), $trip->getLngEnding(), 'k') < 100) {
                    $searchTrip[] = $trip;
                }
            }

            return $this->render('default/searchTrip.html.twig', [
                'trips' => $searchTrip,
                'form' => $form->createView(),
                'request' => 'POST'
            ]);
        }


        return $this->render('default/searchTrip.html.twig', [
            'trips' => $trips,
            'form' => $form->createView(),
            'request' => 'GET'
        ]);
    }
    // Calcule la distance en entre deux points et renvoie sa valeur en kilomètre si $unit est égal à 'k'
    public static function distance($lat1, $lng1, $lat2, $lng2, $unit = 'k') {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        //
        $meter = ($earth_radius * $d);
        if ($unit == 'k') {
            return $meter / 1000;
        }
        return $meter;
    }

    /**
     * @Route("/editPrivateInfo", name="editPrivateInfo")
     */
    public function editPrivateInfoAction(Request $request){
        $user = $this->getUser();

        $form = $this->createForm(UserType::class)
            ->add('Modifier', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $data = $form->getData();
            /** @var $user User */
            $user->setPreference([
                User::SMOKE_AUTHORIZED => $data["AutoriseLaCigarette"],
                User::ACCESS_PHONENUMBER => $data["AccesAMonNumeroDeTelephone"],
                User::ACCESS_MAIL => $data["AccesAMonAdresseMail"],
                User::CONDITIONING_AIR => $data["AirConditionne"],
                User::ANIMALS => $data["AnimauxAutorises"],
                User::BIKE_RACK => $data["SupportAVelo"],
                User::SKI_RACK => $data["SupportASki"]
            ]);
            $user->setPhoneNumber($data["NumeroDeTelephone"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('default/editPrivateInfo.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/trip/{id}", name="trip")
     */
    public function tripAction(Request $request, $id){
        $repository = $this->getDoctrine()->getRepository(Trip::class);
        /** @var Trip $trip */
        $trip = $repository->find($id);
        $driver = $this->getDoctrine()->getRepository(User::class)->find($trip->getIdDriver());

        $form = $this->createFormBuilder()
            ->add('numberPlaces', IntegerType::class, ['label' => 'Nombre de place(s) (Entre 0 et ' . $trip->getInitialNumberPlaces() . ')', 'attr' => ['min' => 0, 'max' => $trip->getInitialNumberPlaces()]])
            ->add('Send', SubmitType::class, ['label' => 'Passer à la caisse'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('saveTrip', [
                'idTrip' => $id,
                'numberPlaces' => $data['numberPlaces']
            ]);
        }

        return $this->render('default/trip.html.twig', [
            'trip' => $trip,
            'driver' => $driver,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/saveTrip/{idTrip}/{numberPlaces}", name="saveTrip")
     */
    public function saveTripAction(Request $request, $idTrip, $numberPlaces){
        /** @var Trip $trip */
        $trip = $this->getDoctrine()->getRepository(Trip::class)->find($idTrip);
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('Send', SubmitType::class, ['label' => 'Payer (on suppose qu\'on a un vrai moyen de paiement.)'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->addTripSave(["idTrip" => $idTrip, "numberPlaces" => $numberPlaces]);
            $trip->addPassengers(["passenger" => $user->getId(), "numberPlaces" => $numberPlaces]);
            $trip->setNumberPlacesRemaining($trip->getInitialNumberPlaces()-$numberPlaces);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->persist($trip);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Vos places pour le covoiturage ont bien été réservée.');
            return $this->redirectToRoute('searchTrip');
        }


        return $this->render('default/saveTrip.html.twig', [
            'trip' => $trip,
            'numberPlaces' => $numberPlaces,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/myTrip", name="myTrip")
     */
    public function myTripAction(Request $request){
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getManager()->getRepository(Trip::class);
        $trips = $repo->findBy(['idDriver' => $user->getId()]);

        return $this->render('default/myTrip.html.twig', [
            'TripSave' => $user->getTripSave(),
            'trips' => $trips
        ]);
    }

    /**
     * @Route("/cancelTrip/{id}/{numberPlaces}", name="cancelTrip")
     */
    public function cancelTripAction(Request $request, $id, $numberPlaces){

        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Trip::class);
        /** @var Trip $trip */
        $trip = $repo->find($id);
            // Dans le cas d'un passager qui annule une réservation, le nombre de places ne sera jamais à 0.
        if ($numberPlaces=="0") {
            if ($trip->getNumberPlacesRemaining() != $trip->getInitialNumberPlaces()){
                foreach ($trip->getPassengers() as $passengerTrip){
                    $userPassenger = $this->getDoctrine()->getRepository(User::class)->find($passengerTrip['passenger']);
                    $tripSave = $userPassenger->getTripSave();
                    unset($tripSave[array_search([$id, $passengerTrip['numberPlaces']], $tripSave)]);
                    $userPassenger->setTripSave($tripSave);
                    $em->persist($userPassenger);
                }
            }
            $em->remove($trip);
        } else {
            dump('PAS 0 places donc PASSENGER');
            // Modification sur l'objet Trip :
            $passengersTrip = $trip->getPassengers();
            $passengerToCancel = [$user->getId(), $numberPlaces];
            unset($passengersTrip[array_search($passengerToCancel, $passengersTrip)]);
            $trip->setPassengers(array_values($passengersTrip));
            $trip->setNumberPlacesRemaining($trip->getNumberPlacesRemaining() + $numberPlaces);

            // Modification sur l'objet User :
            $tripSave = $user->getTripSave();
            $tripToCancel = [$id, $numberPlaces];
            unset($tripSave[array_search($tripToCancel, $tripSave)]);
            $user->setTripSave(array_values($tripSave));
        }


        $user->setCancelTrip($user->getCancelTrip()+1);

        if ($user->getCancelTrip() >= 3){
            $user->setEnabled(0);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('fos_user_security_logout');
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('myTrip');
    }
}
