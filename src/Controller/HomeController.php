<?php
namespace App\Controller;

use Twig\Environment;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * @var Environment 
     */
    private $twig;

    public function _construct(Environment $twig)
    {
        $this->twig=$twig;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(PropertyRepository $repository):Response
    {
        $properties=$repository->findLatest();
        
        return $this->render('pages/home.html.twig', [
            'properties' =>$properties
        ]);

        
    }
}