<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @Route("/delete-bis/{id}", name="category_delete_bis")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteBis(string $id, EntityManagerInterface $entityManager)
    {
        $category = $this->categoryRepository->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('danger', "La categorie a bien été supprimée");

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/deleteCategory/{id}", name="category_delete")
     * @ParamConverter("Category", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Category $category, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('danger', "La categorie a bien été supprimée");

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/editCategory/{id}", name="category_edit")
     * @ParamConverter("Category", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('warning', "La categorie a bien été modifiée");

            return $this->redirectToRoute('list');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
