<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participants")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conversation", inversedBy="participants")
     */
    private $conversationId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $messageReadAt;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->conversationId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->userId->contains($userId)) {
            $this->userId[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->userId->contains($userId)) {
            $this->userId->removeElement($userId);
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationId(): Collection
    {
        return $this->conversationId;
    }

    public function addConversationId(Conversation $conversationId): self
    {
        if (!$this->conversationId->contains($conversationId)) {
            $this->conversationId[] = $conversationId;
        }

        return $this;
    }

    public function removeConversationId(Conversation $conversationId): self
    {
        if ($this->conversationId->contains($conversationId)) {
            $this->conversationId->removeElement($conversationId);
        }

        return $this;
    }

    public function getMessageReadAt(): ?\DateTimeInterface
    {
        return $this->messageReadAt;
    }

    public function setMessageReadAt(\DateTimeInterface $messageReadAt): self
    {
        $this->messageReadAt = $messageReadAt;

        return $this;
    }
}
