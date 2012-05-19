<?php


/**
 * Model_EventType
 *
 * @Table(name="event_type")
 * @Entity(repositoryClass="Model_Repository_EventType_Doctrine")
 */
class Model_EventType
{
	
	const APPLY_FACET_VALUE = 1;
	
	const CANCEL_FACET_VALUE = 2;
	
	const CANCEL_ALL_FACETS = 3;
	
	const HELP = 4;
	
	const SORT = 5;
	
	const PAGING = 6;
	
	const SELECT_PRODUCT = 7;
	
	const CONVERSION = 8;
	
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
     * @var string $token
     *
     * @Column(name="token", type="string", length=32, nullable=false)
     */
    private $token;
    
    /**
     * @oneToMany(targetEntity="Model_Event", mappedBy="eventType")
     */
    private $events; // nema ani getter, je jen kvuli sestavovani dotazu

    public function getId()
    {
    	return $this->id;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function getToken()
    {
    	return $this->name;
    }
    
}