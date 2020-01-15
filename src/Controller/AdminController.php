<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function list(FlightRepository $repo)
    {
        $flights = $repo->findAll();
        dump($flights);
        return $this->render('admin/list.html.twig', [
            'flights' => $flights
            
        ]);
    }
    /**
     * @Route("/admin/new", name="admin_new")
     */
    public function new(Request $request) 
    {
        // instance de l'entité Flight à alimenter avec le formulaire
         $flight = new Flight();
         // On crée le formulaire basé sur la classe FlightType qui configure les champs
         $form = $this->createForm(FlightType::class, $flight);
         // Pour géré la soumission
         $form ->handleRequest($request);
         /* Dans le controller on verifie que les villes de depart et d'arrivé sont différentes
            Ci-dessous j'utilise un message d'erreur mais ce test est Opérationnel dans l'entité Flight
        */
        //  if($flight->getDeparture() == $flight->getArrival()){
        //     $error = new FormError("Le depart et l'arrivée doivent être différents");
        //     $form->get("arrival")->addError($error);
        //  }
         // on génere le numero de vol a travers la fonction privée
         $flight->setNumber($this->getFlightNumber());

         // On enregistre les données du formulaire
         if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            // l'equivalent de Git add .
            $manager->persist($flight);
            // l'equivalent de Git commit
            $manager->flush();
            // redirection à la page d'accueil de l'admin
            return $this->redirectToRoute('admin_home');
         }
         return $this->render('admin/create.html.twig', [
            'myform' => $form->createView()
            
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="admin_edit")
     */
    public function edit(Request $request, $id) 
    {
        // Je crecupère a partir de la BDD le vol relatif à l'id -> $id
        $flight = $this->getDoctrine()->getRepository(Flight::class)->find($id);
        // Je crée un formulaire alimenter par l'objet Flight
        $form = $this->createForm(FlightType::class, $flight);

        $form ->handleRequest($request);

         // On enregistre les données du formulaire
         if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            // l'equivalent de Git add .
            $manager->persist($flight);
            // l'equivalent de Git commit
            $manager->flush();
            // redirection à la page d'accueil de l'admin
            return $this->redirectToRoute('admin_home');
         }
        return $this->render('admin/update.html.twig', [
            'numeroVol' => $flight->getNumber(),
            'flight' => $flight,
            'myform' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(Flight $flight) 
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($flight);
        $manager->flush();
        return $this->redirectToRoute('admin_home');
    }

    /**
     * get a random flight number
     *
     * @return string
     */
    private function getFlightNumber():string 
    {
        // Tableau de lettre en majuscule
        $lettres = range('A', 'Z');
        // je mélange
        shuffle($lettres);
        // j'extrait le premier item du tableau
        $lettre = array_shift($lettres);
        // je recommence pour la seconde lettre
        shuffle($lettres);
        // j'extrait la seconde lettre
        $lettre .= array_shift($lettres);
        // un nombre sur 4 digit au hasard
        $nombre = mt_rand(1000, 9999);
        
        return $lettre.$nombre;

    }
}
