<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
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
    private $Img1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Img2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Img3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Igm4;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImg1(): ?string
    {
        return $this->Img1;
    }

    public function setImg1(string $Img1): self
    {
        $this->Img1 = $Img1;

        return $this;
    }

    public function getImg2(): ?string
    {
        return $this->Img2;
    }

    public function setImg2(?string $Img2): self
    {
        $this->Img2 = $Img2;

        return $this;
    }

    public function getImg3(): ?string
    {
        return $this->Img3;
    }

    public function setImg3(?string $Img3): self
    {
        $this->Img3 = $Img3;

        return $this;
    }

    public function getIgm4(): ?string
    {
        return $this->Igm4;
    }

    public function setIgm4(?string $Igm4): self
    {
        $this->Igm4 = $Igm4;

        return $this;
    }

    public function __toString() {
        return $this->Img1;
    }


}
