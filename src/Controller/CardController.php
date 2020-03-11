<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    private $cardRepository;
    private $entityManager;

    public function __construct(CardRepository $cardRepository, EntityManagerInterface $entityManager)
    {
        $this->cardRepository = $cardRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/card_list", name="card_list")
     */
    public function index()
    {
        $cards = $this->cardRepository->findAll();
        return $this->render('card/index.html.twig', [
            'controller_name' => 'CardController',
            'cardList' => $cards,
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(){
        return $this->render('list/index.html#category');
    }

    /**
     * @Route("/card-create", name="card-create")
     */
    public function newAction(Request $request)
    {

        $card = new Card();

        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $image = $form->get('image')->getData();

            $imageName = 'card-' .uniqid().'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('cards-folder'),
                $imageName
            );
            $card->setImage($imageName);

            $card->setUser($this->getUser());

            $this->entityManager->persist($card);
            $this->entityManager->flush();
            $this->addFlash('success', "La carte a bien été crée");

            return $this->redirectToRoute('list');
        }
        return $this->render('card/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-bis/{id}", name="card_delete_bis")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteBis(string $id, EntityManagerInterface $entityManager)
    {
        $card = $this->cardRepository->find($id);
        $entityManager->remove($card);
        $entityManager->flush();
        $this->addFlash('danger', "La carte a bien été supprimée");

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/deleteCard/{id}", name="card_delete")
     * @ParamConverter("card", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Card $card, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($card);
        $entityManager->flush();

        $this->addFlash('danger', "La carte a bien été supprimée");

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/editCard/{id}", name="card_edit")
     * @ParamConverter("card", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Request $request, Card $card)
    {
        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($card);
            $this->entityManager->flush();
            $this->addFlash('warning', "La carte a bien été modifiée");

            return $this->redirectToRoute('list');
        }
        return $this->render('card/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
