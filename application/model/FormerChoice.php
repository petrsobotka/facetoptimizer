<?php

/**
 * Model_FormerChoice
 *
 * @Table(name="former_choice")
 * @Entity
 */
class Model_FormerChoice
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
     * @var Model_Event
     *
     * @ManyToOne(targetEntity="Model_Event")
     * @JoinColumns({
     *   @JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;

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

    public function getId()
    {
    	return $this->id;
    }
    
    public function setEvent(Model_Event $event)
    {
    	$this->event = $event;
    }
    
    public function getEvent()
    {
    	return $this->event;
    }
    
    public function setFacet(Model_Facet $facet)
    {
    	$this->facet = $facet;
    }
    
    public function getFacet()
    {
    	return $this->facet;
    }
    
    public function setFacetValue(Model_FacetValue $value)
    {
    	$this->facetValue = $value;
    }
    
    public function getFacetValue()
    {
    	return $this->facetValue;
    }
}