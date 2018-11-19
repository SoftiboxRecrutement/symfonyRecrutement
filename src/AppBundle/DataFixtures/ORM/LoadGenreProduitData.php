<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Sfb\SiteBundle\Entity\GenreProduit;

class LoadGenreProduitData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tabs = array("femme","homme","mixte","enfant");
        foreach ($tabs as $i => $elt) {
            $tag[$i] = new GenreProduit();
            $tag[$i]->setName($elt);
            $manager->persist($tag[$i]);
        }
        $manager->flush();
    }
}