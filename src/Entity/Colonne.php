<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ColonneRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ColonneRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Colonne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     * @Assert\Length(min=3, max=255, 
     * minMessage="Le libellé doit faire au moins 3 caractères",
     * maxMessage="Le libellé doit faire tout au plus 255 caractères")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=DetailProduct::class, mappedBy="colonne")
     */
    private $detailProducts;

    public function __construct()
    {
        $this->detailProducts = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function setCreatedAtValue() {
        $date = new \DateTime();
        $this->createdAt = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection|DetailProduct[]
     */
    public function getDetailProducts(): Collection
    {
        return $this->detailProducts;
    }

    public function addDetailProduct(DetailProduct $detailProduct): self
    {
        if (!$this->detailProducts->contains($detailProduct)) {
            $this->detailProducts[] = $detailProduct;
            $detailProduct->setColonne($this);
        }

        return $this;
    }

    public function removeDetailProduct(DetailProduct $detailProduct): self
    {
        if ($this->detailProducts->removeElement($detailProduct)) {
            // set the owning side to null (unless already changed)
            if ($detailProduct->getColonne() === $this) {
                $detailProduct->setColonne(null);
            }
        }

        return $this;
    }

    
}
