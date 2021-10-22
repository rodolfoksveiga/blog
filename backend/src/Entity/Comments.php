<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
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
     * @ORM\ManyToMany(targetEntity=Posts::class, inversedBy="comments")
     */
    private $postId;

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

    /**
     * @return Collection|Posts[]
     */
    public function getPostId(): Collection
    {
        return $this->postId;
    }

    public function addPostId(Posts $postId): self
    {
        if (!$this->postId->contains($postId)) {
            $this->postId[] = $postId;
        }

        return $this;
    }

    public function removePostId(Posts $postId): self
    {
        $this->postId->removeElement($postId);

        return $this;
    }
}
