<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Form\UserType;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use mysql_xdevapi\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    //Page d'accueil pour tous les utilisateurs
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // On renvoie juste la page "default/index.html.twig".
        return $this->render('default/index.html.twig', [
        ]);
    }

    // Contrôleur qui permet à l'utilisateur de publier un trajet.
    /**
     * @Route("/newTrip", name="newTrip")
     */
    public function newTripAction(Request $request){
        // On récupère l'utilisateur courant. S'il n'est pas authentifié, il ne peut pas accéder à cette page. (configuration dans config/packages/security.yml)
        /** @var User $user */
        $user = $this -> getUser();

        // On crée un nouveau voyage dans lequel on va stocker le voyage créé.
        $newTrip = new Trip();

        // On crée le formulaire en utilisant celui définit dans TripType.php, et on ajoute juste un bouton pour soumettre le formulaire.
        $form = $this->createForm(TripType::class, $newTrip)
            ->add('Proposer', SubmitType::class, ['attr' => [ 'class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($request);


        // Dans le cas ou le formulaire est soumis
        if ($form->isSubmitted()){

            // On précise sur le voyage le nombre de place restantes et l'ID du conducteur
            $newTrip->setNumberPlacesRemaining($newTrip->getInitialNumberPlaces());
            $newTrip->setIdDriver($user->getId());


            // Récupération des coordonnées GPS des adresse données grâce à l'API de Google Maps.
            $urlStart = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $urlEnd = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $optionsStart = array("address"=>$newTrip->getStartingPlace(),"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $optionsEnd = array("address"=>$newTrip->getEndingPlace(),"key"=>"AIzaSyBk5VQ6Stqy4h02d6TvvBoT3KefsYZp1lA");
            $urlStart .= http_build_query($optionsStart,'','&');
            $urlEnd .= http_build_query($optionsEnd,'','&');

            // On fait ici les requêtes à l'API de Google pour récupérer les coordonnées à partir des adresses.
            $coordStart = json_decode((file_get_contents(htmlspecialchars_decode($urlStart))))->results[0]->geometry->location;
            $newTrip->setLatStart($coordStart->lat);
            $newTrip->setLngStart($coordStart->lng);

            $coordEnd = json_decode((file_get_contents(htmlspecialchars_decode($urlEnd))))->results[0]->geometry->location;
            $newTrip->setLatEnding($coordEnd->lat);
            $newTrip->setLngEnding($coordEnd->lng);

            // On précise le prix unitaire avec les frais de l'application fixés au nombre de kilomètres divisés par 100, en $.
            $newTrip->setUnitPricePlusFees($newTrip->getUnitPrice()+round(self::distance($coordStart->lat, $coordStart->lng, $coordEnd->lat, $coordEnd->lng)/100, 2));


            // On persiste les données en base.
            $em = $this->getDoctrine()->getManager();
            $em->persist($newTrip);
            $em->flush();

            // Le flashbag précise à l'utilisateur que le voyage a bien été créé.
            $request->getSession()->getFlashBag()->add('info', 'Le voyage a bien été proposé.');

            // On redirige l'utilisateur vers la page "Mes voyages".
            return $this->redirectToRoute('myTrip');
        }

        return $this->render('default/newTrip.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Contrôleur de la page permettant de faire une recherche de covoiturage.
    /**
     * @Route("/searchTrip", name="searchTrip")
     */
    public function searchTripAction(Request $request){
        $em = $this->getDoctrine()->getRepository(Trip::class);

        // On récupère tous les voyages disponible à partir de la date d'aujourd'hui.
        $trips = $em->findAllOrderedByDateSupToCurrentDate();

        // On crée un formulaire avec seulement 3 champs pour la recherche de l'utilisateur.
        $form = $this->createFormBuilder()
            ->add('startPlace', TextType::class, ['label' => 'Adresse de départ souhaitée', 'attr' => ['placeholder' => 'Entrer la ville de départ']])
            ->add('endPlace', TextType::class, ['label' => 'Adresse d\'arrivée souhaitée', 'attr' => ['placeholder' => 'Entrer la ville d\'arrivée']])
            ->add('dateTime', DateType::class, ['label' => 'Date de départ', 'widget' => 'single_text'])
            ->add('Rechercher', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-block' ]])
            ->getForm();

        $form->handleRequest($request);

        // Lorsque le formulaire est soumis
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

            // On initialise un tableau vide pour renvoyer les résultats.
            $searchTrip = [];

            /** @var Trip $trip */
            foreach ($trips as $trip){
                // Si la distance entre le point de départ demandé par l'utilisateur et le point de départ du voyage proposé additionné à la distance entre les points d'arrivées sont inférieure à 100km, alors on renvoie le résultat.
                if (self::distance($coordStart->lat, $coordStart->lng, $trip->getLatStart(), $trip->getLngStart()) + self::distance($coordEnd->lat, $coordEnd->lng, $trip->getLatEnding(), $trip->getLngEnding()) < 100) {
                    $searchTrip[] = $trip;
                }
            }

            // On renvoie la même page avec les résultats de la recherche.
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

    // Contrôleur permettant de faire une recherche avec un maximum de filtre possible.
    /**
     * @Route("/searchTripFilters", name="searchTripFilters")
     */
    public function searchTripFiltersAction(Request $request){
        $repo = $this->getDoctrine()->getRepository(Trip::class);

        // On récupère encore l'ensemble des voyages disponible à partir de la date courrante.
        $trips = $repo->findAllOrderedByDateSupToCurrentDate();


        // On crée un formulaire avec l'ensemble des filtres possibles : départ, arrivée, heure et date, autorisation de fumer, accès au numéro de téléphone après réservarion,
        // Accès au mail, air conditionné, politique sur les animaux, porte vélo, porte ski, type de pneu et espace disponible par passager.
        $form = $this->createFormBuilder()
            ->add('startPlace', TextType::class, ['label' => 'Adresse de départ souhaitée'])
            ->add('endPlace', TextType::class, ['label' => 'Adresse d\'arrivée souhaitée'])
            ->add('dateTime', DateType::class, ['label' => 'Date de départ', 'widget' => 'single_text'])
            ->add('smoke', CheckboxType::class, ['label' => 'Autorisation de fumer dans la voiture', 'required' => false])
            ->add('accessPhoneNumber', CheckboxType::class, ['label' => 'Accès au numéro de téléphone du conducteur après réservation', 'required' => false])
            ->add('accessMail', CheckboxType::class, ['label' => 'Accès au mail du conducteur après réservation', 'required' => false])
            ->add('conditionningAir', CheckboxType::class, ['label' => 'Air conditionné', 'required' => false])
            ->add('animals', ChoiceType::class, [
                'choices'  => [
                    'Non' => User::NO_ANIMALS,
                    'En cage' => User::ANIMALS_IN_CAGE,
                    'Oui' => User::FREE_ANIMALS,
                    'Indifférent' => User::INDIFFERENT_ANIMALS,
                ],
                'required' => true,
                'label' => 'Animaux autorisés'
            ])
            ->add('bike', CheckboxType::class, ['label' => 'Porte-vélos présent', 'required' => false])
            ->add('ski', CheckboxType::class, ['label' => 'Porte-skis présent', 'required' => false])
            ->add('tire', ChoiceType::class, [
                'choices' => [
                    'Hiver' => Trip::WINTER_TIRE,
                    'Été' => Trip::SUMMER_TIRE,
                    'Indifférent' => User::INDIFFERENT_ANIMALS
                ],
                'required' => true,
                'label' => 'Type de pneu du véhicule'
            ])
            ->add('spaceForPassenger', ChoiceType::class, [
                'choices' => [
                    'Valise' => Trip::SUITCASE,
                    'Sac à dos' => Trip::BACKPACK,
                    'Petit sac' => Trip::SMALLBAG
                ],
                'label' => 'Espace disponible par passager'
            ])
            ->add('Rechercher', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-block' ]])
            ->getForm();

        $form->handleRequest($request);


        // Si le formualaire est soumis
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


            $searchTrip = [];

            /** @var Trip $trip */
            foreach ($trips as $trip){
                // On vérifie d'abord que la somme des distances entre les deux points d'arrivées et les deux points de départs sont bien inférieure à 100km.
                if (self::distance($coordStart->lat, $coordStart->lng, $trip->getLatStart(), $trip->getLngStart()) + self::distance($coordEnd->lat, $coordEnd->lng, $trip->getLatEnding(), $trip->getLngEnding()) < 100) {
                    $driverPref = $this->getDoctrine()->getRepository(User::class)->find($trip->getIdDriver())->getPreference();
                    // On vérifie ici que les filtres s'appliquent bien.
                    if (
                        $data["smoke"] == $driverPref[User::SMOKE_AUTHORIZED] &&
                        $data["conditionningAir"] == $driverPref[User::CONDITIONING_AIR] &&
                        ($data["animals"] == $driverPref[User::ANIMALS] || $data["animals"] == User::INDIFFERENT_ANIMALS) &&
                        ($data["bike"] == $driverPref[User::BIKE_RACK] || (!$data["bike"])) &&
                        ($data["ski"] == $driverPref[User::SKI_RACK] || (!$data["ski"]))
                    ){
                        if(
                        ($data["accessPhoneNumber"] == $driverPref[User::ACCESS_PHONENUMBER] || (!$data["accessPhoneNumber"])) &&
                        ($data["accessMail"] == $driverPref[User::ACCESS_MAIL] || (!$data["accessMail"]) )){
                            if ($data["tire"] == $trip->getTireType() || $data["tire"] == User::INDIFFERENT_ANIMALS){
                                if ( ($data["spaceForPassenger"] == $trip->getAvailableSpacePerPassenger()) // Si l'espace disponible est plus grand que l'espace demandé, on renvoie quand même le résultat.
                                    || ($data["spaceForPassenger"] == Trip::SMALLBAG)
                                    || ($data["spaceForPassenger"] == Trip::BACKPACK && ($trip->getAvailableSpacePerPassenger() == Trip::SUITCASE))
                                ){
                                    $searchTrip[] = $trip;
                                }
                            }
                        }
                    }
                }
            }

            // On renvoie alors la meme page avec les résultats.
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


    // Calcule la distance en entre deux points et renvoie sa valeur en kilomètre
    public static function distance($startLat, $startLng, $endLat, $endLng) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($startLng);
        $rla1 = deg2rad($startLat);
        $rlo2 = deg2rad($endLng);
        $rla2 = deg2rad($endLat);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $meter = ($earth_radius * $d);
        return $meter / 1000;
    }

    // Contrôleur qui permet de modifier ses informations personnelles
    /**
     * @Route("/editPrivateInfo", name="editPrivateInfo")
     */
    public function editPrivateInfoAction(Request $request){
        // On récupère l'utilisateur courant.
        $user = $this->getUser();

        // On crée le formulaire (écrit dans UserType)
        $form = $this->createForm(UserType::class)
            ->add('Modifier', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-block']]);

        $form->handleRequest($request);

        // Si le formulaire est soumis
        if ($form->isSubmitted()){
            $data = $form->getData();

            // On modifie toute ses préférences tel qu'il les a choisies
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

            // On modifie son numéro de téléphone et on persiste les informations dans la BDD.
            $user->setPhoneNumber($data["NumeroDeTelephone"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Le flashbag précise que l'action a bien été menée et l'utilisateur est redirigée vers la page de son profil.
            $request->getSession()->getFlashBag()->add('info', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('default/editPrivateInfo.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // Contrôleur de la page permettant de voir le détail d'un voyage.
    /**
     * @Route("/trip/{id}", name="trip")
     */
    public function tripAction(Request $request, $id){
        $repository = $this->getDoctrine()->getRepository(Trip::class);
        // On récupére le voyage en question grâce à son ID.
        /** @var Trip $trip */
        $trip = $repository->find($id);

        // On récupère le conducteur du voyage.
        $driver = $this->getDoctrine()->getRepository(User::class)->find($trip->getIdDriver());

        // On crée un formualire pour proposer aux utilisateurs de choisir le nombre de places qu'ils souhaitent réservé.
        $form = $this->createFormBuilder()
            ->add('numberPlaces', IntegerType::class, ['label' => 'Nombre de place(s) (Entre 1 et ' . $trip->getNumberPlacesRemaining() . ')', 'attr' => ['min' => 1, 'max' => $trip->getInitialNumberPlaces()]])
            ->add('Send', SubmitType::class, ['label' => 'Passer à la caisse'])
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est soumis.
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // On redirige l'utilisateur vers la route qui permet de payer ses places,, avec le nombre de place et l'ID du voyage en paramètres.
            return $this->redirectToRoute('saveTrip', [
                'idTrip' => $id,
                'numberPlaces' => $data['numberPlaces']
            ]);
        }

        // Sinon, on renvoie la page du détail d'un voyage avec le voyage, le conducteur et le formulaire en paramètres.
        return $this->render('default/trip.html.twig', [
            'trip' => $trip,
            'driver' => $driver,
            'form' => $form->createView()
        ]);
    }

    // Contrôleur permettant de réserver des places pour un voyage.
    /**
     * @Route("/saveTrip/{idTrip}/{numberPlaces}", name="saveTrip")
     */
    public function saveTripAction(Request $request, $idTrip, $numberPlaces){
        // On récupère le voyage, et l'utilisateur courant.
        /** @var Trip $trip */
        $trip = $this->getDoctrine()->getRepository(Trip::class)->find($idTrip);
        /** @var User $user */
        $user = $this->getUser();

        // On crée un formulaire (ici il n'y a que payer car on ne communique pas avec une banque.)
        $form = $this->createFormBuilder()
            ->add('Send', SubmitType::class, ['label' => 'Payer'])
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est soumis :
        if ($form->isSubmitted() && $form->isValid()) {

            // On ajoute dans l'objet User le voyage, avec le nombre de places, et dans l'objet Trip le passager, avec le nombre de places.
            $user->addTripSave(["idTrip" => $idTrip, "numberPlaces" => $numberPlaces]);
            $trip->addPassengers(["passenger" => $user->getId(), "numberPlaces" => $numberPlaces]);

            // Le nombre de place restante devient alors le nombre de places disponible - le nombre de places réservés.
            $trip->setNumberPlacesRemaining($trip->getNumberPlacesRemaining()-$numberPlaces);

            // On persiste ces changements dans la BDD.
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($trip);
            $em->flush();

            // On redirige l'utilisateur vers la route pour rechercher un voyage.
            $request->getSession()->getFlashBag()->add('info', 'Vos places pour le covoiturage ont bien été réservée. <br> Vous pouvez les voir dans vos voyages.');
            return $this->redirectToRoute('searchTrip');
        }


        return $this->render('default/saveTrip.html.twig', [
            'trip' => $trip,
            'numberPlaces' => $numberPlaces,
            'form' => $form->createView()
        ]);
    }

    // Controleur de la page permettant de voir tous ses voyages.
    /**
     * @Route("/myTrip", name="myTrip")
     */
    public function myTripAction(Request $request){
        // On récupère l'utilisateur courant, puis tous les voyages que l'utilisateur a propsoé.
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getManager()->getRepository(Trip::class);
        $trips = $repo->findBy(['idDriver' => $user->getId()]);

        // On renvoie tous les voyages que l'utilisateur a reservé, et également ceux qu'il a proposé.
        return $this->render('default/myTrip.html.twig', [
            'TripSave' => $user->getTripSave(),
            'trips' => $trips,
        ]);
    }

    // Ce contrôleur ne correspond pas à une page, il renvoie seulement un bout de HTML. Il permet, si le conducteur accepte de donner ses coordonnées, d'afficher ses coordonnées.
    /**
     * @Route("/getUser/{id}", name="getUser")
     */
    public function getUserAction(Request $request, $id){
        /** @var User $user */
        // On récupère l'utilisateur, et les informations s'il accepte ou pas de donner son mail et/ou son téléphone après la réservation.
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getDoctrine()->getRepository(Trip::class)->find($id)->getIdDriver());
        $mail = $user->getPreference()[User::ACCESS_MAIL];
        $phone = $user->getPreference()[User::ACCESS_PHONENUMBER];

        // On écrit alors en HTML le contenu que l'on veut renvoyer.
        if ($mail && $phone){
            $data = "<b>Téléphone : </b>" . $user->getPhoneNumber() . ", <b>Email : </b>" . $user->getEmail();
        } else if ($mail && !$phone){
            $data = "<b>Email : </b>" . $user->getEmail() . ". Le conducteur n'a pas fourni son numéro de téléphone.";
        } else if (!$mail && $phone){
            $data = "<b>Téléphone : </b>" . $user->getPhoneNumber() . ". Le conducteur n'a pas fourni son adresse mail.";
        } else {
            $data = "Le conducteur n'a fourni aucune de ses coordonnées.";
        }

        // On renvoie cette réponse.
        $user = new Response($data);
        return $user;
    }

    //Contrôleur permettant de confirmer avant l'annulation d'un voyage (réservation ou proposition).
    /**
     * @Route("/confirmCancelTrip/{id}/{numberPlaces}", name="confirmCancelTrip")
     */
    public function confirmCancelTripAction(Request $request, $id, $numberPlaces){
        $trip = $this->getDoctrine()->getManager()->getRepository(Trip::class)->find($id);
        return $this->render('default/confirmCancelTrip.html.twig', [
            'trip' => $trip,
            'numberPlaces' => $numberPlaces
        ]);
    }

    // Contrôleur permettant d'annuler un voyage (réservation ou proposition).
    /**
     * @Route("/cancelTrip/{id}/{numberPlaces}", name="cancelTrip")
     */
    public function cancelTripAction(Request $request, $id, $numberPlaces, \Swift_Mailer $mailer){
        // On récupère l'utilisateur courant.
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Trip::class);
        // On récupère le voyage en question.
        /** @var Trip $trip */
        $trip = $repo->find($id);
            // Dans le cas d'un passager qui annule une réservation, le nombre de places ne sera jamais à 0.
        if ($numberPlaces=="0") {
            // Si il y a eu des réservations, alors on prévient les passagers.
            if ($trip->getNumberPlacesRemaining() != $trip->getInitialNumberPlaces()){
                foreach ($trip->getPassengers() as $passengerTrip){
                    $userPassenger = $this->getDoctrine()->getRepository(User::class)->find($passengerTrip['passenger']);

                    // Envoi d'un mail pour prévenir chaque passager.
                    $message = (new \Swift_Message('Annulation du voyage ' . $trip->getStartingPlace() . " - " . $trip->getEndingPlace()))
                        ->setFrom('TravelExpress@uqac.ca')
                        ->setTo($userPassenger->getEmail())
                        ->setBody('Nous avons le regret de vous annoncer que le conducteur du voyage ' . $trip->getStartingPlace() . " - " . $trip->getEndingPlace() . ' a malheureusement dû annuler le voyage. Aller salut !');

                    $mailer->send($message);


                    // On enlève également des voyages réservés de l'utilisateur les places, et on persiste ça en BDD.
                    $tripSave = $userPassenger->getTripSave();
                    unset($tripSave[array_search([$id, $passengerTrip['numberPlaces']], $tripSave)]);
                    $userPassenger->setTripSave($tripSave);
                    $em->persist($userPassenger);
                }
                $request->getSession()->getFlashBag()->add('info', 'Le voyage a bien été annulé, et les passagers ont reçu un mail. <br> Attention, au bout de 3 annulations, vous ne pourrez plus profiter des services de notre entreprise.');
            }
            $em->remove($trip);
        } else { // Si c'est un passager qui annule un voyage
            // Modification sur l'objet Trip : on retire le passager du voyage, et on modifie le nombre de place restante.
            $passengersTrip = $trip->getPassengers();
            $passengerToCancel = [$user->getId(), $numberPlaces];
            unset($passengersTrip[array_search($passengerToCancel, $passengersTrip)]);
            $trip->setPassengers(array_values($passengersTrip));
            $trip->setNumberPlacesRemaining($trip->getNumberPlacesRemaining() + $numberPlaces);

            // Envoi d'un mail au conducteur.
            $message = (new \Swift_Message('Annulation du voyage ' . $trip->getStartingPlace() . " - " . $trip->getEndingPlace()))
                ->setFrom('TravelExpress@uqac.ca')
                ->setTo($this->getDoctrine()->getRepository(User::class)->find($trip->getIdDriver())->getEmail())
                ->setBody('Nous avons le regret de vous annoncer que le passager du voyage ' . $trip->getStartingPlace() . " - " . $trip->getEndingPlace() . ' a malheureusement dû annuler le voyage.
                 Il avait réservé '. $numberPlaces .' places qui sont à nouveaux disponibles sur la plateforme. Aller salut !');

            $mailer->send($message);


            // Modification sur l'objet User : on enlève le voyage de ses voyages reservés.
            $tripSave = $user->getTripSave();
            $tripToCancel = [$id, $numberPlaces];
            unset($tripSave[array_search($tripToCancel, $tripSave)]);
            $user->setTripSave(array_values($tripSave));

            $request->getSession()->getFlashBag()->add('info', 'Le voyage a bien été annulé, et le conducteur a reçu un mail. <br> Attention, au bout de 3 annulations, vous ne pourrez plus profiter des services de notre entreprise.');
        }

        // On incrémente de 1 la variable cancelTrip de l'utilisateur qui annule le voyage.
        $user->setCancelTrip($user->getCancelTrip()+1);

        // Si cette variable atteint 3, ou plus, alors l'utilisateur est désactivé et redirigé vers la page de deconnexion.
        if ($user->getCancelTrip() >= 3){
            $user->setEnabled(0);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('fos_user_security_logout');
        }

        // On persiste tout ça en BDD et on redirige l'utilisateur vers la page "myTrip".
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('myTrip');
    }
}
