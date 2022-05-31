<?php

namespace App\DataFixtures;

use App\Entity\Module;
use App\Entity\Professeur;
use App\Repository\ModuleRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfesseurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void 
    { 
        $modules = ['php','java','html','css','js'];
        for ($i=0; $i < 10; $i++) { 
            $prof= new Professeur(); 
            $prof->setNomComplet('prof'.$i)
                ->setGrade('grade'.$i) 
                 ->setSexe('sexe'.$i); 
            for ($j=0; $j < 2; $j++) { 
                $ref=rand(0,9);
                $prof->addClass($this->getReference('classe'.$ref)); 
                //$this->modules($prof,$modules,$manager);
            }
            foreach ($modules as  $module) {
               $newModule = new Module;
               $newModule->setLibelle($module);
               $prof->addModule($newModule);
            }
            
            $manager->persist($prof); 
        }         
        $manager->flush();     
    } 

    /* private function modules(Professeur $professeur, array $modules, ModuleRepository $repo,ObjectManager $manager){
        foreach ($modules as $module) {
            $object = $repo->findOneBy(array('libelle' => $module));
            if ($object == null) {
                $object = new Module();
                $object->setLibelle($module);
                $manager->persist($object);
            }
            $professeur->addModule($object);
        }
    } */
}
