<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Timestamps;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Table(name="Task")
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    use Timestamps;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     * 
     * @ORM\Id
     * @ORM\Column(name="id", type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length="255")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="tasks")
     * @JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Get the value of id
     *
     * @return  string|null
     */
    public function getId(): ?string
    {
        return is_null($this->id) ? $this->id : $this->id->toString();
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * Set the value of project
     *
     * @param Project $project
     * 
     * @return  self
     */
    public function setProject(Project $project = null): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get the value of deletedAt
     *
     * @return  \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Set the value of deletedAt
     *
     * @param  string  $deletedAt
     *
     * @return  self
     */
    public function setDeletedAt(\DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
