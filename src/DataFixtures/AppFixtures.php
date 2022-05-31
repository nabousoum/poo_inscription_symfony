<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { 
        $filieres=['Dev Web','Dev Mobile','Dev Web Mobile'] ; 
        $niveaux=['L1','L2','L3'] ; 
        for ($i=0; $i <10 ; $i++) { 
            # code... 
            $classe= new Classe(); 
            $rand=rand(0,2); 
            $classe->setFiliere($filieres[$rand])
                   ->setNiveau($niveaux[$rand]) 
                   ->setLibelle($niveaux[$rand].' '.$filieres[$rand]); 
            $manager->persist($classe); 
            $this->addReference('classe'.$i,$classe); 
        } 
        $manager->flush(); 
    }
}
