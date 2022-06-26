<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Delivery_address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Order_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Order_phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name_customer;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Order_status;

    /**
     * @ORM\OneToMany(targetEntity=DetailOrder::class, mappedBy="Orders")
     */
    private $detailOrders;

    public function __construct()
    {
        $this->detailOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->Delivery_address;
    }

    public function setDeliveryAddress(string $Delivery_address): self
    {
        $this->Delivery_address = $Delivery_address;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->Order_date;
    }

    public function setOrderDate(\DateTimeInterface $Order_date): self
    {
        $this->Order_date = $Order_date;

        return $this;
    }

    public function getOrderPhone(): ?string
    {
        return $this->Order_phone;
    }

    public function setOrderPhone(string $Order_phone): self
    {
        $this->Order_phone = $Order_phone;

        return $this;
    }

    public function getNameCustomer(): ?string
    {
        return $this->Name_customer;
    }

    public function setNameCustomer(string $Name_customer): self
    {
        $this->Name_customer = $Name_customer;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->Order_status;
    }

    public function setOrderStatus(string $Order_status): self
    {
        $this->Order_status = $Order_status;

        return $this;
    }

    /**
     * @return Collection<int, DetailOrder>
     */
    public function getDetailOrders(): Collection
    {
        return $this->detailOrders;
    }

    public function addDetailOrder(DetailOrder $detailOrder): self
    {
        if (!$this->detailOrders->contains($detailOrder)) {
            $this->detailOrders[] = $detailOrder;
            $detailOrder->setOrders($this);
        }

        return $this;
    }

    public function removeDetailOrder(DetailOrder $detailOrder): self
    {
        if ($this->detailOrders->removeElement($detailOrder)) {
            // set the owning side to null (unless already changed)
            if ($detailOrder->getOrders() === $this) {
                $detailOrder->setOrders(null);
            }
        }

        return $this;
    }
}
