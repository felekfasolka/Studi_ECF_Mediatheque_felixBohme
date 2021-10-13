<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @Vich\Uploadable
 */
class Book
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coverPicture;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $dateOfPublication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $isBorrowedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isConfirmed = false;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="books")
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     */
    private $isBorrowedBy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRequested;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="media_images", fileNameProperty="coverPicture")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCoverPicture(): ?string
    {
        return $this->coverPicture;
    }

    public function setCoverPicture(?string $coverPicture): self
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    public function getDateOfPublication(): ?\DateTimeImmutable
    {
        return $this->dateOfPublication;
    }

    public function setDateOfPublication(\DateTimeImmutable $dateOfPublication): self
    {
        $this->dateOfPublication = $dateOfPublication;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getIsBorrowed(): ?bool
    {
        return $this->isBorrowed;
    }

    public function setIsBorrowed(?bool $isBorrowed): self
    {
        $this->isBorrowed = $isBorrowed;

        return $this;
    }

    public function getIsBorrowedAt(): ?DateTimeInterface
    {
        return $this->isBorrowedAt;
    }

    public function setIsBorrowedAt(?DateTimeInterface $isBorrowedAt): self
    {
        $this->isBorrowedAt = $isBorrowedAt;

        return $this;
    }
    public function setIsBorrowedAtNull(?DateTimeInterface $isBorrowedAt): self
    {
        $this->isBorrowedAt = NULL;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(?bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getIsBorrowedBy(): ?User
    {
        return $this->isBorrowedBy;
    }

    public function setIsBorrowedBy(?User $isBorrowedBy): self
    {
        $this->isBorrowedBy = $isBorrowedBy;

        return $this;
    }

    public function getIsRequested(): ?bool
    {
        return $this->isRequested;
    }

    public function setIsRequested(?bool $isRequested): self
    {
        $this->isRequested = $isRequested;

        return $this;
    }
}
