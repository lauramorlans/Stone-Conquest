<?php

namespace App\Controller;

use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */

    public function contact(Request $request, \Swift_Mailer $mailer)
{
    $form = $this->createForm(ContactType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $contactFormData = $form->getData();

            $message = (new \Swift_Message('You Got Mail!'))
            ->setFrom($contactFormData['from'])
            ->setTo('lauramb.morlans@gmail.com')
            ->setSubject($contactFormData['subject'])
            ->setBody(
            $contactFormData['message'],
            'text/plain');

            $mailer->send($message);

            return $this->redirectToRoute('contact');
        }

    return $this->render('contact.html.twig', array('form' => $form->createView()));
}
}