<?php

namespace App\Entity;

use App\Repository\DetailOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetailOrderRepository::class)
 */
class DetailOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="detailOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Products;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="detailOrders")
     */
    private $Orders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getProducts(): ?Product
    {
        return $this->Products;
    }

    public function setProducts(?Product $Products): self
    {
        $this->Products = $Products;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->Orders;
    }

    public function setOrders(?Order $Orders): self
    {
        $this->Orders = $Orders;

        return $this;
    }
}
