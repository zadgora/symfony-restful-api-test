<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Timestamps;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="Project")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Project
{
    use Timestamps;

    /**
     * @var Uuid
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
     * @ORM\Column(name="title", type="string", length="255")
     */
    private $title;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * 
     * @ORM\Column(name="status", type="string", length="100")
     */
    private $status;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="duration", type="string", length="50", nullable=true)
     */
    private $duration;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="client",  type="string", length="255", nullable=true)
     */
    private $client;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="company", type="string", length="255", nullable=true)
     */
    private $company;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="project", cascade={"REMOVE"}, fetch="EAGER")
     */
    private $tasks;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deteletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
     * Get the value of title
     *
     * @return  string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string|null  $description
     *
     * @return  self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of duration
     *
     * @return  string|null
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @param  string|null  $duration
     *
     * @return  self
     */
    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get the value of client
     *
     * @return  string|null
     */
    public function getClient(): ?string
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @param  string|null  $client
     *
     * @return  self
     */
    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of company
     *
     * @return  string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @param  string|null  $company
     *
     * @return  self
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get the value of tasks
     *
     * @return  iterable
     */
    public function getTasks(): iterable
    {
        return $this->tasks;
    }

    /**
     * Set the value of tasks
     *
     * @param  iterable  $tasks
     *
     * @return  self
     */
    public function setTasks(iterable $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Get the value of deteletedAt
     *
     * @return  \DateTime|null
     */
    public function getDeteletedAt(): ?\DateTime
    {
        return $this->deteletedAt;
    }

    /**
     * Set the value of deteletedAt
     *
     * @param  \DateTime  $deteletedAt
     *
     * @return  self
     */
    public function setDeteletedAt(\DateTime $deteletedAt): self
    {
        $this->deteletedAt = $deteletedAt;

        return $this;
    }

    /**
     * Add task to project
     * 
     * @param Task $task
     * 
     * @return self
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    /**
     * Remove task from project
     * 
     * @param Task $task
     * 
     * @return self
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }
}
