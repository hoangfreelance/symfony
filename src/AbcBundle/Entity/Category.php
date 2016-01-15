<?php

namespace AbcBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add products
     *
     * @param \AbcBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\AbcBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \AbcBundle\Entity\Product $products
     */
    public function removeProduct(\AbcBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add children
     *
     * @param \AbcBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\AbcBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AbcBundle\Entity\Category $children
     */
    public function removeChild(\AbcBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \AbcBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\AbcBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AbcBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getIndentedTitle() {
        return sprintf(
            '%s %s',
            str_repeat('-', $this->getLevel()),
            $this->getName()
        );
    }
    
    /**
    * get a numeric representation of the press category level
    * i.e. parent categories - 0, child categories - 1, grandchild categories - 2 etc...
    *
    * @return int
    */
    public function getLevel()
    {
        $level = 0;
        $category = $this;
        if ($category->hasParent() === false) {
            return $level;
        }
        while ($category = $category->getParent()) {
            $level++;
        }
        return $level;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return ($this->parent != null ? true : false);
    }
}
