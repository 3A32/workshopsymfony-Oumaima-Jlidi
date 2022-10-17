<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
//test
#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]

    #[ORM\Column]
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'clubs')]
    private Collection $student;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }






    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setRef(int $ref): self
    {
        $this->ref = $ref;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->student->removeElement($student);

        return $this;
    }
}