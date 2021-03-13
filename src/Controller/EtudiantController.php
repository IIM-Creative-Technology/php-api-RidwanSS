<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;


use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

    /**
     * @Route("/api")
     */
class EtudiantController extends AbstractController
{
    /**
    * @var EtudiantRepository
    */
    private $etudiantRepository;
    private $objectManager;

    public function __construct(EtudiantRepository $etudiantRepository, EntityManagerInterface $objectManager, RequestStack $request)
    {
        $this->etudiantRepository = $etudiantRepository;
        $this->objectManager = $objectManager;

        $apiToken = $request->getCurrentRequest()->headers->get('api-token');
        $user = $this->objectManager->getRepository(User::class)->findOneBy([
                'apiKey' => $apiToken,
            ]);
        if(!$user instanceof User){
            throw new HttpException(401, "Vous n'êtes pas autorisé"); #Si on met une bonne apikey (exemple api_key dans Tableplus(User)) on aura l'accès aux données de l'api
        }
        $this->user = $user;

    }

    /**
    * @Route("/etudiants", name="api_get_etudiants", methods={"GET"})
    */
    public function getEtudiants(EtudiantRepository $etudiantRepository, SerializerInterface $serializer): Response
    {
        $etudiants = $etudiantRepository->findAll(); #Select All from EtudiantRepository
        $json = $serializer->serialize($etudiants, 'json'); #Récupere etudiants est transforme en json
        
        return new Response($json); 
    }

    /**
     * @Route("/etudiants/{etudiantId}", name="api_get_etudiant", methods={"GET"})
     * 
     * @param $etudiantId
     * 
     * @return Response
     */
    public function getEtudiant($etudiantId): Response 
    {
        $etudiant = $this->etudiantRepository->find($etudiantId);

        if (!$etudiant instanceof Etudiant){
            throw new NotFoundHttpException();
        }

        return $this->json($etudiant);
    }

    /**
     * @Route("/etudiants/{etudiantId}", name="api_delete_etudiant", methods={"DELETE"})
     * 
     * @param $etudiantId
     * 
     * @return Response
     */
    public function deleteEtudiant($etudiantId): Response 
    {
        $etudiant = $this->etudiantRepository->find($etudiantId);

        if (!$etudiant instanceof Etudiant){
            throw new NotFoundHttpException();
        }

        $this->objectManager->remove($etudiant);
        $this->objectManager->flush();

        return $this->json('Etudiant supprimé');
    }

    /**
     * @Route("/etudiants", name="api_add_etudiant", methods={"POST"})
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addEtudiant(Request $request): Response 
    {
        $etudiant = new Etudiant;

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->submit($request->request->all());

        $this->objectManager->persist($etudiant);
        $this->objectManager->flush();

        return $this->json($etudiant);
        
    }

    /**
     * @Route("/etudiants/{etudiantId}", name="api_update_etudiant", methods={"PUT"})
     * 
     * @param Request $request
     * @param $etudiantId
     * 
     * @return Response
     */
    public function updateEtudiant($etudiantId, Request $request): Response 
    {

        $etudiant = $this->etudiantRepository->find($etudiantId);

        if (!$etudiant instanceof Etudiant){ 
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(EtudiantType::class, $etudiant); 
        $form->submit($request->request->all());
        $this->objectManager->persist($etudiant);
        $this->objectManager->flush();

        return $this->json($etudiant);
    }

}

