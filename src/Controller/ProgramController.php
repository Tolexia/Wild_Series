<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/", name="program_")
*/
class ProgramController extends AbstractController
{
    /**
     * @Route("/programs/", name="index")
     */
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Séries',
         ]);
    }

    /**
     * @Route("/programs/{id}", methods={"GET"}, requirements={"id"="\d+"},name="program_show")
     */
    public function show($id) : Response
    {
        return $this->render('program/show.html.twig', ['id' => $id]);
    }
}