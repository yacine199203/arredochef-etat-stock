<?php

namespace App\Controller;

use App\Entity\Inventaire;
use App\Entity\InventaireList;
use App\Form\InventaireType;
use App\Form\NameSearchType;
use App\Repository\InventaireListRepository;
use App\Repository\ProductRepository;
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



    /**
     * permet de voir la liste d'un inventaire
     * @Route("/inventaire/{id}", name="inInventaire")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function InInventaire($id,InventaireRepository $inventaireRepo)
    {
        $inventaire= $inventaireRepo->findOneById($id);
        return $this->render('inventaire/inventaire.html.twig', [
            'inventaire' => $inventaire,
        ]);
    }

    /**
     * permet d'acceder au scanner
     * @Route("/inventaire/{id}/scanner", name="scan")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function scanner($id)
    {
        
        return $this->render('mouvement_stock/scanner.html.twig', [
            
            'mouvement' => 'Ajouter un produit',
            'route' => 'inInventaire',
            'id'=> $id
        ]);
    }

    /**
     * permet d'ajouter dans un inventaire
     * @Route("/inventaire/{id}/scanner/{ref}/{qte}", name="addInInventaire")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function addInInventaire(Inventaire $id,$ref,$qte,ProductRepository $productRepo,InventaireListRepository $invListRepo)
    {
        $produit= $productRepo->findOneByRef($ref);
        
        $invlist = $invListRepo->findAll();
        $result=0;
        foreach($invlist as $inv){
            if($inv->getProduct()->getId() == $produit->getId()){
                $result=$produit->getId();
            }
        }
        if($result == $produit->getId()){
            $ligne= $invListRepo->findOneByProduct($result);
            $ligne->setComptage($ligne->getComptage()+$qte);
        }else{
            $ligne= new InventaireList();
            $ligne->setInventaire($id);
            $ligne->setProduct($produit);
            $ligne->setComptage($qte);
        }
        $manager=$this->getDoctrine()->getManager();
        $manager->persist($ligne); 
        $manager->flush();
        return $this->json(['code'=> 200, 'message'=>'ok','data'=>true],200);
    }

    /**
     * @Route("/inventaire/{id}/supprimer-produit/{ide} ", name="removeProductInList")
     * @return Response
     */
    public function removeProduct($id,$ide,InventaireListRepository $invListRepo)
    {   
        $removeProduct = $invListRepo->findOneById($ide);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeProduct); 
        $manager->flush();
        return $this->redirectToRoute('inInventaire', [
            'id' => $id
        ]);
    }
}
