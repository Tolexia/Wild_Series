<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;

/**
* @Route("/categories", name="category_")
*/
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
         ]);
    }
    
    ///**
    // * @Route("/{categoryName}", name="show")
    // */
    /*public function show(string $categoryName) : Response
    {

        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name', $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program with id : '.$categoryName.' found in category table.'
            );
        }


        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy([
            ['category'=> $category],
            ['id' => 'DESC'],
            3
        ]);
    return $this->render('category/show.html.twig', [
        'categoryName' => $categoryName, 'programs' => $programs
    ]);
    }*/


    /**
    * @Route("/{categoryName}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
    */
    public function show(string $categoryName):Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No '.$categoryName.' has been sent to find a category in category\'s table.');
        }

        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        return $this->render('category/show.html.twig', [
            'categoryName' => $categoryName,
            'programs' => $programs]);
    }
}
