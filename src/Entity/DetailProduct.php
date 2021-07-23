<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DetailProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DetailProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *      fields={"colonne", "product"},
 *      errorPath="colonne",
 *      message="Une valeur a été déjà ajoutée pour ce champ sélectionné"
 * )
 */
class DetailProduct
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
     * @ORM\Column(type="text")
     * @Groups("product:read")
     * @Assert\Length(min=3, max=255, 
     * minMessage="Le libellé doit faire au moins 3 caractères",
     * maxMessage="Le libellé doit faire tout au plus 255 caractères")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Colonne::class, inversedBy="detailProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colonne;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="detailProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("product:read")
     */
    private $colName;

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

    public function getColonne(): ?Colonne
    {
        return $this->colonne;
    }

    public function setColonne(?Colonne $colonne): self
    {
        $this->colonne = $colonne;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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

    public function getColName(): ?string
    {
        return $this->colName;
    }

    public function setColName(string $colName): self
    {
        $this->colName = $colName;

        return $this;
    }
}
