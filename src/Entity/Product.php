<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var
     * @ORM\Column(type="string", length=100)
     */
    private $network;

    /**
     * @var
     * @ORM\Column(type="string", length=100)
     */
    private $os;

    /**
     * @var
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var
     * @ORM\Column(type="float")
     */
    private $weight;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $created
     * @return Product
     */
    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param $price
     * @return Product
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param $network
     * @return Product
     */
    public function setNetwork($network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param $os
     * @return Product
     */
    public function setOs($os): self
    {
        $this->os = $os;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return Product
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return Product
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param $weight
     * @return Product
     */
    public function setWeight($weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
