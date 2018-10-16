<?php

namespace App\Controller;

use App\Form\ChangeEmailType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChangeEmailController extends Controller
{
    /**
     * @Route("/change-email", name="change_email")
     */
    public function changeEmail(Request $request, UserRepository $userRepository)
    {

        $userInfo = ['email' => null];

        $form = $this->createForm(ChangeEmailType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userInfo = $form->getData();
            $email = $userInfo['email'];
            $id = $this->getUser()->getId();

            $user = $userRepository->findOneBy(['id' => $id]);


            $user->setEmail($email);
            $userRepository->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('User/change_email.html.twig', array('form' => $form->createView()));
    }
}
