<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model_Facet
 *
 * @Table(name="facet")
 * @Entity(repositoryClass="Model_Repository_Facet_Doctrine")
 */
class Model_Facet
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
     * @var boolean $static
     *
     * @Column(name="static", type="boolean", nullable=false)
     */
    private $static;

    /**
     * @var Model_Experiment
     *
     * @ManyToOne(targetEntity="Model_Experiment")
     * @JoinColumns({
     *   @JoinColumn(name="experiment_id", referencedColumnName="id")
     * })
     */
    private $experiment;
    
    /**
     * @oneToMany(targetEntity="Model_FacetValue", mappedBy="facet")
     */
    private $facetValues;
    
    /**
     * @oneToMany(targetEntity="Model_Event", mappedBy="facet")
     */
    private $events; // nema ani getter, je jen kvuli sestavovani dotazu
    
    public function __construct()
    {
    	$this->facetValues = new ArrayCollection();
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
    
    public function setStatic($flag)
    {
    	$this->static = $flag;
    }
    
    public function isStatic()
    {
    	return $this->static;
    }
    
    public function setExperiment( Model_Experiment $experiment )
    {
    	$this->experiment = $experiment;
    }
    
    public function getExperiment()
    {
    	return $this->experiment;
    }
    
}