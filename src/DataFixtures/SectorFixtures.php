<?php

namespace App\DataFixtures;

use App\Entity\Secteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectorFixtures extends Fixture
{
    // public const SECTOR_REFERENCE = 'sector';

    public function load(ObjectManager $manager)
    {
        $sectorTypes = ['Recrutement', 'Informatique', 'Comptabilité', 'Direction'];
        $colors = ['CornflowerBlue', 'LightCoral', 'Lavender', 'LemonChiffon'];

        for ($i = 0; $i < 4; $i ++) {
            $sector = new Secteur();
            $sector->setName($sectorTypes[$i]);
            $sector->setColor($colors[$i]);
            $manager->persist($sector);
        }

        // $this->addReference(self::SECTOR_REFERENCE, $sector);

        $manager->flush();
    }
}
