<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\Panier;
use App\Form\PanierType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}")
 */

class ProduitController extends AbstractController
{
  /**
  * @Route("/", name="home")
  */
  public function index(Request $request)
  {
    // Connexion à la BDD
    $pdo = $this->getDoctrine()->getManager();

    
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    // Analyse la requete http
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $fichier = $form->get('pic')->getData();
      if ($fichier) {
        $nomFichier = uniqid() . '.' . $fichier->guessExtension();
        
        try {
          $fichier->move(
            $this->getParameter('upload_dir'),
            $nomFichier
          );
        } catch (FileException $e) {
          return $this->redirectToRoute('home');
        }
        
        $produit->setPic($nomFichier);
      }
      $pdo->persist($produit);  // prepare
      $pdo->flush();            // execute
    }
    
    $produits = $pdo->getRepository(Produit::class)->findAll();
    
    return $this->render('produit/index.html.twig', [
      'produits' => $produits,
      'form_ajout' => $form->createView()
      ]);
    }
    /**
    * @Route("/produit/{id}", name="un_produit")
    */
    public function produit(Produit $produit = null, Request $request)
    {
      $pdo = $this->getDoctrine()->getManager();
        
      $panier = new Panier();
      $form = $this->createForm(PanierType::class, $panier);
      // Analyse la requete http
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        try{
          $pdo->persist($panier);  // prepare
          $pdo->flush();            // execute
          $this->addFlash("success", "Produit ajouté");
      }catch(\Exception $e){
        error_log($e->getMessage());
    }
    
    }
      
      $produits = $pdo->getRepository(Produit::class)->findAll();
      return $this->render('produit/index.html.twig', [
          'produits' => $produits,
          'form_ajout' => $form->createView()
          
          ]);
      }
      
      /**
      * @Route("/produit/delete/{id}", name="delete_produit")
      */
      public function delete(Produit $produit = null)
      {
        if ($produit != null) {  // Si l'on trouve un produit, alors on le supprime
          $pdo = $this->getDoctrine()->getManager(); // Connexion à la BDD
          $pdo->remove($produit);
          $pdo->flush();
        }
        return $this->redirectToRoute('home');
      }
    }
    