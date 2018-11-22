<?php

namespace Sfb\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="Sfb\SiteBundle\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="pricettc", type="decimal", precision=10, scale=2)
     */
    private $pricettc;

    /**
     * @var string
     *
     * @ORM\Column(name="types", type="string", length=20)
     */
    private $types;

    /**
     * @ORM\ManyToOne(targetEntity="Sfb\SiteBundle\Entity\GenreProduit", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genreProduit;

    /**
     * @ORM\ManyToMany(targetEntity="Sfb\SiteBundle\Entity\Image", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinTable(joinColumns={@ORM\JoinColumn(name="produit_id", nullable=false, referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Produit
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Produit
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set pricettc
     *
     * @param string $pricettc
     *
     * @return Produit
     */
    public function setPricettc($pricettc)
    {
        $this->pricettc = $pricettc;

        return $this;
    }

    /**
     * Get pricettc
     *
     * @return string
     */
    public function getPricettc()
    {
        return $this->pricettc;
    }

    /**
     * Set types
     *
     * @param string $types
     *
     * @return Produit
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Get types
     *
     * @return string
     */
    public function getTypes()
    {
        return $this->types;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set genreProduit
     *
     * @param \Sfb\SiteBundle\Entity\GenreProduit $genreProduit
     *
     * @return Produit
     */
    public function setGenreProduit(\Sfb\SiteBundle\Entity\GenreProduit $genreProduit)
    {
        $this->genreProduit = $genreProduit;

        return $this;
    }

    /**
     * Get genreProduit
     *
     * @return \Sfb\SiteBundle\Entity\GenreProduit
     */
    public function getGenreProduit()
    {
        return $this->genreProduit;
    }

    /**
     * Add image
     *
     * @param \Sfb\SiteBundle\Entity\Image $image
     *
     * @return Produit
     */
    public function addImage(\Sfb\SiteBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Sfb\SiteBundle\Entity\Image $image
     */
    public function removeImage(\Sfb\SiteBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Produit
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
