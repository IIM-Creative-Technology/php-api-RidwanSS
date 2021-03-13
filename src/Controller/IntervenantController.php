<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Entity\User;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
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
class IntervenantController extends AbstractController
{

    /**
    * @var IntervenantRepository
    */
    private $intervenantRepository;
    private $objectManager;

    public function __construct(IntervenantRepository $intervenantRepository, EntityManagerInterface $objectManager, RequestStack $request)
    {
        $this->intervenantRepository = $intervenantRepository;
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
     * @Route("/intervenants", name="api_get_intervenants", methods={"GET"})
     */
    public function getEtudiants(SerializerInterface $serializer): Response 
    {
        $intervenants = $this->intervenantRepository->findAll(); #Select All from IntervenantRepository
        $json = $serializer->serialize($intervenants, 'json'); #Récupere intervenants est transforme en json

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
