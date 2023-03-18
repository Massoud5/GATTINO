<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Service;
use App\Entity\Categorie;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminProduitsController extends AbstractController
{
    // ----------------------------PRODUITS-----------------------------------

    
    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produit/ajouter', name: 'admin_produit_ajouter')]
    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produit/modifier/{id}', name: 'admin_produit_modify')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProduitModifierAjouter(Request $request, EntityManagerInterface $entityManager, Produit $produit = null, SluggerInterface $slugger): Response
    {
        // le cas d'ajout
        if(!$produit) {
            $produit = new Produit();
        }
        
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $produitFormData = $form->getData();
            $produit->setActive(false);
            
            // la partie upload d'image dans application et enrigistrer son path dans db
            $brochureFile = $form->get('image')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) { // si une image est téléchargé
                
                // en cas d'édite, supprimer l'ancienne image du dossier d'application
                if($request->attributes->get('id')){
                    $filename = $produit->getImgProduit();
                    $filesystem = new Filesystem();
                    $filesystem->remove($filename);
                }
                
                // renommer le fichier pour éviter le problème des noms similaires----------
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                //---------------------------------------------------------------------------
                
                try {
                    // ajouter l'image dans dossier d'application via le path donnée dans le fichier services.yaml
                    $brochureFile->move(
                        $this->getParameter('produit_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                
                // enregistrer le path dans db
                $produit->setImgProduit('dist/img/photos/produits/'.$newFilename);
            }
            
            $entityManager->persist($produitFormData);
            $entityManager->flush();
            
            return $this->redirectToRoute('admin_produits_main');
        }
        
        return $this->render('administration/admin-produits/admin-produits-form.html.twig', [
            'formAddProduit' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produit/supprimer/{id}', name: 'admin_produit_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProduitSupprimer(EntityManagerInterface $entityManager, Produit $produit, ProduitRepository $produitRepo): Response
    {
        // effacer l'image dans appilcation
        $filename = $produit->getImgProduit();
        $filesystem = new Filesystem();
        $filesystem->remove($filename);

        // effacer dans db
        $produitRepo->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('admin_produits_main');
    }   

    #[Route('/admin/produit/inverser/{id}', name: 'admin_produit_inverse')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProduitInverser(EntityManagerInterface $entityManager, Produit $produit, ProduitRepository $produitRepo): Response
    {
        if($produit->isActive() == true){
            $produit->setActive(false);
        }else{
            // if($produit->getQuantity() && $produit->getQuantity() > 0){
                $produit->setActive(true);
            // }
        }
        
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('admin_produits_main');
    }   
    
    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produits', name: 'admin_produits_main')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminProduitsShow(ProduitRepository $produitRepo, EntityManagerInterface $entityManager): Response
    {
        $produitsCommandables = $produitRepo->findByProduitsCommandables();
        $produitsNonCommandables = $produitRepo->findByProduitsNonCommandables();

        $services = $entityManager->getRepository(Service::class)->findAll();
        asort($services);
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        asort($categories);

        // if (isset($_POST['submit-prod-quantity'])){
        //     $prodId = filter_input(INPUT_POST,"prod-id", FILTER_VALIDATE_INT);
        //     $prodQuantity = filter_input(INPUT_POST,"prod-quantity", FILTER_VALIDATE_INT);
            
        //     $produit = $produitRepo->findOneById($prodId);
        //     $produit->setQuantity($prodQuantity);

            
        //     $entityManager->persist($produit);
        //     $entityManager->flush();

        //     //désactiver le produit si quantité est 0
        //     if($produit->getQuantity()== 0){
        //         $produit->setActive(false);
        //         $entityManager->persist($produit);
        //         $entityManager->flush();
        //     }
        // }

        return $this->render('administration/admin-produits/admin-produits-main.html.twig', [
            'produitsCommandables' => $produitsCommandables,
            'produitsNonCommandables' => $produitsNonCommandables,
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produits/service/{id}', name: 'admin_produits_service')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminServiceProduitsShow(ProduitRepository $produitRepo, Service $service, EntityManagerInterface $entityManager): Response
    {
        $produitsCommandables = $produitRepo->findByServProdCommandables($service);
        $produitsNonCommandables = $produitRepo->findByServProdNonCommandables($service);
        $services = $entityManager->getRepository(Service::class)->findAll();
        asort($services);
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        asort($categories);
        return $this->render('administration/admin-produits/admin-produits-service.html.twig', [
            'produitsCommandables' => $produitsCommandables,
            'produitsNonCommandables' => $produitsNonCommandables,
            'services' => $services,
            'categories' => $categories,
            'service' => $service,
        ]);
    }

    #[Route('/admin/6w3bocMghDiz4Xvckg6ijiT3kKfqdQl8L3VqNRJQ/produits/categorie/{id}', name: 'admin_produits_categorie')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminCategorieProduitsShow(ProduitRepository $produitRepo, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $produitsCommandables = $produitRepo->findByCatProdCommandables($categorie);
        $produitsNonCommandables = $produitRepo->findByCatProdNonCommandables($categorie);
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        asort($categories);
        $services = $entityManager->getRepository(Service::class)->findAll();
        asort($services);
        return $this->render('administration/admin-produits/admin-produits-categorie.html.twig', [
            'produitsCommandables' => $produitsCommandables,
            'produitsNonCommandables' => $produitsNonCommandables,
            'categories' => $categories,
            'services' => $services,
            'categorie' => $categorie,
        ]);
    }
}
