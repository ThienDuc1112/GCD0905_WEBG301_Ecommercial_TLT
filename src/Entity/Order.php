<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeInterface;
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
     * @ORM\OneToMany(targetEntity=OrderDetail::class, mappedBy="orderRef", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status= self::STATUS_CART;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @var string
     */
    const STATUS_CART = 'cart';

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderDetail $item): self
    {
        foreach ($this->getItems() as $existingItem) {
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $this;
            }
        }
        $this->items[] = $item;
        $item->setOrderRef($this);

    }
    public function getDelivery_Address(): ?string
    {
        return $this->Delivery_address;
    }

    public function setDelivery_Address(string $Delivery_address): self
    {
        $this->Delivery_address = $Delivery_address;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->Order_date;

            return $this;

    }

    public function removeItem(OrderDetail $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderRef() === $this) {
                $item->setOrderRef(null);
            }
        }

        return $this;
    }


    public function getOrder_Date(): ?\DateTimeInterface
    {
        return $this->Order_date;
    }

    public function setOrder_Date(\DateTimeInterface $Order_date): self
    {
        $this->Order_date = $Order_date;

        return $this;
    }


    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeItems(): self

    {
        foreach ($this->getItems() as $item) {
            $this->removeItem($item);
        }

        return $this;
    }
    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }


    public function getOrderStatus(): ?string

    public function getCreatedAt(): ?DateTimeInterface
>>>>>>> Stashed changes
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

<<<<<<< Updated upstream
    public function getOrder_Status(): ?string
    {
        return $this->Order_status;
    }

    public function setOrder_Status(string $Order_status): self
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
=======
>>>>>>> Stashed changes

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
