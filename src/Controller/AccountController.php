<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function show(): Response
    {
        return $this->render('account/show.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    
    #[Route('/account/edit', name: 'app_account_edit',methods:'GET|POST')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Request $request,EntityManagerInterface $em): Response
    {
        $user=$this->getUser();

        $form=$this->createForm(UserFormType::class,$user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $em->flush();

            $this->addFlash('success','Account updated successfully');
            return $this->redirectToRoute('app_account');
        }

        return $this->renderForm('account/edit.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/account/change-password', name: 'app_account_change_password',methods:'GET|POST')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        
        $user=$this->getUser();
        $form=$this->createForm(ChangePasswordFormType::class,null,
        [
            'current_password_is_required'=>true
        ]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $user->setPassword($userPasswordHasherInterface->hashPassword($user,$form->get('plainPassword')->getData()));
            $em->flush();

            $this->addFlash('success','Your password updated successfully');
            return $this->redirectToRoute('app_account');
        }

        return $this->renderForm('account/change_password.html.twig', [
            'form' => $form
        ]);
    }
}
