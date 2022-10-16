<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $student): Response
    {
        return $this->render('student/list.html.twig', [
            'students' => $student->findAll(),
        ]);
    }
    #[Route('/ajoutStudent', name: 'app_ajout_student')]
    public function Ajoutstudent(Request $request, ManagerRegistry $doctrine):Response{
        $student=new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();
        $em->persist($student);
        $em->flush();
            return $this->redirectToRoute('app_student', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/ajout.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }
    #[Route('/modifierStudent/{NSC}', name: 'app_modifier_student')]
    public function Modifierstudent(Request $request, ManagerRegistry $doctrine,$NSC,studentRepository $repository):Response{
        $student=$repository->find($NSC);
        $form = $this->createForm(studentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();

        $em->flush();
            return $this->redirectToRoute('app_student', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/modifier.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }
    #[Route('/supprimerStudent/{NSC}', name: 'app_supprimer_student')]
    public function SupprimerStudent( ManagerRegistry $doctrine,$NSC,studentRepository $repository):Response{
        $student=$repository->find($NSC);
        $em=$doctrine->getManager();
        $em->remove($student);
        $em->flush();
            return $this->redirectToRoute('app_student', [], Response::HTTP_SEE_OTHER);
        

       
    }
}
