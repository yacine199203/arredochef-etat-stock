<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MouvementStockController extends AbstractController
{
    /**
     * @Route("/mouvement-stock", name="mouvement")
     */
    public function index(): Response
    {
        return $this->render('mouvement_stock/index.html.twig', [
            'controller_name' => 'MouvementStockController',
        ]);
    }

    /**
     * @Route("/mouvement-stock/entree", name="entree")
     */
    public function entree(): Response
    {
        
        return $this->render('mouvement_stock/scanner.html.twig', [
            'mouvement' => 'Entrée',
        ]);
    }

    /**
     * @Route("/mouvement-stock/sortie", name="sortie")
     */
    public function sortie(): Response
    {
        
        return $this->render('mouvement_stock/scanner.html.twig', [
            'mouvement' => 'sortie',
        ]);
    }
}
