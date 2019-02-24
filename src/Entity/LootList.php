<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LootListRepository")
 * @Table(name="loot_list",indexes={@Index(name="idx_loot_guild_id_user_id_item_name_insert_ts", columns={"guild_id", "member_id", "item_name", "insert_ts"})})
 */
class LootList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $guild_id;

    /**
     * @ORM\Column(type="string")
     */
    private $member_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $item_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $item_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $insert_ts;

    /**
     * @ORM\Column(type="integer")
     */
    private $quality;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuildId(): ?string
    {
        return $this->guild_id;
    }

    public function getMemberId(): ?string
    {
        return $this->member_id;
    }
    public function setMemberId(string $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }

    public function setGuildId(string $guild_id): self
    {
        $this->guild_id = $guild_id;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setItemId(?string $item_id): self
    {
        $this->item_id = $item_id;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->item_name;
    }

    public function setItemName(string $item_name): self
    {
        $this->item_name = $item_name;

        return $this;
    }

    public function getInsertTs(): ?\DateTimeInterface
    {
        return $this->insert_ts;
    }

    public function setInsertTs(): self
    {
        $this->insert_ts = new \DateTime("now");

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
