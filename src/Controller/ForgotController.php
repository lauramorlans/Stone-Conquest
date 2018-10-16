<?php

namespace App\Controller;

use App\Form\ForgotType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgotController extends Controller
{
    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgot(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {

        $userInfo = ['username' => null, 'plainPassword' => null];

        $form = $this->createForm(ForgotType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userInfo = $form->getData();
            $username = $userInfo['username'];
            $plainPassword = $userInfo['plainPassword'];

            $user = $userRepository->findOneBy(['username' => $username]);
            if ($user === null) {
                $this->addFlash('danger', 'Invalid username');
                return $this->redirectToRoute('forgot_password');
            }
            $password = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($password);
            $userRepository->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('forgot.html.twig', array('form' => $form->createView()));
    }
}