<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\NameSearchType;
use App\Repository\DepotRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie", name="category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(DepotRepository $depotRepo,CategoryRepository $categoryRepo,Request $request): Response
    {
        $depots=$depotRepo->findAll();
        $categorys= $categoryRepo->findBy(array(), array('id' => 'DESC'));
        $formName = $this->createForm(NameSearchType::class);
        $formName-> handleRequest($request);
        if($formName->isSubmitted() && $formName->isValid())
        {
            $name = $formName->get('word')->getData();
            $manager=$this->getDoctrine()->getConnection();
            $sql = '
            SELECT * FROM category c
            WHERE c.categoryname LIKE \'%'.$name.'%\''.' ORDER BY c.id DESC';
            $result=$manager->prepare($sql);
            $categorys=$result->executeQuery();

        }
        return $this->render('category/index.html.twig', [
            'categorys'=>$categorys,
            'depots'=>count($depots),
            'formName'=> $formName->createView(),
        ]);
    }

    /**
     * permet d'ajouter une catégorie 
     * @Route("/ajouter-categorie", name="addCategory")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function addCategory(Request $request)
    {
        $addCategory = new Category();
        $addCatForm = $this->createForm(CategoryType::class,$addCategory);
        $addCatForm-> handleRequest($request);
        if($addCatForm->isSubmitted() && $addCatForm->isValid() && empty($addCatForm->get('description')->getData()))
        {  
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($addCategory); 
            $manager->flush();
            return $this-> redirectToRoute('category');  
        }
        
        return $this->render('category/addCategory.html.twig', [
            'addCatForm'=> $addCatForm->createView(),
        ]);
    }

    /**
     * permet de modifier une catégorie 
     * @Route("/modifier-categorie/{slug}", name="editCategory")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function editCategory($slug,CategoryRepository $categoryRepo,Request $request)
    {
        $editCategory = $categoryRepo->findOneBySlug($slug);
        $editCatForm = $this->createForm(CategoryType::class,$editCategory);
        $editCatForm-> handleRequest($request);
        if($editCatForm->isSubmitted() && $editCatForm->isValid() && empty($editCatForm->get('description')->getData()))
        {  
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($editCategory); 
            $manager->flush();
            return $this-> redirectToRoute('category');  
        }
        
        return $this->render('category/editCategory.html.twig', [
            'editCatForm'=> $editCatForm->createView(),
        ]);
    }

    /**
     * permet de supprimer une catégorie
     * @Route("/supprimer-categorie/{slug} ", name="removeCategory")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function removeDepot($slug,CategoryRepository $categoryRepo)
    {   
        $removeCategory = $categoryRepo->findOneBySlug($slug);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($removeCategory); 
        $manager->flush();
        return $this-> redirectToRoute('category');
    }
}
