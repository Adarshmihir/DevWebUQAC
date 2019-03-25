<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/enableUser/{id}", name="enableUser")
     */
    public function enableUserAction(Request $request, $id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        if ($user->isEnabled()){
            $user->setEnabled(false);
            $request->getSession()->getFlashBag()->add('info', 'L\'utilisateur a bien été désactivé');
        } else {
            $user->setEnabled(true);
            $request->getSession()->getFlashBag()->add('info', 'L\'utilisateur a bien été activé');
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}
