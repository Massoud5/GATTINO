<?php

namespace App\Controller;

use DateTime;
use App\Entity\Service;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Form\ServiceFormType;
use App\Form\CategorieFormType;
use App\Repository\ServiceRepository;
use App\Repository\CommandeRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{

    //--------------------COMMANDES-----------------------------------------------------------------------------------------------
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandesNonRecupere = $commandeRepository->findByCommandesNonRecupere();
        $commandesRecupere = $commandeRepository->findByCommandesRecupere();
        return $this->render('administration/index.html.twig', [
            'commandesNonRecupere' => $commandesNonRecupere,
            'commandesRecupere' => $commandesRecupere,
        ]);
    }

    #[Route('/admin/changer-statut-commande/{id}', name: 'changer_statut_commande')]
    #[IsGranted('ROLE_ADMIN')]
    public function changerStatutCommande(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        date_default_timezone_set('Europe/Paris');
        $retrivedDateTime = (new DateTime())->format('Y-m-d H:i:s');

        if($commande->isIsRetrieved()){
            $commande ->setIsRetrieved(false);
            $commande ->setRetrievedMoment('not retrieved yet');
        }else{
            $commande ->setIsRetrieved(true);
            $commande ->setRetrievedMoment($retrivedDateTime);
        }
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    //---------------------SERVICES-----------------------------------------------------------------------------------------------------------------------

    #[Route('/admin/service/ajouter', name: 'admin_services_ajouter')]
    #[Route('/admin/service/modifier/{id}', name: 'admin_service_modify')]
    public function adminServiceModifierAjouter(Request $request, EntityManagerInterface $entityManager, Service $service = null, SluggerInterface $slugger): Response
    {
        // le cas d'ajout
        if(!$service) {
            $service = new Service();
        }

        $form = $this->createForm(ServiceFormType::class, $service);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $serviceFormData = $form->getData();
            
            // la partie upload d'image dans application et enrigistrer son path dans db
            $brochureFile = $form->get('image')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) { // si une image est téléchargé

                // en cas d'édite, supprimer l'ancienne image du dossier d'application
                if($request->attributes->get('id')){
                    $filename = $service->getImgService();
                    $filesystem = new Filesystem();
                    $filesystem->remove($filename);
                }

                // renommer le fichier pour éviter le problème des noms similaires----------
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                //---------------------------------------------------------------------------
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('service_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $service->setImgService('dist/img/photos/services/'.$newFilename);
            }
            
            $entityManager->persist($serviceFormData);
            $entityManager->flush();
            return $this->redirectToRoute('admin_services');
        }

        return $this->render('administration/service-admin-form.html.twig', [
            'formAddService' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/service/supprimer/{id}', name: 'admin_service_delete')]
    public function adminServiceSupprimer(EntityManagerInterface $entityManager, Service $service, ServiceRepository $serviceRepo): Response
    {
        // effacer l'image dans appilcation
        $filename = $service->getImgService();
        $filesystem = new Filesystem();
        $filesystem->remove($filename);

        // effacer dans db
        $serviceRepo->remove($service);
        $entityManager->flush();
        return $this->redirectToRoute('admin_services');
    }
    
    #[Route('/admin/services', name: 'admin_services')]
    public function adminServicesShow(EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Service::class)->findAll();
        return $this->render('administration/services-admin.html.twig', [
            'services' => $services,
        ]);
    }

    //---------------------CATEGORIES-----------------------------------------------------------------------------------------------------------------------------

    #[Route('/admin/categorie/ajouter', name: 'admin_categorie_ajouter')]
    #[Route('/admin/categorie/modifier/{id}', name: 'admin_categorie_modify')]
    public function adminCategorieModifierAjouter(Request $request, EntityManagerInterface $entityManager, Categorie $categorie = null, SluggerInterface $slugger): Response
    {
        // le cas d'ajout
        if(!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $categorieFormData = $form->getData();
            
            // la partie upload d'image dans application et enrigistrer son path dans db
            $brochureFile = $form->get('image')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) { // si une image est téléchargé
                
                // en cas d'édite, supprimer l'ancienne image du dossier d'application
                if($request->attributes->get('id')){
                    $filename = $categorie->getCatImg();
                    $filesystem = new Filesystem();
                    $filesystem->remove($filename);
                }

                // renommer le fichier pour éviter le problème des noms similaires----------
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                //---------------------------------------------------------------------------
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('categorie_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $categorie->setCatImg('dist/img/photos/categories/'.$newFilename);

            }
            
            $entityManager->persist($categorieFormData);
            $entityManager->flush();
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('administration/categorie-admin-form.html.twig', [
            'formAddCategorie' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/categorie/supprimer/{id}', name: 'admin_categorie_delete')]
    public function adminCategorieSupprimer(EntityManagerInterface $entityManager, Categorie $categorie, CategorieRepository $categorieRepo): Response
    {
        // effacer l'image dans appilcation
        $filename = $categorie->getCatImg();
        $filesystem = new Filesystem();
        $filesystem->remove($filename);

        // effacer dans db
        $categorieRepo->remove($categorie);
        $entityManager->flush();
        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/admin/categories', name: 'admin_categories')]
    public function adminCategoriesShow(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('administration/categories-admin.html.twig', [
            'categories' => $categories,
        ]);
    }
}
