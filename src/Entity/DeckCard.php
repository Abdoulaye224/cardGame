<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeckCardRepository")
 */
class DeckCard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Deck", inversedBy="deckCards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deck;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card", inversedBy="deckCards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cards;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
    }

    public function getCards(): ?Card
    {
        return $this->cards;
    }

    public function setCards(?Card $cards): self
    {
        $this->cards = $cards;

        return $this;
    }
}
