<?php
/**
 * Created by PhpStorm.
 * User: faris
 * Date: 13/03/19
 * Time: 16:50
 */

namespace AppBundle\Entity;

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

    const SMOKE_AUTHORIZED = "Smoke is authorized";
    const ACCESS_PHONENUMBER = "Access to phonenumber";
    const ACCESS_MAIL = "Access to mail";
    const CONDITIONING_AIR = "Conditioning air";

    const ANIMALS = "animals";
    const ANIMALS_IN_CAGE = "animals in cage";
    const NO_ANIMALS = "no animals";
    const FREE_ANIMALS = "free animals";
    const INDIFFERENT_ANIMALS = "indifferent for animals";

    const BIKE_RACK="bike rack";
    const SKI_RACK="ski rack";

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
        User::SMOKE_AUTHORIZED => false,
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
    protected   $phoneNumber;

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