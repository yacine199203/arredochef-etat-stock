<?php

namespace App\Controller;

use App\Entity\Inventaire;
use App\Form\InventaireType;
use App\Form\NameSearchType;
use App\Repository\InventaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InventaireController extends AbstractController
{
    /**
     * @Route("/inventaire", name="inventaire")
     * @IsGranted("ROLE_USER")
     */
    public function index(InventaireRepository $inventairetRepo,Request $request): Response
    {
        $inventaires= $inventairetRepo->findBy(array(), array('id' => 'DESC'));
        $formName = $this->createForm(NameSearchType::class);
        $formName-> handleRequest($request);
        if($formName->isSubmitted() && $formName->isValid())
        {
            $name = $formName->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM inventaire i
            WHERE i.libelle LIKE \'%'.$name.'%\''.' ORDER BY i.id DESC';
            $result=$manager->prepare($sql);
            $inventaires=$result->executeQuery();

        }
        return $this->render('inventaire/index.html.twig', [
            'inventaires'=>$inventaires,
            'formName'=> $formName->createView(),
        ]);
    }

    /**
     * permet d'ajouter un dépôt
     * @Route("/inventaire/ajouter-inventaire", name="addInventaire")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function addDepot(Request $request)
    {
        $addInventaire = new Inventaire();
        $addInvForm = $this->createForm(InventaireType::class,$addInventaire);
        $addInvForm-> handleRequest($request);
        if($addInvForm->isSubmitted() && $addInvForm->isValid() && empty($addInvForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($addInventaire); 
            $addInventaire->setTraitepar($this->getUser());
            $manager->flush();
            return $this-> redirectToRoute('inventaire');
        }
        return $this->render('inventaire/addInventaire.html.twig', [
            'addInvForm'=> $addInvForm->createView(),
        ]);
    }

    /**
     * permet de modifier un Inventaire
     * @Route("/inventiare/modifier-inventaire/{id}", name="editInventaire")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function editDepot($id,InventaireRepository $inventaireRepo,Request $request)
    {
        $editInventaire = $inventaireRepo->findOneById($id);
        $editInvForm = $this->createForm(InventaireType::class,$editInventaire);
        $editInvForm-> handleRequest($request);
        if($editInvForm->isSubmitted() && $editInvForm->isValid() && empty($editInvForm->get('description')->getData()))
        {
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($editInventaire); 
            $manager->flush();
            return $this-> redirectToRoute('inventaire');
        }
        return $this->render('inventaire/editInventaire.html.twig', [
            'editInvForm'=> $editInvForm->createView(),
        ]);
    }

    /**
     * permet de supprimer un inventaire
     * @Route("/inventaire/supprimer-inventaire/{id} ", name="removeInventaire")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function removeDepot($id,InventaireRepository $inventaireRepo)
    {   
        $removeInventaire = $inventaireRepo->findOneById($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeInventaire); 
        $manager->flush();
        return $this-> redirectToRoute('inventaire');
    }
}
