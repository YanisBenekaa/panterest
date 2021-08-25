<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Please enter your email")
     * @Assert\Email(message="Your email is not valid")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please enter your password")
     * @Assert\NotCompromisedPassword(message="This password has been leaked in a data breach, it must not be used. Please use another password.")
     * @Assert\Regex(pattern="/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$/", message="Your password must contain 12 characters including at least : 1 lower letter, 1 upper letter, 1 number, 1 special character")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter your last name")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Pin::class, mappedBy="user", orphanRemoval=true)
     */
    private $pins;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function __construct()
    {
        $this->pins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Pin[]
     */
    public function getPins(): Collection
    {
        return $this->pins;
    }

    public function addPin(Pin $pin): self
    {
        if (!$this->pins->contains($pin)) {
            $this->pins[] = $pin;
            $pin->setUser($this);
        }

        return $this;
    }

    public function removePin(Pin $pin): self
    {
        if ($this->pins->removeElement($pin)) {
            // set the owning side to null (unless already changed)
            if ($pin->getUser() === $this) {
                $pin->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function gravatar(?int $size = 200): string
    {
        return 'https://www.gravatar.com/avatar/'. md5(strtolower(trim($this->getEmail()))) .'/?s='.$size;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
