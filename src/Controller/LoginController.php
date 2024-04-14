<?php

namespace App\Controller;

use App\Entity\Employe;
use PhpParser\Builder\Interface_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type as SFType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\LoginUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class LoginController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('projet/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/createLogin', name: 'app_login')]
    public function ajoutAction(ManagerRegistry $doctrine)
    {
        $user = new Employe();
        $user -> setLogin("tbo");
        $user -> setMdp("1234");
        $user -> setNom("Thibault");
        $user -> setPrenom("Laille");
        $user -> setStatut("0");
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('login/index.html.twig',
            [
                'controller_name' => 'LoginController',
            ]);
    }

    #[Route('/createUser', name:'app_nouv_login')]
    public function ajoutLoginUser(Request $request, ManagerRegistry $doctrine, $employe = null)
    {
        if ($employe == null)
        {
            $employe = new Employe();
        }
        $form = $this->createForm(CreateUserType::class, $employe);
        $form->handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            $em = $doctrine->getManager();
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('');
        }

        return $this->render('login/formulaire.html.twig', ['form'=>$form->createView()]);
    }

    //back de la connexion employe
    #[Route('/identification', name:'app_login_connexion')]
    public function connexionUser(Request $request, ManagerRegistry $doctrine, SessionInterface $session)
    {
        $employe = new Employe();
        $form = $this->createForm(LoginUserType::class, $employe);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $mdpH = (MD5($employe->getMdp().'15'));
            if($employeConnecte = $doctrine->getManager()->getRepository(Employe::class)->findOneBy([
                'login' => $employe->getLogin(),
                'mdp' => $mdpH
            ]))
            {
                $employeId = $employeConnecte->getId();
                $employeStatut = $employeConnecte->getStatut();
                $session->set('id', $employeId);
                $session->set('statut', $employeStatut);
                if ($employeConnecte && $employeStatut == 1) {
                    return $this->redirectToRoute('app_emp_serv_formation');
                }

                if ($employeConnecte && $employeStatut == 0)
                {
                    return $this->redirectToRoute('app_emplambda');
                }
            }
            else
            {
                echo "Impossible de vous connecter";
            }
        }

        return $this->render('login/formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deconnexion', name:'app_login_deconnexion')]
    public function deconnexionUser(Request $request, ManagerRegistry $doctrine, SessionInterface $session)
    {
        $employe = new Employe();
        $form = $this->createForm(LoginUserType::class, $employe);

        $session = null;

        return $this->render('projet/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
