<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjetRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/{id}', name: 'app_task', requirements: ['id' => '\d+'])]
    public function index(int $id,Request $request, EntityManagerInterface $em, TaskRepository $task): Response
    {
        $task = $task->find($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('app_projet');
        }

        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            "form" => $form->createView(),
            "task" => $task
            
        ]);
    }

    #[Route('/delete/{id}', name: 'app_task_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, EntityManagerInterface $em, TaskRepository $task, Request $request): Response
    {
        $task = $task->find($id);
        if($task){
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('app_projet');
    }
    

    #[Route('/{projetId}/add-task', name: "app_task_add")]
    public function add(int $projetId, ProjetRepository $projet, EntityManagerInterface $em, Request $request): Response
    {

        $projet = $projet->find($projetId);
        $task = new Task();
        $task->setProjet($projet);
        $task->setStatus($request->get('status'));
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('app_projet');
        }

        return $this->render('task/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name:'app_task_edit', requirements: ['id' => '\d+'])]
    public function editProjet(int $id, TaskRepository $task, EntityManagerInterface $em, Request $request): Response
    {
        $task = $task->find($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('app_task');
        }

        return $this->render('task/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
