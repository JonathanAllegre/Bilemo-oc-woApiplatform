<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @ORM\Table()
 *  @Hateoas\Relation(
 *     name = "self",
 *      href = @Hateoas\Route(
 *          "app_user_show",
 *           parameters = { "id" = "expr(object.getId())" },
 *           absolute = true
 *     )
 *
 * )
 *
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"list", "detail"})
     */
    private $id;

    /**
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     *
     * @var
     * @ORM\Column(type="string", length=255, name="first_name")
     */
    private $firstName;

    /**
     * @Serializer\Groups({"list", "detail"})
     * @var
     * @ORM\Column(type="string", length=255, name="last_name")
     */
    private $lastName;

    /**
     * @Serializer\Groups({"list", "detail"})
     * @var
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Serializer\Groups({"create"})
     * @var
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     * @return User
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return User
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return User
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
