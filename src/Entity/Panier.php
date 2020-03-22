<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->state = false;
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="paniers")
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
