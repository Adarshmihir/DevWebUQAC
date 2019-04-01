<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    // Page d'accueil de la plateforme d'administration
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
        // Récupération de la liste des utilisateurs
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        // Renvoie de la page admin/index.html.twig avec la liste des utilisateurs en paramètres.
        return $this->render('admin/index.html.twig', [
            'users' => $users
        ]);
    }

    // Contrôleur permettant d'activer ou de désactiver un utilisateur selon son état.
    // Le contrôleur prend en paramètre l'ID de l'utilisateur.
    /**
     * @Route("/admin/enableUser/{id}", name="enableUser")
     */
    public function enableUserAction(Request $request, $id){
        // On récupère l'utilisateur grâce à son ID.
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        // Si l'utilisateur est actif, alors on le désactive. Sinon, on l'active. Le flashbag précise ce qui a été fait.
        if ($user->isEnabled()){
            $user->setEnabled(false);
            $request->getSession()->getFlashBag()->add('info', 'L\'utilisateur a bien été désactivé');
        } else {
            $user->setEnabled(true);
            $request->getSession()->getFlashBag()->add('info', 'L\'utilisateur a bien été activé');
        }

        // On persiste les changements en base de données.
        $em->persist($user);
        $em->flush();

        // On redirige l'utilisateur vers la route "admin".
        return $this->redirectToRoute('admin');
    }
}
