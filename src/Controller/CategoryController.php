<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private $categoryRepository;
    private $entityManager;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $categorys = $this->categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categorys,
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(){
        return $this->render('list/index.html#category');
    }
    /**
     * @Route("/category-create", name="category-create")
     */
    public function newAction(Request $request)
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', "La categorie a été crée");

            return $this->redirectToRoute('list');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
