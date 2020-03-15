<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\DeckCard;
use App\Form\DeckType;
use App\Repository\CardRepository;
use App\Repository\DeckCardRepository;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeckController
 * @package App\Controller
 * @Route("/deck", name="deck_")
 */
class DeckController extends AbstractController
{
    private $deckRepository;
    private $cardRepository;
    private $deckCardRepository;
    private $manager;

    public function __construct(DeckRepository $deckRepository, EntityManagerInterface $manager, CardRepository $cardRepository,
                                DeckCardRepository $deckCardRepository)
    {
        $this->deckRepository = $deckRepository;
        $this->manager = $manager;
        $this->cardRepository = $cardRepository;
        $this->deckCardRepository = $deckCardRepository;
    }

    /**
     * @Route("/", name="list")
     */
    public function index(Request $request): Response
    {
        $decks = $this->deckRepository->findBy(['user' => $this->getUser()]);

        $deck = new Deck();
        $form = $this->createForm(DeckType::class, $deck)
            ->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deck->setUser($this->getUser());

            $this->manager->persist($deck);
            $this->manager->flush();

            $this->addFlash('success', "the deck has been created");

            return $this->redirectToRoute('deck_list');

        }

        return $this->render('deck/index.html.twig', [
            'entities' => $decks,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manage/{id}", name="manage")
     * @ParamConverter("deck", options={"id"="id"})
     */
    public function manage(Request $request, Deck $deck): Response
    {
        $cards = $this->cardRepository->findAll();
        $deckCards = $this->deckCardRepository->findBy(['deck' => $deck]);

        $form = $this->createForm(DeckType::class, $deck)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deck->setUser($this->getUser());

            $this->manager->persist($deck);
            $this->manager->flush();
        }

        return $this->render('deck/manage.html.twig', [
            'deckCards' => $deckCards,
            'deck' => $deck,
            'cards' => $cards,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add_card", name="add_card")
     */
    public function addCardToDeck(Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $idDeck = $request->get('idDeck');
            $idCard = $request->get('idCard');

            $deckCard = new DeckCard();
            $deck = $this->deckRepository->findOneBy(['id' => $idDeck]);
            $card = $this->cardRepository->findOneBy(['id' => $idCard]);

            $deckCard->setCards($card);
            $deckCard->setDeck($deck);

            $this->manager->persist($deckCard);
            $this->manager->flush();
        }

        return new Response('true');
    }

    /**
     * @Route("/delete_card/{id}", name="delete_card")
     * @ParamConverter("deckCard", options={"id"="id"})
     */
    public function deleteCardToDeck(DeckCard $deckCard): Response
    {
        $this->manager->remove($deckCard);
        $this->manager->flush();

        return new Response('true');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @ParamConverter("deck", options={"mapping"={"id"="id"}})
     */
    public function deleteDeck(EntityManagerInterface $entityManager, Deck $deck): Response
    {

        $entityManager->remove($deck);
        $entityManager->flush();

        $this->addFlash('danger', "the deck has been deleted");

        return $this->redirectToRoute('deck_list');
    }
}