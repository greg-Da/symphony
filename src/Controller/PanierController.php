<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;
use App\Form\PanierType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}")
 */

class PanierController extends AbstractController
{
    /**
    * @Route("/panier", name="panier")
    */
    public function index(Request $request = null)
    {
        
        $pdo = $this->getDoctrine()->getManager();
        
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        // Analyse la requete http
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pdo->persist($panier);  // prepare
            $pdo->flush();            // execute
        }
        
        $paniers = $pdo->getRepository(Panier::class)->findAll();
        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
            'form_ajout' => $form->createView()
            
            ]);
        }
        /**
      * @Route("/panier/delete/{id}", name="delete_panier")
      */
      public function delete(Panier $panier = null)
      {
        if ($panier != null) {  // Si l'on trouve un panier, alors on le supprime
          $pdo = $this->getDoctrine()->getManager(); // Connexion à la BDD
          $pdo->remove($panier);
          $pdo->flush();
        }
        return $this->redirectToRoute('panier');
      }
    }
