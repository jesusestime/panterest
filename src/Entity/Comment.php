<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Entity\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable] 



#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Assert\Length(min:3)]
    private $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comment')]
    #[ORM\JoinColumn(nullable: false)]

    private $userId;

    #[ORM\ManyToOne(targetEntity: Pin::class, inversedBy: 'comment')]
    #[ORM\JoinColumn(nullable: false)]

    private $pinId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $file;

 

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
 
    private $replies;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPinId(): ?Pin
    {
        return $this->pinId;
    }

    public function setPinId(?Pin $pinId): self
    {
        $this->pinId = $pinId;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }












    
    #[Vich\UploadableField(mapping: 'comment_file', fileNameProperty: 'file')]
    #[Assert\File(maxSize : "6M")]
    private ?File $fileFile = null;

    public function setfileFile(?File $fileFile = null): void
    {
        $this->fileFile = $fileFile;

        if (null !== $fileFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getfileFile(): ?File
    {
        return $this->fileFile;
    }

}
