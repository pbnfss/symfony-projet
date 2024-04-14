<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\Produit;
use App\Form\CreateFormationType;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class EmpServFormationController extends AbstractController
{

    #[Route('/empServFormation', name: 'app_emp_serv_formation')]
    public function index(): Response
    {
        return $this->render('emp_serv_formation/index.html.twig', [
            'controller_name' => 'EmpServFormationController',
        ]);
    }

    #[Route('/createProduit', name: 'app_produit')]
    public function ajoutAction(ManagerRegistry $doctrine)
    {
        $user = new Produit();
        $user -> setLibelle("");
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('emplambda/index.html.twig',
            [
                'controller_name' => 'EmpServFormationController',
            ]);
    }

    #[Route('/empCreateFormation', name: 'app_emp_create_formation')]
    public function ajoutFormation(Request $request, ManagerRegistry $doctrine): Response
    {
        $formation = new Formation();
        $form = $this->createForm(CreateFormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_emp_serv_formation');
        }

        return $this->render('emp_serv_formation/createFormation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gererInscription', name: 'app_emp_gerer_inscription')]
    public function gererInscription(Request $request, ManagerRegistry $doctrine)
    {
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['statut' => 'En cours de validation']);
        return $this->render('emp_serv_formation/traitementInscription.html.twig', array('lesInscriptions' => $inscriptions));
    }

    #[Route('/validerInscription/{id}', name: 'app_emp_validerInscription')]
    public function validerInscription(Request $request, ManagerRegistry $doctrine, Inscription $inscription)
    {
        $entityManager = $doctrine->getManager();
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['statut' => 'En cours de validation']);
        $inscription->setStatut("valide");
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->render('emp_serv_formation/traitementInscription.html.twig', array('lesInscriptions' => $inscriptions));
    }

    #[Route('/refuserInscription/{id}', name: 'app_emp_refuserInscription')]
    public function refuserInscription(Request $request, ManagerRegistry $doctrine, Inscription $inscription, SessionInterface $session)
    {
        $entityManager = $doctrine->getManager();
        $inscriptions = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['statut' => 'En cours de validation']);
        $inscription->setStatut("refus");
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->render('emp_serv_formation/traitementInscription.html.twig', array('lesInscriptions' => $inscriptions));
    }

//    #[Route('/.../{id}', name: '')]
//    public function inscription(Request $request, ManagerRegistry $doctrine, $id, SessionInterface $session): Response
//    {
//        $entityManager = $doctrine->getManager();
//
//        $formation = $entityManager->getRepository(Formation::class)->find($id);
//        if (!$formation) {
//            throw $this->createNotFoundException('La formation n existe pas.');
//        }
//
//        $employeId = $session->get('id');
//        $employe = $entityManager->getReference(Employe::class, $employeId);
//        $inscription = new Inscription();
//        $inscription->setFormation($formation);
//        $inscription->setEmploye($employe);
//        $inscription->setStatut("En cours de validation");
//        $entityManager->persist($inscription);
//        $entityManager->flush();
//
//        return $this->redirect('/voirFormations');
//    }

}
