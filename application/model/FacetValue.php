<?php

use DoctrineExtensions\NestedSet\MultipleRootNode;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model_FacetValue
 *
 * @Table(name="facet_value")
 * @Entity(repositoryClass="Model_Repository_FacetValue_Doctrine")
 */
class Model_FacetValue implements MultipleRootNode
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $externalId
     *
     * @Column(name="external_id", type="integer", nullable=false)
     */
    private $externalId;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var Model_Facet
     *
     * @ManyToOne(targetEntity="Model_Facet")
     * @JoinColumns({
     *   @JoinColumn(name="facet_id", referencedColumnName="id")
     * })
     */
    private $facet;

    /**
     * @var integer $lft
     *
     * @Column(name="lft", type="integer", nullable=false)
     */
    private $lft;

    /**
     * @var integer $rgt
     *
     * @Column(name="rgt", type="integer", nullable=false)
     */
    private $rgt;

    /**
     * @var integer $root
     *
     * @Column(name="root", type="integer", nullable=false)
     */
    private $root;

    /**
     * @oneToMany(targetEntity="Model_Event", mappedBy="facetValue")
     */
    private $events; // nema ani getter, je jen kvuli sestavovani dotazu
    
    public function __construct()
    {
    	$this->events = new ArrayCollection();
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function setExternalId($id)
    {
    	$this->externalId = $id;
    }
    
    public function getExternalId()
    {
    	return $this->externalId;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setFacet(Model_Facet $facet)
    {
    	$this->facet = $facet;
    }
    
    public function getFacet()
    {
    	return $this->facet;
    }
    
    // nasledujici metody jsou tu kvuli implementaci \DoctrineExtensions\NestedSet\MultipleRootNode
    
    public function __toString()
    {
    	return $this->name;
    }
    
    public function getLeftValue()
    {
    	return $this->lft;
    }
    
    public function setLeftValue($lft)
    {
    	$this->lft = $lft;
    }
    
    public function getRightValue()
    {
    	return $this->rgt;
    }
    
    public function setRightValue($rgt)
    {
    	$this->rgt = $rgt;
    }
    
    public function getRootValue()
    {
    	return $this->root;
    }
    
    public function setRootValue($root)
    {
    	$this->root = $root;
    }
}