<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use App\Form\ClasseFormType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(ClasseRepository $classeRepository, EntityManagerInterface $objectManager)
    {
        $this->classeRepository = $classeRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/classes", name="api_get_classes", methods={"GET"})
     * 
     * @return Response
     */
    public function getClasses(ClasseRepository $classeRepository, SerializerInterface $serializer): Response #Mettre (SerializerInterface $serializer) pour 2e exemple
    {
        #Première manière
        $classes = $classeRepository->findAll(); #Select All from TaskRepository
        $json = $serializer->serialize($classes, 'json'); #Récupere task est transforme en json

        return new Response($json); 


        // #Deuxième manière
        // $tasks = $taskRepository->findAllAsArray();
        // return $this->json($tasks);

        // #Le most et dans config>package>framework
        // $tasks = $this->taskRepository->findBy([
        //     'user' => $this->user,
        // ]);

        // $classes = $this->taskRepository->findAllByUser($this->user);
        // return $this->json($classes);
    } #Envoie la liste des tâches

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

        if (!$classe instanceof Classe){ #Si la tâche est vide ça renvoie un message d'erreur
            // return $this->error();
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

        if (!$classe instanceof Classe){ #Si la tâche est vide ça renvoie un message d'erreur /
            // return $this->error();
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
        // dd($request->request->all());

        $classe = new Classe;

        $form = $this->createForm(ClasseFormType::class, $classe); #Partie optimisée comparé à "$classe->setName($request->request->get('name')); ..."
        $form->submit($request->request->all());

        // $classe->setNompromo($request->request->get('nompromo'));
        // $classe->setAnneefin($request->request->get('anneefin')); #Non optimisée par rapport au $form au dessus

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
