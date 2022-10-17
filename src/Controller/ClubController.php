<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function listClub(ClubRepository $clubRespository): Response
    {
            return $this->render('club/listClub.html.twig', [
                'clubs' => $clubRespository->findAll(),
            ]);
       
    }
    #[Route('/listFormation', name: 'list_formation')]
    public function list()
    {
        $var="3A32";
        $var1="J12";
        $formations = array(
            array('ref' => 'form147', 'Titre' => 'Formation Symfony
            4','Description'=>'formation pratique',
            'date_debut'=>'12/06/2022', 'date_fin'=>'19/06/2020',
            'nb_participants'=>19) ,
            array('ref'=>'form1778','Titre'=>'Formation SOA' ,
            'Description'=>'formation theorique','date_debut'=>'03/12/2020','date_fin'=>'10/12/2020',
            'nb_participants'=>0),
            array('ref'=>'form178','Titre'=>'Formation Angular' ,
            'Description'=>'formation theorique','date_debut'=>'10/06/2020','date_fin'=>'14/06/2020',
            'nb_participants'=>13));
        return $this->render('club/list.html.twig', array("x"=>$var,"y"=>$var1,"tabFormation"=>$formations));
    }
    #[Route('/reservation', name: 'app_reservation')]
    public function Reservation(){
        return new Response("nouvelle page de reservation");
    }

    #[Route('/AjoutClub', name: 'app_ajout_club')]
    public function AjoutClub(Request $request, ManagerRegistry $doctrine):Response{
        $club=new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();
        $em->persist($club);
        $em->flush();
            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/AjoutClub.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }
    #[Route('/modifierClub/{id}', name: 'app_modifier_club')]
    public function ModifierClub(Request $request, ManagerRegistry $doctrine,$id,ClubRepository $repository):Response{
        $club=$repository->find($id);
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();

        $em->flush();
            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/ModifierClub.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }
    #[Route('/supprimerClub/{id}', name: 'app_supprimer_club')]
    public function SupprimerClub(Request $request, ManagerRegistry $doctrine,$id,ClubRepository $repository):Response{
        $club=$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($club);
        $em->flush();
            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        

       
    }
}
