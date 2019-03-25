<?php
/**
 * Created by PhpStorm.
 * User: faris
 * Date: 13/03/19
 * Time: 16:50
 */

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const DRIVER_TYPE = "Driver";
    const PASSENGER_TYPE = "Passenger";
    const BOTH_TYPE = "Driver or Passenger";

    const SMOKE_AUTHORIZED = "Fumeur autorisé dans la voiture";
    const ACCESS_PHONENUMBER = "Accés au numéro de téléphone après réservation";
    const ACCESS_MAIL = "Accés au mail après réservation";
    const CONDITIONING_AIR = "Air conditionné";

    const ANIMALS = "Animaux";
    const ANIMALS_IN_CAGE = "En cage seulement";
    const NO_ANIMALS = "Pas d'animaux";
    const FREE_ANIMALS = "Libre";
    const INDIFFERENT_ANIMALS = "Indifferent";

    const BIKE_RACK="Porte-vélo";
    const SKI_RACK="Porte-ski";

    //à voir pour le type de véhicule parce qu'on va pas lister tous les possibles

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }


    /**
     * @ORM\Column(type="string", name="type")
     */
    protected $type=User::PASSENGER_TYPE;

    /**
     * @ORM\Column(type="array", name="preference")
     */
    protected $preference=[
        self::SMOKE_AUTHORIZED => false,
        self::ACCESS_PHONENUMBER => false,
        self::ACCESS_MAIL => false,
        self::CONDITIONING_AIR => false,
        self::ANIMALS => self::NO_ANIMALS,
        self::BIKE_RACK => false,
        self::SKI_RACK => false
    ];

    /**
     * @ORM\Column(type="string", name="phoneNumber", nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="array", name="tripSave", nullable=true)
     */
    protected $tripSave;

    /**
     * @ORM\Column(type="integer", name="cancelTrip")
     */
    protected $cancelTrip=0;

    /**
     * @return mixed
     */
    public function getCancelTrip()
    {
        return $this->cancelTrip;
    }

    /**
     * @param mixed $cancelTrip
     */
    public function setCancelTrip($cancelTrip): void
    {
        $this->cancelTrip = $cancelTrip;
    }

    /**
     * @return mixed
     */
    public function getTripSave()
    {
        return $this->tripSave;
    }

    /**
     * @param mixed $tripSave
     */
    public function setTripSave($tripSave)
    {
        $this->tripSave = $tripSave;
    }

    public function addTripSave($trip){
        $trips = $this->tripSave;
        $trips[] = $trip;
        $this->tripSave = $trips;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param mixed $preference
     */
    public function setPreference($preference)
    {
        $this->preference = $preference;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
}