<?php
/**
 * Created by PhpStorm.
 * User: faris
 * Date: 14/03/19
 * Time: 00:46
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trip
 *
 * @ORM\Table(name="trip")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TripRepository")
 */
class Trip
{

    const WINTER_TIRE = "Pneu hiver";
    const SUMMER_TIRE = "Pneu été";

    const SUITCASE = "Valise";
    const BACKPACK = "Sac à dos";
    const SMALLBAG = "Petit sac";


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departureTime", type="datetime")
     */
    private $departureTime;

    /**
     * @var string
     *
     * @ORM\Column(name="startingPlace", type="string", length=255)
     */
    private $startingPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="endingPlace", type="string", length=255)
     */
    private $endingPlace;

    /**
     * @var float
     *
     * @ORM\Column(name="unitPrice", type="float")
     */
    private $unitPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="unitPricePlusFees", type="float")
     */
    private $unitPricePlusFees;

    /**
     * @var int
     *
     * @ORM\Column(name="initialNumberPlaces", type="integer")
     */
    private $initialNumberPlaces;

    /**
     * @var int
     *
     * @ORM\Column(name="numberPlacesRemaining", type="integer")
     */
    private $numberPlacesRemaining;

    /**
     * @var string
     *
     * @ORM\Column(name="tireType", type="string", length=255)
     */
    private $tireType;

    /**
     * @var string
     *
     * @ORM\Column(name="availableSpacePerPassenger", type="string", length=255)
     */
    private $availableSpacePerPassenger;

    /**
     * @var int
     *
     * @ORM\Column(name="idDriver", type="integer")
     */
    private $idDriver;

    /**
     * @var array|null
     *
     * @ORM\Column(name="passengers", type="array", nullable=true)
     */
    private $passengers;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set departureTime.
     *
     * @param \DateTime $departureTime
     *
     * @return Trip
     */
    public function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    /**
     * Get departureTime.
     *
     * @return \DateTime
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Set startingPlace.
     *
     * @param string $startingPlace
     *
     * @return Trip
     */
    public function setStartingPlace($startingPlace)
    {
        $this->startingPlace = $startingPlace;

        return $this;
    }

    /**
     * Get startingPlace.
     *
     * @return string
     */
    public function getStartingPlace()
    {
        return $this->startingPlace;
    }

    /**
     * Set endingPlace.
     *
     * @param string $endingPlace
     *
     * @return Trip
     */
    public function setEndingPlace($endingPlace)
    {
        $this->endingPlace = $endingPlace;

        return $this;
    }

    /**
     * Get endingPlace.
     *
     * @return string
     */
    public function getEndingPlace()
    {
        return $this->endingPlace;
    }

    /**
     * Set unitPrice.
     *
     * @param float $unitPrice
     *
     * @return Trip
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice.
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set unitPricePlusFees.
     *
     * @param float $unitPricePlusFees
     *
     * @return Trip
     */
    public function setUnitPricePlusFees($unitPricePlusFees)
    {
        $this->unitPricePlusFees = $unitPricePlusFees;

        return $this;
    }

    /**
     * Get unitPricePlusFees.
     *
     * @return float
     */
    public function getUnitPricePlusFees()
    {
        return $this->unitPricePlusFees;
    }

    /**
     * Set initialNumberPlaces.
     *
     * @param int $initialNumberPlaces
     *
     * @return Trip
     */
    public function setInitialNumberPlaces($initialNumberPlaces)
    {
        $this->initialNumberPlaces = $initialNumberPlaces;

        return $this;
    }

    /**
     * Get initialNumberPlaces.
     *
     * @return int
     */
    public function getInitialNumberPlaces()
    {
        return $this->initialNumberPlaces;
    }

    /**
     * Set numberPlacesRemaining.
     *
     * @param int $numberPlacesRemaining
     *
     * @return Trip
     */
    public function setNumberPlacesRemaining($numberPlacesRemaining)
    {
        $this->numberPlacesRemaining = $numberPlacesRemaining;

        return $this;
    }

    /**
     * Get numberPlacesRemaining.
     *
     * @return int
     */
    public function getNumberPlacesRemaining()
    {
        return $this->numberPlacesRemaining;
    }

    /**
     * Set tireType.
     *
     * @param string $tireType
     *
     * @return Trip
     */
    public function setTireType($tireType)
    {
        $this->tireType = $tireType;

        return $this;
    }

    /**
     * Get tireType.
     *
     * @return string
     */
    public function getTireType()
    {
        return $this->tireType;
    }

    /**
     * Set availableSpacePerPassenger.
     *
     * @param string $availableSpacePerPassenger
     *
     * @return Trip
     */
    public function setAvailableSpacePerPassenger($availableSpacePerPassenger)
    {
        $this->availableSpacePerPassenger = $availableSpacePerPassenger;

        return $this;
    }

    /**
     * Get availableSpacePerPassenger.
     *
     * @return string
     */
    public function getAvailableSpacePerPassenger()
    {
        return $this->availableSpacePerPassenger;
    }

    /**
     * Set idDriver.
     *
     * @param int $idDriver
     *
     * @return Trip
     */
    public function setIdDriver($idDriver)
    {
        $this->idDriver = $idDriver;

        return $this;
    }

    /**
     * Get idDriver.
     *
     * @return int
     */
    public function getIdDriver()
    {
        return $this->idDriver;
    }

    /**
     * Set passengers.
     *
     * @param array|null $passengers
     *
     * @return Trip
     */
    public function setPassengers($passengers = null)
    {
        $this->passengers = $passengers;

        return $this;
    }

    /**
     * Get passengers.
     *
     * @return array|null
     */
    public function getPassengers()
    {
        return $this->passengers;
    }
}
