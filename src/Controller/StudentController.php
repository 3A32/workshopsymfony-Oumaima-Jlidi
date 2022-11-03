<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\SearchStudentType;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
  
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $repository,Request $request): Response
    {
        $students= $repository->findAll();
        $studentsBynsc=$repository->getStudentsOrdredBynsc();
        $formSearch= $this->createForm(SearchStudentType::class);
        $formSearch->handleRequest($request) ;
        $topStudents= $repository->topStudent();
        if($formSearch->isSubmitted()){
             $nsc=$formSearch->getData();
             $result= $repository->findStudentBynsc($nsc);
             return $this->renderForm("student/list.html.twig",
                 array("students"=>$result,"bynsc"=>$studentsBynsc,"searchForm"=>$formSearch, "topStudent"=>$topStudents));
         }
        return $this->renderForm("student/list.html.twig",
       array("students"=>$students,
                "bynsc"=>$studentsBynsc,
                "searchForm"=>$formSearch,
                 "topStudent"=>$topStudents));
    }
    #[Route('Ajoutstudent',name:'app_ajout_student')]
    public function Ajoutstudent(Request $request ,ManagerRegistry $doctrine):Response{
        $student=new Student();
        $form=$this->createForm(studentType::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
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
    #[Route('/showclassroom/{id}', name: 'app_showStudent')]
    public function showClassroom(StudentRepository  $rep,ClassroomRepository  $repository ,$id)
    {
     $classroom= $repository->find($id);
     $students= $rep->getStudentsByClassroom($id);
     return $this->render("student/showClassroom.html.twig",array(
         "classrom"=>$classroom,
         'students'=>$students));
    }
}
