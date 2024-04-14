<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\InscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;
use App\Entity\Employe;
use App\Entity\FormationRepository;
use App\Entity\Produit;
use App\Form\CreateFormationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EmplambdaController extends AbstractController
{
    #[Route('/emplambda', name: 'app_emplambda')]
    public function index(): Response
    {
        return $this->render('emplambda/index.html.twig', [
            'controller_name' => 'EmplambdaController',
        ]);
    }

    #[Route('/voirFormations', name: 'app_voir_les_formations')]
    public function listeFormations(Request $request, ManagerRegistry $doctrine, SessionInterface $session)
    {
        //array_filter() -> permet de filtrer pour n'afficher que les formations auxquelles l'employe n'est pas encore inscrit
        $entityManager = $doctrine->getManager();
        $employeId = $session->get('id');

        if (!$employeId) {
            return $this->redirectToRoute('route_name_for_error_handling');
        }

        $employe = $entityManager->getRepository(Employe::class)->find($employeId);

        if (!$employe) {
            return $this->redirectToRoute('route_name_for_error_handling');
        }
        $query = $entityManager->createQuery(
            'SELECT f FROM App\Entity\Formation f WHERE f.id NOT IN (
            SELECT IDENTITY(ins.formation) FROM App\Entity\Inscription ins WHERE ins.employe = :employeId
        )'
        )->setParameter('employeId', $employeId);

        $formationsNonInscrites = $query->getResult();

        return $this->render('emplambda/formations.html.twig', [
            'lesFormations' => $formationsNonInscrites,
        ]);
    }


    #[Route('/inscription/{id}', name: 'app_inscriptionEmp')]
    public function inscription(Request $request, ManagerRegistry $doctrine, $id, SessionInterface $session): Response
    {
        $entityManager = $doctrine->getManager();

        $formation = $entityManager->getRepository(Formation::class)->find($id);
        if (!$formation) {
            throw $this->createNotFoundException('La formation n existe pas.');
        }

        $employeId = $session->get('id');
        $employe = $entityManager->getReference(Employe::class, $employeId);

        $inscription = new Inscription();
        $inscription->setFormation($formation);
        $inscription->setEmploye($employe);
        $inscription->setStatut("En cours de validation");
        $entityManager->persist($inscription);
        $entityManager->flush();

        return $this->redirect('/voirFormations');
    }

    #[Route('/voirInscriptions', name: 'app_voir_les_inscriptions')]
    public function listeInscriptions(Request $request, ManagerRegistry $doctrine, SessionInterface $session)
    {
        $employe = $session->get('id');
        $inscription = $doctrine->getManager()->getRepository(Inscription::class)->findBy(['employe' => $employe]);
        return $this -> render('emplambda/mesInscriptions.html.twig', array('lesFormations' => $inscription));
    }
}
