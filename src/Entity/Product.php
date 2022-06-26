<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Brand;

    /**
     * @ORM\OneToMany(targetEntity=DetailOrder::class, mappedBy="Products")
     */
    private $detailOrders;


    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="categories")
     */
    private $Category;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $Images;

    public function __construct()
    {
        $this->detailOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->Brand;
    }

    public function setBrand(string $Brand): self
    {
        $this->Brand = $Brand;

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
            $detailOrder->setProducts($this);
        }

        return $this;
    }

    public function removeDetailOrder(DetailOrder $detailOrder): self
    {
        if ($this->detailOrders->removeElement($detailOrder)) {
            // set the owning side to null (unless already changed)
            if ($detailOrder->getProducts() === $this) {
                $detailOrder->setProducts(null);
            }
        }

        return $this;
    }



    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getImages(): ?Image
    {
        return $this->Images;
    }

    public function setImages(?Image $Images): self
    {
        $this->Images = $Images;

        return $this;
    }

    public function getimages_id(): ?Image
    {
        return $this->Images;
    }

    public function setimages_id(?Image $Images): self
    {
        $this->Images = $Images;

        return $this;
    }
}
