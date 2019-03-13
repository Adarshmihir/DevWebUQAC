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

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const DRIVER_TYPE = "Driver";
    const PASSENGER_TYPE = "Passenger";

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
}