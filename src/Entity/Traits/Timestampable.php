<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait Timestampable
{

    
    #[Gedmo\Timestampable(on:"create")]
    #[ORM\Column(type: 'datetime_immutable',options: ['default'=>'CURRENT_TIMESTAMP'])]
    private $createdAt;



    #[Gedmo\Timestampable(on:"update")]
    #[ORM\Column(type: 'datetime_immutable',options: ['default'=>'CURRENT_TIMESTAMP'])]
    private $updatedAt;



    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamp():void
    {
        if ($this->getCreatedAt()=== null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
        $this->setUpdatedAt(new \DateTimeImmutable());
    }
}


?>