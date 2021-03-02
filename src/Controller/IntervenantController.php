<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
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
class IntervenantController extends AbstractController
{

    /**
    * @var IntervenantRepository
    */
    private $intervenantRepository;
    private $objectManager;

    public function __construct(IntervenantRepository $intervenantRepository, EntityManagerInterface $objectManager)
    {
        $this->intervenantRepository = $intervenantRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/intervenants", name="api_get_intervenants", methods={"GET"})
     */
    public function getEtudiants(SerializerInterface $serializer): Response #Mettre (SerializerInterface $serializer) pour 2e exemple
    {
        #Première manière
        $intervenants = $this->intervenantRepository->findAll(); #Select All from TaskRepository
        $json = $serializer->serialize($intervenants, 'json'); #Récupere task est transforme en json

        return new Response($json); 
    }

    /**
     * @Route("/intervenants/{intervenantId}", name="api_get_intervenant", methods={"GET"})
     * 
     * @param $intervenantId
     * 
     * @return Response
     */
    public function getIntervenant($intervenantId): Response 
    {
        $intervenant = $this->intervenantRepository->find($intervenantId);

        if (!$intervenant instanceof Intervenant){
            throw new NotFoundHttpException();
        }

        return $this->json($intervenant);
    }

    /**
     * @Route("/intervenants/{intervenantId}", name="api_delete_intervenant", methods={"DELETE"})
     * 
     * @param $intervenantId
     * 
     * @return Response
     */
    public function deleteIntervenant($intervenantId): Response 
    {
        $intervenant = $this->intervenantRepository->find($intervenantId);

        if (!$intervenant instanceof Intervenant){
            throw new NotFoundHttpException();
        }

        $this->objectManager->remove($intervenant);
        $this->objectManager->flush();

        return $this->json('Intervenant supprimé');
    }

    /**
     * @Route("/intervenants", name="api_add_intervenant", methods={"POST"})
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addIntervenant(Request $request): Response 
    {
        $intervenant = new Intervenant;

        $form = $this->createForm(IntervenantType::class, $intervenant);
        $form->submit($request->request->all());

        $this->objectManager->persist($intervenant);
        $this->objectManager->flush();

        return $this->json($intervenant);
        
    }

    /**
     * @Route("/intervenants/{intervenantId}", name="api_update_intervenant", methods={"PUT"})
     * 
     * @param Request $request
     * @param $intervenantId
     * 
     * @return Response
     */
    public function updateIntervenant($intervenantId, Request $request): Response 
    {

        $intervenant = $this->intervenantRepository->find($intervenantId);

        if (!$intervenant instanceof Intervenant){ 
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(IntervenantType::class, $intervenant); 
        $form->submit($request->request->all());
        $this->objectManager->persist($intervenant);
        $this->objectManager->flush();

        return $this->json($intervenant);
    }
}
