<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjetController extends AbstractController
{
    #[Route('/', name: 'app_projet')]
    public function index(ProjetRepository $projets): Response
    {
        $projet = $projets->findAll();

        return $this->render('projet/index.html.twig', [
            'controller_name' => 'ProjetController',
            "projet" => $projet
        ]);
    }

    #[Route('/{id}', name: 'app_projet_projet', requirements: ['id' => '\d+'])]
    public function showProjet(int $id, ProjetRepository $projet, TaskRepository $task): Response 
    {
        $projet = $projet->find($id);
        $taskInDoing = $task->findTaskInDoing($id);
        $taskInTodo = $task->findTaskInTodo($id);
        $taskInDone = $task->findTakInDone($id);

        return $this->render('projet/projet.html.twig', [
            'projet' => $projet,
            'taskDoing' => $taskInDoing,
            'taskTodo' => $taskInTodo,
            'taskDone' => $taskInDone,
        ]);
    }

    #[Route('/add', name:'app_projet_add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projet');
        }
        return $this->render('projet/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name:'app_projet_edit', requirements: ['id' => '\d+'])]
    public function editProjet(int $id, ProjetRepository $projet, EntityManagerInterface $em, Request $request): Response
    {
        $projet = $projet->find($id);
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projet');
        }

        return $this->render('projet/add.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet
        ]);
    }

    #[Route('/{id}/delete', name: 'app_projet_delete', requirements: ['id' => '\d+'])]
    public function deleteProjet(int $id, Request $request, EntityManagerInterface $em, ProjetRepository $projet): Response
    {
        $projet = $projet->find($id);

        if($projet){
            $em->remove($projet);
            $em->flush();
        }
        
        return $this->redirectToRoute('app_projet');
    }
}
