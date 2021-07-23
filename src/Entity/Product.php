<?php

namespace App\Entity;

use App\Entity\Import;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("product:read")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("product:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $tva;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $ddi;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $position;

    /**
     * @ORM\Column(type="string")
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $debut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $unite;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $dci;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("product:read")
     * @Groups("product:public")
     */
    private $dcl;

    /**
     * @ORM\ManyToOne(targetEntity=Import::class, inversedBy="products")
     */
    private $import;

    /**
     * @ORM\OneToMany(targetEntity=DetailProduct::class, mappedBy="product")
     * @Groups("product:read")
     */
    private $detailProducts;

    public function __construct()
    {
        $this->detailProducts = new ArrayCollection();
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(?string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getDdi(): ?string
    {
        return $this->ddi;
    }

    public function setDdi(?string $ddi): self
    {
        $this->ddi = $ddi;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDebut(): string
    {
        return $this->debut;
    }

    public function setDebut(string $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unite = $unite;

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

    public function getDci(): ?string
    {
        return $this->dci;
    }

    public function setDci(?string $dci): self
    {
        $this->dci = $dci;

        return $this;
    }

    public function getDcl(): ?string
    {
        return $this->dcl;
    }

    public function setDcl(?string $dcl): self
    {
        $this->dcl = $dcl;

        return $this;
    }

    public function getImport(): ?import
    {
        return $this->import;
    }

    public function setImport(?import $import): self
    {
        $this->import = $import;

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
            $detailProduct->setProduct($this);
        }

        return $this;
    }

    public function removeDetailProduct(DetailProduct $detailProduct): self
    {
        if ($this->detailProducts->removeElement($detailProduct)) {
            // set the owning side to null (unless already changed)
            if ($detailProduct->getProduct() === $this) {
                $detailProduct->setProduct(null);
            }
        }

        return $this;
    }
}
