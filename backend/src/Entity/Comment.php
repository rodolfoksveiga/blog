<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment implements \JsonSerializable
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
    private $modifiedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, inversedBy="comments")
     */
    private $postId;

    public function jsonSerialize()
    {
        return [
            'modifiedAt' => $this->modifiedAt,
            'postId' => $this->postId
        ];
    }

    public function __construct()
    {
        $this->postId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getPostId(): Collection
    {
        return $this->postId;
    }

    public function addPostId(Post $postId): self
    {
        if (!$this->postId->contains($postId)) {
            $this->postId[] = $postId;
        }

        return $this;
    }

    public function removePostId(Post $postId): self
    {
        $this->postId->removeElement($postId);

        return $this;
    }
}
