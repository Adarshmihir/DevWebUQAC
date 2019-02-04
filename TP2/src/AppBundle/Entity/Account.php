<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Owner", type="string", length=255)
     */
    private $owner;

    /**
     * @var int
     *
     * @ORM\Column(name="Amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LastOp", type="date")
     */
    private $lastOp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreationDate", type="date")
     */
    private $creationDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return Account
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Account
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set lastOp
     *
     * @param \DateTime $lastOp
     *
     * @return Account
     */
    public function setLastOp($lastOp)
    {
        $this->lastOp = $lastOp;

        return $this;
    }

    /**
     * Get lastOp
     *
     * @return \DateTime
     */
    public function getLastOp()
    {
        return $this->lastOp;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Account
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}

