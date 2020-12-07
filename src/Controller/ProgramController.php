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
    public function show(Program $program) : Response
    {
       
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '. $program->getId() .' found in program\'s table.'
            );
        }

        $seasons = $program->getSeason();
    return $this->render('program/showProgram.html.twig', [
        'program' => $program, 'seasons' => $seasons
    ]);
    }

    /**
     * @route ("/{program}/seasons/{season}", requirements={"programId"="\d+", "seasonId"="\d+"}, name = "season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {

        $episodes = $season->getEpisodes();

        return $this->render('program/showSeason.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodes
        ]);
    }
}