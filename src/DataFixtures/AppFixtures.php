<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Flight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // -- alimenter la table City en utilisant l'entité City
        $cities = ['Londre', 'Paris', 'Berlin', 'Madrid', 'Bruxelles','Rome','Amsterdam'];
        $tabObjCity = [];
        foreach ($cities as $name) {
            $city = new City();
            $city->setName($name);
            $tabObjCity[] = $city;
            $manager->persist($city);
        }

        // creation d'un seul vol
        $flight = new Flight();
        $flight
            ->setNumber($this->getFlightNumber())
            ->setSchedule(\DateTime::createFromFormat('H:i','08:00'))
            ->setSeat(28)
            ->setPrice(210)
            ->setReduction(false)
            ->setDeparture($tabObjCity[0])
            ->setArrival($tabObjCity[6]);
        $manager->persist($flight);
        $manager->flush();
    }

    /**
     * get a random flight number
     *
     * @return string
     */
    public function getFlightNumber():string 
    {
        // Tableau de lettre en majuscule
        $lettres = range('A', 'Z');
        // je mélange
        shuffle($lettres);
        // j'extrait le premier item du tableau
        $lettre = array_shift($lettres);
        // je recommence pour la seconde lettre
        shuffle($lettres);
        // j'extrait la seconde lettre
        $lettre .= array_shift($lettres);
        // un nombre sur 4 digit au hasard
        $nombre = mt_rand(1000, 9999);
        
        return $lettre.$nombre;

    }
}
