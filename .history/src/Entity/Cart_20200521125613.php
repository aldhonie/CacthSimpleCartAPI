<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $items = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $totalFormatted;

    /**
     * @ORM\Column(type="integer")
     */
    private $savings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $savingsFormatted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getTotalFormatted(): ?string
    {
        return $this->totalFormatted;
    }

    public function setTotalFormatted(string $totalFormatted): self
    {
        $this->totalFormatted = $totalFormatted;

        return $this;
    }

    public function getSavings(): ?int
    {
        return $this->savings;
    }

    public function setSavings(int $savings): self
    {
        $this->savings = $savings;

        return $this;
    }

    public function getSavingsFormatted(): ?string
    {
        return $this->savingsFormatted;
    }

    public function setSavingsFormatted(string $savingsFormatted): self
    {
        $this->savingsFormatted = $savingsFormatted;

        return $this;
    }
}
