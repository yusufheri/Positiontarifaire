<?php

namespace App\Controller\Dashboard;

use App\Entity\Product;
use App\Form\EditProductType;
use App\Form\ProductType;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dashboard", name="dashboard_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('dashboard/product/index.html.twig', [
            'product' => $productRepository->findBy([],["libelle" => "ASC"]),
        ]);
    }

    /**
     * @Route("/product/create_form", name="_create_form")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        return $this->render("dashboard/product/partials/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/product/new", name="_create")
     */
    public function new(Request $request, ValidatorInterface $validator, LoggerInterface $logger, EntityManagerInterface $manager): Response
    {
        
        if($request->isXmlHttpRequest()) {
            
            
            $product = $request->request->get('product');
            $token =$product["_token"];
            $position = $product["position"];
            $libelle = $product["libelle"];
            $tva = $product["tva"];
            $ddi = $product["ddi"];
            $unite = $product["unite"];
            $date = $product["debut"];

            $input = [
                'position' => $position,
                'libelle' => $libelle,
                'date' => $date
            ];

            $constraints = new Assert\Collection([
                'date' => [new Assert\NotBlank(null,"La date ne peut pas etre nulle"), new Assert\Date],
                'libelle' => [new Assert\NotBlank(null,"Le libelle ne peut pas etre vide")],
                'position' => [new Assert\NotBlank(null,"La position tarifaire ne peut pas etre vide")],
            ]);
    
            $violations = $validator->validate($input, $constraints);
            
            if (count($violations) > 0) {
    
                $accessor = PropertyAccess::createPropertyAccessor();
    
                $errorMessages = [];
    
                foreach ($violations as $violation) {
    
                    $accessor->setValue($errorMessages,
                        $violation->getPropertyPath(),
                        $violation->getMessage());
                }
    
                return $this->render('dashboard/product/partials/violations.html.twig',
                    ['errorMessages' => $errorMessages]);
            } else {
                
                $product = new Product();
                $product->setCreatedAt(new \DateTime())
                        ->setPosition($position)
                        ->setLibelle($libelle)
                        ->setTva($tva)
                        ->setDdi($ddi)
                        ->setUnite($unite)
                        ->setDebut($date)
                        ->setUser($this->getUser());

                
                $manager->persist($product);
                $manager->flush();

                return new Response("La nouvelle position tarifaire a été créée avec succès", Response::HTTP_OK,
                    ['content-type' => 'text/plain']);
            }

        } else {
            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
            ['content-type' => 'text/plain']);
        }        
        
    }

    /**
     * @Route("/product/{id}/delete", name="_delete")
     */
    public function delete(Product $product,Request $request, ValidatorInterface $validator, LoggerInterface $logger, EntityManagerInterface $manager): Response
    {
        
        if($request->isXmlHttpRequest()) {

                $product->setDeletedAt(new \DateTime());
                $manager->persist($product);
                $manager->flush();

                return new Response("La position tarifaire a été supprimée avec succès", Response::HTTP_OK,
                    ['content-type' => 'text/plain']);

        } else {
            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
            ['content-type' => 'text/plain']);
        }        
        
    }

    /**
     * @Route("/product/{id}/edit_form", name="_edit_form")
     */
    public function editForm(Product $product, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(EditProductType::class, $product);


        if($request->isXmlHttpRequest()) 
        {
            return $this->render("dashboard/product/partials/edit.html.twig", [
                "form" => $form->createView(),
                'product' => $product
            ]);
        } else {
            return new Response("denied access !!!");
        }
        
    }

    /**
     * @Route("/product/{id}/edit", name="_edit_product")
     */
    public function edit(Product $product,Request $request, ValidatorInterface $validator, LoggerInterface $logger, EntityManagerInterface $manager): Response
    {
        
        if($request->isXmlHttpRequest()) {
            
            $product_form = $request->request->get('edit_product');
            
            $token =$product_form["_token"];
            $position = $product_form["position"];
            $libelle = $product_form["libelle"];
            $tva = $product_form["tva"];
            $ddi = $product_form["ddi"];
            $unite = $product_form["unite"];
            //$date = $product["debut"];

            $input = [
                'position' => $position,
                'libelle' => $libelle,
                //'date' => $date
            ];

            $constraints = new Assert\Collection([
                'libelle' => [new Assert\NotBlank(null,"Le libelle ne peut pas etre vide")],
                'position' => [new Assert\NotBlank(null,"La position tarifaire ne peut pas etre vide")],
            ]);
    
            $violations = $validator->validate($input, $constraints);

            if (count($violations) > 0) {
                
                $accessor = PropertyAccess::createPropertyAccessor();
    
                $errorMessages = [];
    
                foreach ($violations as $violation) {
    
                    $accessor->setValue($errorMessages,
                        $violation->getPropertyPath(),
                        $violation->getMessage());
                }
    
                return $this->render('dashboard/product/partials/violations.html.twig',
                    ['errorMessages' => $errorMessages]);
            } else {
                $product->setPosition($position);
                $product->setLibelle($libelle);
                $product->setTva($tva);
                $product->setDdi($ddi);
                $product->setUnite($unite);
                        //->setDebut($date);

                
                $manager->persist($product);
                $manager->flush();

                return new Response("La position tarifaire a été modifiée avec succès", Response::HTTP_OK,
                    ['content-type' => 'text/plain']);
            }
            

        } else {
            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
            ['content-type' => 'text/plain']);
        }        
        
    }



    /**
     * Retourne la liste des comptes créés
     * @Route("/product/api", name="api")
     * @return json
     */
    public function datatable()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Product::class);
        return  $this->json($repo->findBy(["deletedAt" => null], ["createdAt" => "DESC",]), 200, [], ['groups' => 'product:read']);
    }


}
