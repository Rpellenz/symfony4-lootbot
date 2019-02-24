<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 * @Table(name="config",indexes={@Index(name="idx_guild_key", columns={"guild_id", "config_key"})})
 */
class Config
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
    private $config_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $config_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guild_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigKey(): ?string
    {
        return $this->config_key;
    }

    public function setConfigKey(string $configKey): self
    {
        $this->config_key = $configKey;

        return $this;
    }

    public function getConfigValue(): ?string
    {
        return $this->config_value;
    }

    public function setConfigValue(string $configValue): self
    {
        $this->config_value = $configValue;

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
