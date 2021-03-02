<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Intervenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $promo = 2024;
        $a = 1;
        for ($i = 2; $i <= 5; $i++){
            $classe = new Classe();
            $classe->setNompromo('A' . $i);
            $classe->setAnneefin('Promo' . $promo);
            $promo = $promo - $a;
            $a = $a++;
            $manager->persist($classe); # Méthode persist "Enregistre moi une nouvelle tâche ($classe) en bdd"
        }

        
        for ($i = 1; $i <= 30; $i++){
            $agerandom = random_int(18, 27);
            $startrandom = random_int(2017, 2019);
            $etudiant = new Etudiant();
            $etudiant->setNom('NomEtudiant' . $i);
            $etudiant->setPrenom('PrénomEtudiant' . $i);
            $etudiant->setAge($agerandom . ' ans');
            $etudiant->setAnneestart($startrandom);
            $manager->persist($etudiant); #Méthode persist "Enregistre moi une nouvelle tâche ($classe) en bdd"
        }

        for ($i = 1; $i <= 7; $i++){
            $anneerandom = random_int(2010, 2020);
            $intervenant = new Intervenant();
            $intervenant->setNom('NomIntervenant' . $i);
            $intervenant->setPrenom('PrenomIntervenant' . $i);
            $intervenant->setAnneestart($anneerandom);
            $manager->persist($intervenant);
        }

        $manager->flush();
    }
}
