<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    private $cardRepository;
    private $categoryRepository;
    private $userRepository;
    private $entityManager;

    public function __construct(CardRepository $cardRepository,
                                CategoryRepository $categoryRepository,
                                UserRepository $userRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cardRepository = $cardRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        $cards = $this->cardRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        $userList = $this->userRepository->findAll();

        return $this->render('list/index.html.twig', [
            'controller_name' => 'ListController',
            'cardList' => $cards,
            'categories' => $categories,
            'user_list' => $userList,
        ]);
    }

    public function newAction(Request $request){}
}
