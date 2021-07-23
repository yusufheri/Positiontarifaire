<?php

namespace App\Controller\Dashboard;

use App\Entity\DetailProduct;
use App\Form\DetailProductType;
use App\Repository\DetailProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/detail-product")
 */
class DetailProductController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_detail_product_index", methods={"GET"})
     */
    public function index(DetailProductRepository $detailProductRepository): Response
    {
        return $this->render('dashboard/detail_product/index.html.twig', [
            'detail_products' => $detailProductRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dashboard_detail_product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $detailProduct = new DetailProduct();
        $form = $this->createForm(DetailProductType::class, $detailProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailProduct->setColName($detailProduct->getColonne()->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($detailProduct);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_detail_product_index');
        }

        return $this->render('dashboard/detail_product/new.html.twig', [
            'detail_product' => $detailProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_detail_product_show", methods={"GET"})
     */
    public function show(DetailProduct $detailProduct): Response
    {
        return $this->render('dashboard/detail_product/show.html.twig', [
            'detail_product' => $detailProduct,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_detail_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DetailProduct $detailProduct): Response
    {
        $form = $this->createForm(DetailProductType::class, $detailProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailProduct->setColName($detailProduct->getColonne()->getName());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard_detail_product_index');
        }

        return $this->render('dashboard/detail_product/edit.html.twig', [
            'detail_product' => $detailProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_detail_product_delete", methods={"POST"})
     */
    public function delete(Request $request, DetailProduct $detailProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailProduct->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($detailProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_detail_product_index');
    }
}
