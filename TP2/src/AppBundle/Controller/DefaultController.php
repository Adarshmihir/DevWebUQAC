<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Account::class);

        $AccountList = $repo->findAll();

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'accountList' => $AccountList
        ]);
    }

    /**
     * @Route("/createAccount", name="createAccount")
     */
    public function createAccountAction(Request $request){
        $newAccount = new Account();

        $formBuilder = $this->get('form.factory')->createBuilder(AccountType::class, $newAccount);
        $form = $formBuilder
                ->add('owner', TextType::class, ["label" => "Nom du propriétaire", "attr" => ["placeholder" => "Ex. John"]])
                ->add('amount', NumberType::class,["label" => "Montant initial", "attr" => ["placeholder" => "Ex. 10 000"]])
                ->add('Creer', SubmitType::class)
                ->getForm();

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $form->handleRequest($request);

            $newAccount->setLastOp(new \DateTime());
            $newAccount->setCreationDate(new \DateTime('now'));
            $em->persist($newAccount);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Le compte a bien été créé');

            return $this->redirectToRoute('homepage');

        }

        return $this->render('default/createAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/makeChanges/{id}", name="makeChanges")
    */
    public function makeChangesAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Account::class);

        $account = $repo->find($id);

        return $this->render('default/makeChanges.html.twig', [
            'account' => $account
        ]);
    }

    /**
    * @Route("/add/{id}", name="add")
    */
    public function addAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Account::class);

        $account = $repo->find($id);

        $form = $this->createFormBuilder()
            ->add('amountToAdd', NumberType::class, [
                "label" => "Saisissez le montant à déposer sur le compte.",
                "attr" => ["placeholder" => "Ex. 10 000"],
                "constraints" => [
                    new Type(['type' => 'numeric']),
                ],
            ])
            ->add('Deposer', SubmitType::class)
            ->getForm();

        $form->getData()['amountToAdd'] = 10;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->getData()['amountToAdd'] > 0) {
            $data = $form->getData();
            $account->setAmount($account->getAmount() + $data['amountToAdd']);
            $em -> persist($account);
            $em -> flush();

            $this->get('session')->getFlashBag()->add('info', 'Le depôt a bien été effectué');

            return $this->redirectToRoute('makeChanges', [
                "id" => $id
            ]);
        }

        return $this->render('default/add.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'account' => $account,
        ]);

        
        
    }

    /**
    * @Route("/remove/{id}", name="remove")
    */
    public function removeAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Account::class);

        $account = $repo->find($id);

        $form = $this->createFormBuilder()
            ->add('amountToExtract', NumberType::class,[
                "label"=> "Saisissez le montant à retirer",
                "attr" => ["placeholder" => "Ex. 10 000"],
                "constraints" => [
                    new Type(['type' => 'numeric']),
                ],
            ])
            ->add('Retirer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && (($account->getAmount() - $form->getData()['amountToExtract']) >= 0)) {
            $data = $form->getData();
            $account->setAmount($account->getAmount() - $data['amountToExtract']);
            $em -> persist($account);
            $em -> flush();

            $this->get('session')->getFlashBag()->add('info', 'Le retrait a bien été effectué');

            return $this->redirectToRoute('makeChanges', [
                "id" => $id,
                "account"=> $account
            ]);
        }

        return $this->render('default/remove.html.twig', [
            "account" => $account,
            "id" => $id,
            "form" => $form->createView()
        ]);
    }

    /**
    * @Route("/position/{id}", name="position")
    */
    public function positionAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Account::class);

        $account = $repo->find($id);

        return $this->render('default/position.html.twig', [
            'account' => $account
        ]);
    }

}
