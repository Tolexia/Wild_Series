<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;

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
    
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img')->getData();
            if ($imgFile) {
                $imgFileName = $fileUploader->upload($imgFile);
                $category->setImg($imgFileName);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();
            }
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
    * @Route("/{name}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
    */
    public function show(Category $category):Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC']
            );

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            "category" => $category]);
    }
}
