<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Intervenant;
use App\Entity\Matiere;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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
            $manager->persist($classe);
        }

        
        for ($i = 1; $i <= 30; $i++){
            $agerandom = random_int(18, 27);
            $startrandom = random_int(2017, 2019);

            $etudiant = new Etudiant();
            $etudiant->setNom('NomEtudiant' . $i);
            $etudiant->setPrenom('PrénomEtudiant' . $i);
            $etudiant->setAge($agerandom . ' ans');
            $etudiant->setAnneestart($startrandom);

            // $etudiant->setNompromo($classe); #Pour avoir le dernier id des promo (ici A5) en automatique
            
            $manager->persist($etudiant);
        }

        for ($i = 1; $i <= 7; $i++){
            $anneerandom = random_int(2010, 2020);
            $intervenant = new Intervenant();
            $intervenant->setNom('NomIntervenant' . $i);
            $intervenant->setPrenom('PrenomIntervenant' . $i);
            $intervenant->setAnneestart($anneerandom);
            $manager->persist($intervenant);
        }

        $matieres = [
            1 => [
                'nomcours' => 'API PHP', 
                'datestart' => new \DateTime("2020-09-07 11:00:00"), 
                'dateend' => new \DateTime("2020-09-11 17:00:00")
            ],
            2 => [
                'nomcours' => 'Agile', 
                'datestart' => new \DateTime("2020-10-07 09:00:00"), 
                'dateend' => new \DateTime("2020-10-11 18:00:00")
            ],
            3 => [
                'nomcours' => 'Gestion budgets et risques', 
                'datestart' => new \DateTime("2021-02-22 08:00:00"), 
                'dateend' => new \DateTime("2020-02-26 17:00:00")
            ],
        ];
        
        foreach($matieres as $key => $value){
            $matiere = new Matiere(); 
            $matiere -> setNomcours($value['nomcours']);
            $matiere -> setDatestart($value['datestart']);
            $matiere -> setDateend($value['dateend']);

            // $matiere -> setNompromo() ;

            $manager->persist($matiere);
        }
        
        $admin = [
            1 => [
                'email' => 'alexis@edu.devinci.fr',
                'password' => '',
                'apiKey' => 'AlexisAdmin'
            ],
            2 => [
                'email' => 'nicolas@edu.devinci.fr',
                'password' => '',
                'apiKey' => 'NicolasAdmin'
            ],
            3 => [
                'email' => 'karine@edu.devinci.fr',
                'password' => '',
                'apiKey' => 'KarineAdmin'
            ],
        ];

        foreach($admin as $key => $value){
            $user = new User(); 

            $user -> setEmail($value['email']);
            $user -> setPassword($this->passwordEncoder->encodePassword($user,'password'));
            $user -> setApiKey($value['apiKey']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
