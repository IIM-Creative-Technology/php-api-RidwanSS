<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;


use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function __construct(EtudiantRepository $etudiantRepository, EntityManagerInterface $objectManager)
    {
        $this->etudiantRepository = $etudiantRepository;
        $this->objectManager = $objectManager;
    }

    /**
    * @Route("/etudiants", name="api_get_etudiants", methods={"GET"})
    */
    public function getEtudiants(EtudiantRepository $etudiantRepository, SerializerInterface $serializer): Response #Mettre (SerializerInterface $serializer) pour 2e exemple // EtudiantRepository $etudiantRepository, SerializerInterface $serializer
    {
        // #Première manière
        $etudiants = $etudiantRepository->findAll(); #Select All from TaskRepository
        $json = $serializer->serialize($etudiants, 'json'); #Récupere task est transforme en json

        return new Response($json); 

        // $etudiants = $this->etudiantRepository->findAllByNompromo($this->nompromo);
        // return $this->json($etudiants);
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

