<?php

namespace App\Controller;

use App\Repository\FlightRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(FlightRepository $repo)
    {
        $flights = $repo->findAll();
        #dump($flights);
        return $this->render('main/index.html.twig', [
            'flights' => $flights,
        ]);
    }
}
