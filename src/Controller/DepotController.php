<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Form\DepotType;
use App\Form\NameSearchType;
use App\Repository\DepotRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepotController extends AbstractController
{
    /**
     * @Route("/depot", name="depot")
     */
    public function index(DepotRepository $depotRepo,Request $request): Response
    {
        $depots= $depotRepo->findBy(array(), array('id' => 'DESC'));
        $formName = $this->createForm(NameSearchType::class);
        $formName-> handleRequest($request);
        if($formName->isSubmitted() && $formName->isValid())
        {
            $name = $formName->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM depot d
            WHERE d.libelle LIKE \'%'.$name.'%\''.' ORDER BY d.id DESC';
            $result=$manager->prepare($sql);
            $depots=$result->executeQuery();

        }
        return $this->render('depot/index.html.twig', [
            'depots'=>$depots,
            'formName'=> $formName->createView(),
        ]);
    }

    /**
     * permet d'ajouter un dépôt
     * @Route("/ajouter-depot", name="addDepot")
     * @return Response
     */
    public function addDepot(Request $request)
    {
        $addDepot = new Depot();
        $addDepotForm = $this->createForm(DepotType::class,$addDepot);
        $addDepotForm-> handleRequest($request);
        if($addDepotForm->isSubmitted() && $addDepotForm->isValid() && empty($addDepotForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($addDepot); 
            $manager->flush();
            return $this-> redirectToRoute('depot');
        }
        return $this->render('depot/addDepot.html.twig', [
            'addDepotForm'=> $addDepotForm->createView(),
        ]);
    }

    /**
     * permet de modifier un dépôt
     * @Route("/modifier-depot/{slug}", name="editDepot")
     * @return Response
     */
    public function editDepot($slug,DepotRepository $depotRepo,Request $request)
    {
        $editDepot = $depotRepo->findOneBySlug($slug);
        $editDepotForm = $this->createForm(DepotType::class,$editDepot);
        $editDepotForm-> handleRequest($request);
        if($editDepotForm->isSubmitted() && $editDepotForm->isValid() && empty($editDepotForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($editDepot); 
            $manager->flush();
            return $this-> redirectToRoute('depot');
        }
        return $this->render('depot/editDepot.html.twig', [
            'editDepotForm'=> $editDepotForm->createView(),
        ]);
    }

    /**
     * permet de supprimer une dépôt
     * @Route("/supprimer-depot/{slug} ", name="removeDepot")
     * @return Response
     */
    public function removeDepot($slug,DepotRepository $depotRepo)
    {   
        $removeDepot = $depotRepo->findOneBySlug($slug);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeDepot); 
        $manager->flush();
        return $this-> redirectToRoute('depot');
    }
}
