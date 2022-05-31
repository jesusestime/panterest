<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
   


    #[Route('/admin', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('admin/pins', name: 'app_admin_pins_index')]
    public function pinsIndex(): Response
    {
        return $this->render('admin/pin_index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
