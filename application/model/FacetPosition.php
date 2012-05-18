<?php

/**
 * Model_FacetPosition
 *
 * @Table(name="facet_position")
 * @Entity(repositoryClass="Model_Repository_FacetPosition_Doctrine")
 */
class Model_FacetPosition
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
     * @var integer $position
     *
     * @Column(name="position", type="integer", nullable=false)
     */
    private $position;

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

    public function getId()
    {
    	return $this->id;
    }
    
    public function setPosition($position)
    {
    	$this->position = $position;
    }
    
    public function getPosition()
    {
    	return $this->position;
    }
    
    public function setExperiment( Model_Experiment $experiment )
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
}