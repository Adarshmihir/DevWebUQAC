<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {




        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }

    /**
     * @Route("/createAccount", name="createAccount")
     */
    public function createAccountAction(Request $request){
        $newAccount = new Account();

        $formBuilder = $this->get('form.factory')->createBuilder(AccountType::class, $newAccount);
        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $form->handleRequest($request);

            $newAccount->setLastOp(new \DateTime());
            $newAccount->setCreationDate(new \DateTime('now'));
            $em->persist($newAccount);
            $em->flush();
        }

        return $this->render('default/createAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/makeChanges", name="makeChanges")
    */
    public function makeChangesAction(Request $request){
        return $this->render('default/makeChanges.html.twig');
    }

    /**
    * @Route("/add", name="add")
    */
    public function addAction(Request $request){
        return $this->render('default/add.html.twig');
    }

    /**
    * @Route("/remove", name="remove")
    */
    public function removeAction(Request $request){
        return $this->render('default/remove.html.twig');
    }

    /**
    * @Route("/position", name="position")
    */
    public function positionAction(Request $request){
        return $this->render('default/position.html.twig');
    }

}
