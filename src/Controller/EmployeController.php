<?php

namespace App\Controller;

use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeController extends AbstractController
{

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(): Response
    {
        return $this->render('welcome.html.twig');
    }

    #[Route('/employe', name: 'app_team')]
    public function employe(EmployeRepository $employes): Response
    {
        $employes = $employes->findAll();

        return $this->render('team/index.html.twig', [
            'controller_name' => 'EmployeController',
            'employe' => $employes
        ]);
    }

    #[Route('/employe/{id}/edit', name: 'app_team_edit', requirements: ['id' => '\d+'])]
    public function editEmploye(EmployeRepository $employes, int $id, EntityManagerInterface $em, Request $request): Response
    {
        $employe = $employes->find($id);
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('app_team', [
                'employe' => $employe,
                'id' => $id
            ]);
        }
        return $this->render('team/employe.html.twig', [
            'form' => $form->createView(),
            'employe' => $employe
        ]);
    }

    #[Route('/employe/{id}/delete', name: 'app_team_delete', requirements: ['id' => '\d+'])]
    public function deleteEmploye(int $id, EmployeRepository $employes, EntityManagerInterface $em): Response
    {
        $employe = $employes->find($id);

        if($employe){
            $em->remove($employe);
            $em->flush();
        }

        return $this->redirectToRoute('app_team');
    }
}
