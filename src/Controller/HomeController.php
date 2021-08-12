<?php

namespace App\Controller;

use App\Form\NameSearchType;
use App\Repository\DepotRepository;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('home/index.html.twig', [
            'depots'=>$depots,
            'formName'=> $formName->createView(),
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
