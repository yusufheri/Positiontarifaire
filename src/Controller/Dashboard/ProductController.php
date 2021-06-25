<?php

namespace App\Controller\Dashboard;

use App\Entity\Import;
use App\Entity\Product;
use App\Form\EditProductType;
use App\Form\ImportType;
use App\Form\ProductType;
use Psr\Log\LoggerInterface;
use App\Repository\ProductRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
    
    private $noFile = '<div class="alert alert-dismissible alert-danger"> 
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4 class="alert-heading">Attention !</h4>
                        <p class="mb-0"> Prière de sélectionner un fichier (CSV, XLS, XLSX) </p>                   
                    </div>';
    private $message01 = '<div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4 class="alert-heading">Félicitations !</h4>
                            <p class="mb-0">
                                Le fichier a été importé avec succès :)
                            </p>                 
                        </div>';
    private $message02 = '<div class="alert alert-dismissible alert-info"> 
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4 class="alert-heading">Attention !</h4>
                            <p class="mb-0">
                                Seules les extensions suivantes sont prises en charge (CSV, XLS, XLSX)
                            </p>                   
                        </div>';
    private $message03 = '<div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4 class="alert-heading">Félicitations !</h4>
                            <p class="mb-0">
                                Le fichier a été importé avec succès :)
                            </p>                 
                        </div>';
    private $message04 = '<div class="alert alert-dismissible alert-info"> 0
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4 class="alert-heading">Attention !</h4>
                            <p class="mb-0">
                                Seules les extensions suivantes sont prises en charge (CSV, XLS, XLSX)
                            </p>                   
                        </div>';
    
    private $manager;

    /**
     * Constructeur de la classe
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="product")
     */
    public function index(ProductRepository $productRepository): Response
    {
        if (isset($_POST["btnSubmit"])) {

            $filename = $_FILES["sentfile"]["tmp_name"];

            if ($_FILES["sentfile"]["size"] > 0)
            {
                $row = 0;
                if(($file = fopen($filename, "r")) !== FALSE) {
                    while (($item = fgetcsv($file, 10000, ";")) !== FALSE) {
                        //$num = count($item);
                        $k = 1;
                        if ($row > 0) {  
                                $product = new Product();
                                $product->setPosition($item[1 + $k])
                                        ->setLibelle($item[2 + $k])
                                        ->setDebut($item[3 + $k])
                                        ->setDdi($item[4 + $k])
                                        ->setDcl($item[5 + $k])
                                        ->setDci($item[6 + $k])
                                        ->setTva(!empty($item[7 + $k])?$item[7 + $k]." %":$item[7 + $k])
                                        ->setUnite($item[8 + $k])
                                        ->setCreatedAt(new \Datetime())
                                        ->setUser($this->getUser())
                                        ;

                                $this->manager->persist($product);
                        }
                        $row++;
                        //unset($_FILES);
                    }
                }

                $this->manager->flush();
            }
        }
        

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
            $dci = $product["dci"];
            $dcl = $product["dcl"];
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
                        ->setDci($dci)
                        ->setDcl($dcl)
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
            $dci = $product_form["dci"];
            $dcl = $product_form["dcl"];
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
                $product->setDci($dci);
                $product->setDcl($dcl);
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

    /**
     * @Route("/product/import", name="import")
     */
    public function importFile(Request $request)
    {
        
        $import = new Import();

        //return  new Response(json_encode($data));

        $form = $this->createForm(ImportType::class, $import);
        $form->handleRequest($request);

        if ($form->isSubmitted()) 
        {
            if ($form->isValid())
            {
                $data = $request->files->get('import');
                $file = $data['excelFile']; 

                if(is_null($file)) return new Response($this->noFile);                

                $fileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension(); 
                $inputFileName = $file->getRealPath();
                $size = $file->getSize(); 

                if( $fileName!= '') {
                    $allowed_extension = array('csv', 'xls', 'xlsx');

                    if(in_array($fileExtension, $allowed_extension))
                    {
                        $file_Type = IOFactory::identify($inputFileName);
                        $reader = IOFactory::createReader($file_Type);
                        $spreadsheet = $reader->load($inputFileName);
                        $data = $spreadsheet->getActiveSheet()->toArray();

                        $import->setCreatedAt(new \Datetime());
                        $import->setFilesize($size);
                        $import->setFilename($fileName);

                        $this->manager->persist($import);

                        $counter = 0;
                        foreach($data as $row) {
                            if ($counter > 3) {
                                $product = new Product();

                            }
                            $counter ++;
                        }

                        $message = $this->message03;
                    } else {
                        $message = $this->message04;
                    }
                } else {
                    $message = $this->noFile;
                }
                $this->manager->flush();
            } else {
                $errors = "";
                foreach ($form->getErrors(true, true) as $error) { $errors = $error->getMessage()."<br>";}            
                $message = '
                    <div class="alert alert-dismissible alert-warning"> 
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4 class="alert-heading">Attention !</h4><p class="mb-0">'.$errors.'</p>                   
                    </div>';
            }
            return new Response($message);
        }

        return $this->render('dashboard/product/excel.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
