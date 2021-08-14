<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementStockController extends AbstractController
{
    /**
     * @Route("/mouvement-stock", name="mouvement")
     * @IsGranted("ROLE_MANAGER")
     */
    public function index(): Response
    {
        return $this->render('mouvement_stock/index.html.twig', [
            'controller_name' => 'MouvementStockController',
        ]);
    }

    /**
     * @Route("/mouvement-stock/entree", name="entree")
     * @IsGranted("ROLE_MANAGER")
     */
    public function entree(): Response
    {
        
        return $this->render('mouvement_stock/scanner.html.twig', [
            'mouvement' => 'Entrée',
        ]);
    }

    /**
     * @Route("/mouvement-stock/sortie", name="sortie")
     * @IsGranted("ROLE_MANAGER")
     */
    public function sortie(): Response
    {
        
        return $this->render('mouvement_stock/scanner.html.twig', [
            'mouvement' => 'Sortie',
        ]);
    }

    /**
     * @Route("/mouvement-stock/entree/{ref}/{qte}", name="addQte")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addQte($ref,$qte,ProductRepository $productRepo): Response
    {
        $addQte= $productRepo->findOneByRef($ref);
        $newQte=$addQte->getQte()+$qte;
        $addQte->setQte($newQte);
        $manager=$this->getDoctrine()->getManager();
        $manager->persist($addQte); 
        $manager->flush();
        
        return $this->json(['code'=> 200, 'message'=>'ok','data'=>true],200);
    }

    /**
     * @Route("/mouvement-stock/sortie/{ref}/{qte}", name="subQte")
     * @IsGranted("ROLE_MANAGER")
     */
    public function subtractQte($ref,$qte,ProductRepository $productRepo): Response
    {
        $addQte= $productRepo->findOneByRef($ref);
        $newQte=$addQte->getQte()-$qte;
        $addQte->setQte($newQte);
        $manager=$this->getDoctrine()->getManager();
        $manager->persist($addQte); 
        $manager->flush();
        
        return $this->json(['code'=> 200, 'message'=>'ok','data'=>true],200);
    }
}
