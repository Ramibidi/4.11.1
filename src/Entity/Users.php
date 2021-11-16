<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Products::class, mappedBy="user")
     */
    private $productsUser;

    public function __construct()
    {
        $this->productsUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function eraseCredentials()
    {
    }
    public function getSalt()
    {
    }

    /**
     * @return Collection|Products[]
     */
    public function getProductsUser(): Collection
    {
        return $this->productsUser;
    }

    public function addProductsUser(Products $productsUser): self
    {
        if (!$this->productsUser->contains($productsUser)) {
            $this->productsUser[] = $productsUser;
            $productsUser->setUser($this);
        }

        return $this;
    }

    public function removeProductsUser(Products $productsUser): self
    {
        if ($this->productsUser->removeElement($productsUser)) {
            // set the owning side to null (unless already changed)
            if ($productsUser->getUser() === $this) {
                $productsUser->setUser(null);
            }
        }

        return $this;
    }
}
