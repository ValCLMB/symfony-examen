<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();


        // $email = $contactRepository->show();

        

        return $this->render('default/index.html.twig', [
            'contacts' => $contacts,
            // 'email' => $email,
        
        ]);
    }

    /**
    * @Route("/contact", name="contact")
    */
    public function contact(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Nouveau contact BDD
        $contact = new Contact();

        $contact->setEmail('test@test.com');
        $contact->setSubject('Ceci est un test');
        $contact->setMessage('Un message de test, pouvant être long, ou non. Celui-ci l\'est un peu');


        //Formulaire
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('default/contact.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
        ]);
    }
}
