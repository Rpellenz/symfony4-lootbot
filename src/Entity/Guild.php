<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuildRepository")
 */
class Guild
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guild_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guild_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuildId(): ?string
    {
        return $this->guild_id;
    }

    public function setGuildId(string $guild_id): self
    {
        $this->guild_id = $guild_id;

        return $this;
    }

    public function getGuildName(): ?string
    {
        return $this->guild_name;
    }

    public function setGuildName(string $guild_name): self
    {
        $this->guild_name = $guild_name;

        return $this;
    }
}
