<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;

/**
* @Route("/programs", name="program_")
*/
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"},name="show")
     */
    public function show(int $id) : Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $program->getSeason();
    return $this->render('program/showProgram.html.twig', [
        'program' => $program, 'seasons' => $seasons
    ]);
    }

    /**
     * @route ("/{programId}/seasons/{seasonId}", requirements={"programId"="\d+", "seasonId"="\d+"}, name = "season_show")
     */
    public function showSeason(int $programId, int $seasonId): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $programId]);

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findOneBy(['id' => $seasonId]);

        $episodes = $season->getEpisodes();

        return $this->render('program/showSeason.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodes
        ]);
    }
}