<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\User;
use App\Repository\ClasseRepository;
use App\Form\ClasseFormType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;

    /**
     * @Route("/api")
     */
class ClasseController extends AbstractController
{

    /**
     * @var ClasseRepository
     */
    private $classeRepository;
    private $objectManager;

    public function __construct(ClasseRepository $classeRepository, EntityManagerInterface $objectManager, RequestStack $request)
    {
        $this->classeRepository = $classeRepository;
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
     * @Route("/classes", name="api_get_classes", methods={"GET"})
     * 
     * @return Response
     */
    public function getClasses(ClasseRepository $classeRepository, SerializerInterface $serializer): Response
    {
        $classes = $classeRepository->findAll(); #Select All from ClasseRepository
        $json = $serializer->serialize($classes, 'json'); #Récupere classes est transforme en json

        return new Response($json); 

    } #Envoie la liste des classes

    /**
     * @Route("/classes/{classeId}", name="api_get_classe", methods={"GET"})
     * 
     * @param $classeId
     * 
     * @return Response
     */
    public function getClasse($classeId): Response 
    {
        $classe = $this->classeRepository->find($classeId);

        if (!$classe instanceof Classe){ #Si la classe est vide ça renvoie un message d'erreur
            
            throw new NotFoundHttpException();
        }

        return $this->json($classe);
    }

    /**
     * @Route("/classes/{classeId}", name="api_delete_classe", methods={"DELETE"})
     * 
     * @param $classeId
     * 
     * @return Response
     */
    public function deleteClasse($classeId): Response 
    {
        $classe = $this->classeRepository->find($classeId);

        if (!$classe instanceof Classe){
            throw new NotFoundHttpException();
        }

        $this->objectManager->remove($classe);
        $this->objectManager->flush();

        return $this->json('Classe supprimé');
    }

    /**
     * @Route("/classes", name="api_add_classe", methods={"POST"})
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addClasse(Request $request): Response 
    {
        $classe = new Classe;

        $form = $this->createForm(ClasseFormType::class, $classe);
        $form->submit($request->request->all());

        $this->objectManager->persist($classe);
        $this->objectManager->flush();

        return $this->json($classe);
        
    }

    /**
     * @Route("/classes/{classeId}", name="api_update_classe", methods={"PUT"})
     * 
     * @param Request $request
     * @param $classeId
     * 
     * @return Response
     */
    public function updateClasse($classeId, Request $request): Response 
    {

        $classe = $this->classeRepository->find($classeId);

        if (!$classe instanceof Classe){ 
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ClasseFormType::class, $classe); 
        $form->submit($request->request->all());
        $this->objectManager->persist($classe);
        $this->objectManager->flush();

        return $this->json($classe);
    }
}
