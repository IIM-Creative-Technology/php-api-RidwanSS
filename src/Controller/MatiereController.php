<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\User;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
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

class MatiereController extends AbstractController
{
    
    /**
     * @var MatiereRepository
     */
    private $matiereRepository;
    private $objectManager;

    public function __construct(MatiereRepository $matiereRepository, EntityManagerInterface $objectManager, RequestStack $request)
    {
        $this->matiereRepository = $matiereRepository;
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
     * @Route("/matieres", name="api_get_matieres", methods={"GET"})
     */
    public function getMatieres(MatiereRepository $matiereRepository, SerializerInterface $serializer): Response
    {
        $matieres = $matiereRepository->findAll(); #Select All from MatiereRepository
        $json = $serializer->serialize($matieres, 'json'); #Récupere matieres est transforme en json

        return new Response($json); 

    }

    /**
     * @Route("/matieres/{matiereId}", name="api_get_matiere", methods={"GET"})
     * 
     * @param $matiereId
     * 
     * @return Response
     */
    public function getMatiere($matiereId): Response 
    {
        $matiere = $this->matiereRepository->find($matiereId);

        if (!$matiere instanceof Matiere){
            throw new NotFoundHttpException();
        }

        return $this->json($matiere);
    }

    /**
     * @Route("/matieres/{matiereId}", name="api_delete_matiere", methods={"DELETE"})
     * 
     * @param $matiereId
     * 
     * @return Response
     */
    public function deleteMatiere($matiereId): Response 
    {
        $matiere = $this->matiereRepository->find($matiereId);

        if (!$matiere instanceof Matiere){
            throw new NotFoundHttpException();
        }

        $this->objectManager->remove($matiere);
        $this->objectManager->flush();

        return $this->json('Matière supprimée');
    }

    /**
     * @Route("/matieres", name="api_add_matiere", methods={"POST"})
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addMatiere(Request $request): Response 
    {
        $matiere = new Matiere;

        $form = $this->createForm(MatiereType::class, $matiere);
        $form->submit($request->request->all());

        $this->objectManager->persist($matiere);
        $this->objectManager->flush();

        return $this->json($matiere);
        
    }

    /**
     * @Route("/matieres/{matiereId}", name="api_update_matiere", methods={"PUT"})
     * 
     * @param Request $request
     * @param $matiereId
     * 
     * @return Response
     */
    public function updateMatiere($matiereId, Request $request): Response 
    {

        $matiere = $this->matiereRepository->find($matiereId);

        if (!$matiere instanceof Matiere){ 
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MatiereType::class, $matiere); 
        $form->submit($request->request->all());
        $this->objectManager->persist($matiere);
        $this->objectManager->flush();

        return $this->json($matiere);
    }

}
