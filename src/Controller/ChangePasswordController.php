<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordController extends Controller
{
    /**
     * @Route("/change-password", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $userInfo = ['oldPassword' => null, 'plainPassword' => null];

        $form = $this->createForm(ChangePasswordType::class, $userInfo);
        $form->handleRequest($request);

        $userInfo = $form->getData();
        $old_pwd = $userInfo['oldPassword'];
        $plainPassword = $userInfo['plainPassword'];
        $user = $this->getUser();
        $checkPass = $encoder->isPasswordValid($user, $old_pwd);

        if ($form->isSubmitted() && $form->isValid()) {

            $id = $this->getUser()->getId();

            $user = $userRepository->findOneBy(['id' => $id]);

            if ($checkPass === false) {
                $this->addFlash('error', 'wrong current password');
                return $this->redirectToRoute('change_password');
            }
            $password = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($password);
            $userRepository->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('User/changepassword.html.twig', array('form' => $form->createView()));
    }
}
