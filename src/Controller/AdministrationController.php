<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/admin/administration", name="administration")
     */
    public function Administration()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findAll();

        return $this->render('Admin/index.html.twig', [
            'user' => $user,
        ]);

    }

    /**
     * @Route("/admin/edit/{id}", name="editUsers")
     */
    public function edit(Request $request, User $user){
        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'User updated!');
            /* @var User $updated_user */
            $updated_user = $form->getData();


            $em = $this->getDoctrine()->getManager();
            $em->persist($updated_user);
            $em->flush();

            return $this->redirectToRoute('administration');
        }

        return $this->render('admin/edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
}