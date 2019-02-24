<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscordMemberRepository")
 */
class DiscordMember
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $discord_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guild_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDiscordId(): ?string
    {
        return $this->discord_id;
    }

    public function setDiscordId(string $discord_id): self
    {
        $this->discord_id = $discord_id;

        return $this;
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
}
