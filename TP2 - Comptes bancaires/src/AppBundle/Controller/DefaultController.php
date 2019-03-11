<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
    public function createAccountAction(Request $request) {
        $newAccount = new Account();

        $formBuilder = $this->get('form.factory')->createBuilder(AccountType::class, $newAccount);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            if(!ctype_alnum($data->getOwner()) || !preg_match('/^[A-z]{3,}[0-9]*/', $data->getOwner()) || $data->getAmount() < 0) {
                $this->get('session')->getFlashBag()
                    ->add('danger', 'Le compte n\'a pas pu etre créé pour une des raisons suivantes : Le nom ne commence pas par 3 lettres consécutives, ne contient pas des chiffres et des lettres uniquement ou le montant saisi est inférieur à 0 $');
                return $this->redirectToRoute('createAccount');
            }

            $em = $this->getDoctrine()->getManager();
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
    public function makeChangesAction(Request $request, $id) {

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
            ->add('amountToAdd', IntegerType::class, [
                "label" => "Saisissez le montant à déposer sur le compte.",
                "attr" => ["placeholder" => "Ex. 10.000"],
                "constraints" => [
                    new Type(['type' => 'numeric']),
                ],
            ])
            ->add('Deposer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['amountToAdd'] <= 0) {
                $this->get('session')->getFlashBag()->add('danger', 'Le montant doit être supérieur à 0 $ ! Nous n\'avons pas pu traiter votre demande.');
                
                return $this->render('default/add.html.twig', [
                    'form' => $form->createView(),
                    'id' => $id,
                    'account' => $account,
                ]);
            }
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
            ->add('amountToExtract', IntegerType::class,[
                "label"=> "Saisissez le montant à retirer",
                "attr" => ["placeholder" => "Ex. 10 000"],
                "constraints" => [
                    new Type(['type' => 'numeric']),
                ],
            ])
            ->add('Retirer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['amountToExtract'] <= 0) {
                $this->get('session')->getFlashBag()->add('danger', 'Le montant doit être supérieur à 0 $ ! Nous n\'avons pas pu traiter votre demande.');
                
                return $this->render('default/remove.html.twig', [
                    'form' => $form->createView(),
                    'id' => $id,
                    'account' => $account,
                ]);
            }
            $account->setAmount($account->getAmount() - $data['amountToExtract']);
            $em -> persist($account);
            $em -> flush();

            $this->get('session')->getFlashBag()->add('info', 'Le retrait a bien été effectué');

            return $this->redirectToRoute('makeChanges', [
                "id" => $id
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