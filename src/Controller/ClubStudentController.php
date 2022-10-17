<?php

namespace App\Controller;
use App\Form\ClubType;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubStudentController extends AbstractController
{
    #[Route('/club/student', name: 'app_club_student')]
    public function index(ClubRepository $club): Response
    {
        return $this->render('club_student/list.html.twig', [
            'clubs' => $club->findAll(),
        ]);
    }
    #[Route('/ajoutClubStudent', name: 'app_ajout_club')]
    public function Ajoutstudent(Request $request, ManagerRegistry $doctrine): Response
    {
        $club=new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('app_club_student', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club_student/ajout.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }
}
