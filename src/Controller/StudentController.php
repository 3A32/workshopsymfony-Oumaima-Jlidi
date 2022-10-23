<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
  
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $student): Response
    {
        return $this->render('student/list.html.twig', [
            'students' => $student->findAll(),
        ]);
    }
    #[Route('Ajoutstudent',name:'app_ajout_student')]
    public function Ajoutstudent(Request $request ,ManagerRegistry $doctrine):Response{
        $student=new Student();
        $form=$this->createForm(studentType::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
           $em=$doctrine->getManager();
           $em->persist($student);
           $em->flush();
                return $this->redirectToRoute('app_student',[],Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('student/ajout.html.twig',[
            'student'=>$student,
            'form'=>$form,
        ]);
    

    }
    #[Route('/modifierstudent/{nsc}',name:'app_modifier_student')]
    public function Modifierstudent(Request $request,ManagerRegistry $doctrine,$nsc,StudentRepository $repository){
        $student=$repository->find($nsc);
        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_student',[],Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('student/modifier.html.twig',[
            'student'=>$student,
            'form'=>$form,
        ]);
    }

    #[Route('/supprimerstudent/{nsc}', name: 'app_supprimer_student')]
    public function SupprimerClub( ManagerRegistry $doctrine,$nsc,StudentRepository $repository):Response{
        $student=$repository->find($nsc);
        $em=$doctrine->getManager();
        $em->remove($student);
        $em->flush();
            return $this->redirectToRoute('app_student', [], Response::HTTP_SEE_OTHER);
        

       
    }
}
