<?php

declare(strict_types=1);

namespace App\Entity;

trait Timestamps
{
    /**
     * @var \DataTime
     * 
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DataTime
     * 
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Set the value of createdAt
     * 
     * @ORM\PrePersist()
     */
    public function setCreatedAt() : self
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Set the value of updatedAt
     * 
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt() : self
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return  \DateTime
     */ 
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updatedAt
     * 
     * @return \DateTime
     */ 
    public function getUpdatedAt() : \DateTime
    {
        return $this->updatedAt;
    }

}