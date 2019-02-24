<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemTemplateRepository")
 */
class ItemTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $entry;

    /**
     * @ORM\Column(type="integer")
     */
    private $class;

    /**
     * @ORM\Column(type="integer")
     */
    private $subclass;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $displayid;

    /**
     * @ORM\Column(type="integer")
     */
    private $quality;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?int
    {
        return $this->entry;
    }

    public function setEntry(int $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function getClass(): ?int
    {
        return $this->class;
    }

    public function setClass(int $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getSubclass(): ?int
    {
        return $this->subclass;
    }

    public function setSubclass(int $subclass): self
    {
        $this->subclass = $subclass;

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

    public function getDisplayid(): ?int
    {
        return $this->displayid;
    }

    public function setDisplayid(int $displayid): self
    {
        $this->displayid = $displayid;

        return $this;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }
}
