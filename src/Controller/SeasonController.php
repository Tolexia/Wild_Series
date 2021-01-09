<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;

/**
 * @Route("/season")
 */
class SeasonController extends AbstractController
{
    /**
     * @Route("/", name="season_index", methods={"GET"})
     */
    public function index(SeasonRepository $seasonRepository): Response
    {
        return $this->render('season/index.html.twig', [
            'seasons' => $seasonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="season_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($season);
            $entityManager->flush();

            $program = $form->get('program')->getData();
            $programId = $program->getId();
            $seasonId = $season->getId();

            return $this->redirectToRoute('program_season_show', ['program' => $programId, 'season' => $seasonId ]);
        }

        return $this->render('season/new.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="season_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $program = $form->get('program')->getData();
            $programId = $program->getId();
            $seasonId = $season->getId();

            return $this->redirectToRoute('program_season_show', ['program' => $programId, 'season' => $seasonId ]);
        }

        return $this->render('season/edit.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="season_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();
        }
        $program = $season->getProgram();
        $programId = $program->getId();
        $seasons = $program->getSeason();
        return $this->redirectToRoute('program_show', [
            'id' => $programId, 
            'program' => $program,
            'seasons' => $seasons
        ]);
    }
}
