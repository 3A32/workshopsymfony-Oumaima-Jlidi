<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function ListClassroom(ClassroomRepository $classroom): Response
    {
        return $this->render('classroom/list.html.twig', [
            'classrooms' => $classroom->findAll(),
        ]);
    }
    #[Route('/ajoutClassroom', name: 'app_ajout_classroom')]
    public function AjoutClassroom(Request $request, ManagerRegistry $doctrine):Response{
        $classroom=new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();
        $em->persist($classroom);
        $em->flush();
            return $this->redirectToRoute('app_classroom', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classroom/ajout.html.twig', [
            'classroom' => $classroom,
            'form' => $form,
        ]);
    }
    #[Route('/modifierclassroom/{id}', name: 'app_modifier_classroom')]
    public function Modifierclassroom(Request $request, ManagerRegistry $doctrine,$id,ClassroomRepository $repository):Response{
        $classroom=$repository->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();

        $em->flush();
            return $this->redirectToRoute('app_classroom', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classroom/modifier.html.twig', [
            'classroom' => $classroom,
            'form' => $form,
        ]);
    }
    #[Route('/supprimerclassroom/{id}', name: 'app_supprimer_classroom')]
    public function Supprimerclassroom(Request $request, ManagerRegistry $doctrine,$id,ClassroomRepository $repository):Response{
        $classroom=$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
            return $this->redirectToRoute('app_classroom', [], Response::HTTP_SEE_OTHER);
        

       
    }
}
