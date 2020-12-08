<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProgramType;

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
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
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
     * @route ("/{program}/seasons/{season}", requirements={"program"="\d+", "season"="\d+"}, name = "season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {

        $episodes = $season->getEpisodes();

        return $this->render('program/showSeason.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodes
        ]);
    }
    /**
     * @route ("/{program}/seasons/{season}/episodes/{episode}", requirements={"program"="\d+", "season"="\d+", "episode"="\d+"}, name = "episode_show" )
     */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/showEpisode.html.twig', [
            'program' => $program, 'season' => $season, 'episode' => $episode
        ]);
    }
}
