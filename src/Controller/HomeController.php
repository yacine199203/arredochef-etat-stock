<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

    /**
     * @Route("/parametres", name="parametres")
     * @IsGranted("ROLE_ADMIN")
     */
    public function parametres(): Response
    {
        return $this->render('/home/parametres.html.twig', [
        ]);
    }
}
