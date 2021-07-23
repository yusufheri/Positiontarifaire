<?php

namespace App\Controller\Dashboard;

use App\Entity\Colonne;
use App\Form\ColonneType;
use App\Repository\ColonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/colonne")
 */
class ColonneController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_colonne_index", methods={"GET"})
     */
    public function index(ColonneRepository $colonneRepository): Response
    {
        return $this->render('dashboard/colonne/index.html.twig', [
            'colonnes' => $colonneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dashboard_colonne_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $colonne = new Colonne();
        $form = $this->createForm(ColonneType::class, $colonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($colonne);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_colonne_index');
        }

        return $this->render('dashboard/colonne/new.html.twig', [
            'colonne' => $colonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_colonne_show", methods={"GET"})
     */
    public function show(Colonne $colonne): Response
    {
        return $this->render('dashboard/colonne/show.html.twig', [
            'colonne' => $colonne,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_colonne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Colonne $colonne): Response
    {
        $form = $this->createForm(ColonneType::class, $colonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard_colonne_index');
        }

        return $this->render('dashboard/colonne/edit.html.twig', [
            'colonne' => $colonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dashboard_colonne_delete", methods={"POST"})
     */
    public function delete(Request $request, Colonne $colonne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colonne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($colonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_colonne_index');
    }
}
