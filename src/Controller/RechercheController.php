<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Inscription;
use App\Entity\Produit;
use App\Entity\Formation;
use App\Form\EmployeType;
use App\Form\RechercheEmployeType;
use App\Form\LoginUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class RechercheController extends AbstractController
{

    #[Route('/recherche', name: 'app_recherche')]
    public function rechercheFindBy(ManagerRegistry $doctrine)
    {
        $employe = $doctrine->getRepository(Employe::class)->findBy(['nom' => 'Bonnefis', 'prenom' => 'Pierre']);

        return $this->render('recherche/employes.html.twig',
            array('ensEmploye' => $employe)
        );
    }


    #[Route('/inscriptionRecherche', name: 'app_recherche_rechinscription')]
    public function rechInscription(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RechercheEmployeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nom = $data['nom'];
            $prenom = $data['prenom'];

            $inscriptions = $doctrine->getRepository(Inscription::class)->rechInscriptionEmploye($nom, $prenom);
            return $this->render('recherche/employes2.html.twig', [
                'form' => $form->createView(),
                'ensInscription' => $inscriptions,
            ]);
        }

        return $this->render('recherche/rechercheEmploye.html.twig', [
            'form' => $form->createView(),
            'ensInscription' => []
        ]);
    }

}