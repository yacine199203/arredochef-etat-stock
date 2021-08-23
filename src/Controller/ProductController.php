<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\RefSearchType;
use App\Form\NameSearchType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/produit", name="product")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ProductRepository $productRepo,CategoryRepository $categoryRepo,Request $request): Response
    {
        $categorys=$categoryRepo->findAll();
        $products= $productRepo->findBy(array(), array('id' => 'DESC'));
        $formName = $this->createForm(NameSearchType::class);
        $formName-> handleRequest($request);
        $form = $this->createForm(RefSearchType::class);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $name = $form->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM product p
            WHERE p.ref LIKE \'%'.$name.'%\''.' ORDER BY p.id DESC';
            $result=$manager->prepare($sql);
            $products=$result->executeQuery();
        }
        if($formName->isSubmitted() && $formName->isValid())
        {
            $name = $formName->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM product p
            WHERE p.libelle OR p.color LIKE \'%'.$name.'%\''.' ORDER BY p.id DESC';
            $result=$manager->prepare($sql);
            $products=$result->executeQuery();

        }
        return $this->render('product/index.html.twig', [
            'products'=>$products,
            'categorys'=>count($categorys),
            'formName'=> $formName->createView(),
            'form'=> $form->createView(),
        ]);
    }

    /**
     * permet d'ajouter un produit 
     * @Route("/ajouter-produit", name="addProduct")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function addCategory(Request $request)
    {
        $addProduct = new Product();
        $addProdForm = $this->createForm(ProductType::class,$addProduct);
        $addProdForm-> handleRequest($request);
        if($addProdForm->isSubmitted() && $addProdForm->isValid() && empty($addProdForm->get('description')->getData()))
        {  
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($addProduct); 
            $manager->flush();
            return $this-> redirectToRoute('product');  
        }
        
        return $this->render('product/addProduct.html.twig', [
            'addProdForm'=> $addProdForm->createView(),
        ]);
    }

    /**
     * permet de modifier un produit 
     * @Route("/modifier-produit/{slug}", name="editProduct")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function editCategory($slug,ProductRepository $productRepo,Request $request)
    {
        $editProduct = $productRepo->findOneBySlug($slug);
        $editProdForm = $this->createForm(ProductType::class,$editProduct);
        $editProdForm-> handleRequest($request);
        if($editProdForm->isSubmitted() && $editProdForm->isValid() && empty($editProdForm->get('description')->getData()))
        {  
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($editProduct); 
            $manager->flush();
            return $this-> redirectToRoute('product');  
        }
        
        return $this->render('product/editProduct.html.twig', [
            'editProdForm'=> $editProdForm->createView(),
        ]);
    }

    /**
     * permet de supprimer un produit
     * @Route("/supprimer-produit/{id} ", name="removeProduct")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function removeDepot($id,ProductRepository $productRepo)
    {   
        $removeProduct = $productRepo->findOneById($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeProduct); 
        $manager->flush();
        return $this-> redirectToRoute('product');
    }

}
