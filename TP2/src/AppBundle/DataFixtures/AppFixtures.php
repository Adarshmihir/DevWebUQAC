<?php
/**
 * Created by PhpStorm.
 * User: faris
 * Date: 11/02/19
 * Time: 21:02
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $Account = new Account();
            $Account->setOwner('Account '.$i);
            $Account->setAmount(mt_rand(100, 10000));
            $Account->setCreationDate(new \DateTime());
            $manager->persist($Account);
        }

        $manager->flush();
    }
}
