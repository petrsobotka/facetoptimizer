<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model_Event
 *
 * @Table(name="event")
 * @Entity(repositoryClass="Model_Repository_Event_Doctrine")
 */
class Model_Event
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
     * @var integer $timestamp
     *
     * @Column(name="timestamp", type="integer", nullable=false)
     */
    private $timestamp;

    /**
     * @var integer $resultSetSize
     *
     * @Column(name="result_set_size", type="integer", nullable=true)
     */
    private $resultSetSize;

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
     * @var Model_Visitor
     *
     * @ManyToOne(targetEntity="Model_Visitor")
     * @JoinColumns({
     *   @JoinColumn(name="visitor_id", referencedColumnName="id")
     * })
     */
    private $visitor;

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
     * @var Model_FacetValue
     *
     * @ManyToOne(targetEntity="Model_FacetValue")
     * @JoinColumns({
     *   @JoinColumn(name="facet_value_id", referencedColumnName="id")
     * })
     */
    private $facetValue;

    /**
     * @var Model_EventType
     *
     * @ManyToOne(targetEntity="Model_EventType")
     * @JoinColumns({
     *   @JoinColumn(name="event_type_id", referencedColumnName="id")
     * })
     */
    private $eventType;
    
    /**
     * @oneToMany(targetEntity="Model_FormerChoice", mappedBy="event")
     */
    private $formerChoices;

    public function __construct()
    {
    	$this->formerChoices = new ArrayCollection();
    }

    public function getId()
    {
    	return $this->id;
    }
    
    public function setTimestamp($stamp)
    {
    	$this->timestamp = $stamp;
    }
    
    public function getTimestamp()
    {
    	return $this->timestamp;
    }
    
    public function setResultSetSize($size)
    {
    	$this->resultSetSize = $size;
    }
    
    public function getResultSetSize()
    {
    	return $this->resultSetSize;
    }
    
    public function setExperiment(Model_Experiment $experiment)
    {
    	$this->experiment = $experiment;
    }
    
    public function getExperiment()
    {
    	return $this->experiment;
    }
    
    public function setVisitor(Model_Visitor $visitor)
    {
    	$this->visitor = $visitor;
    }
    
    public function getVisitor()
    {
    	return $this->visitor;
    }
    
    public function setFacet(Model_Facet $facet)
    {
    	$this->facet = $facet;
    }
    
    public function getFacet()
    {
    	return $this->facet;
    }
    
    public function setFacetValue(Model_FacetValue $facetValue)
    {
    	$this->facetValue = $facetValue;
    }
    
    public function getFacetValue()
    {
    	return $this->facetValue;
    }
    
    public function setEventType($type)
    {
    	$this->eventType = $type;
    }
    
    public function getEventType()
    {
    	return $this->eventType;
    }
    
    public function getFormerChoices()
    {
    	return $this->formerChoices;
    }
    
    public function getDateTime()
    {
    	$dt = new DateTime(NULL, new DateTimeZone('Europe/Prague'));
    	$dt->setTimestamp($this->timestamp);
    	return $dt;
    }
}