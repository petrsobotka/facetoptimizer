<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model_Experiment
 *
 * @Table(name="experiment")
 * @Entity(repositoryClass="Model_Repository_Experiment_Doctrine")
 */
class Model_Experiment
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
     * @var string $name
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var text $description
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $url
     *
     * @Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;
    
    /**
     * @oneToMany(targetEntity="Model_Facet", mappedBy="experiment")
     */
    private $facets;
    
    /**
     * @oneToMany(targetEntity="Model_Event", mappedBy="experiment")
     */
    private $events;
    
    /**
     * @oneToMany(targetEntity="Model_UserBinding", mappedBy="experiment", cascade={"remove"})
     */
    private $userBindings;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToMany(targetEntity="Model_Client", mappedBy="experiments")
     */
    private $clients;

    public function __construct()
    {
    	$this->facets = new ArrayCollection();
    	$this->events = new ArrayCollection();
    	$this->userBindings = new ArrayCollection();
    	$this->clients = new ArrayCollection();
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setDescription($desc)
    {
    	$this->description = $desc;
    }
    
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function setUrl($url)
    {
    	$this->url = $url;
    }
    
    public function getUrl()
    {
    	return $this->url;
    }
    
    public function getFacets()
    {
    	return $this->facets;
    }
    
    public function getEvents()
    {
    	return $this->events;
    }
    
    public function getClients()
    {
    	return $this->clients;
    }

    /*
    public function set($)
    {
    	$this-> = $;
    }
    
    public function get()
    {
    	return $this->;
    }
    */

}