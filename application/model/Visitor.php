<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Visitor
 *
 * @Table(name="visitor")
 * @Entity(repositoryClass="Model_Repository_Visitor_Doctrine")
 */
class Model_Visitor
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="string", length=36, nullable=false)
     * @Id
     */
    private $id;

    /**
     * @var string $ipv4
     *
     * @Column(name="ipv4", type="string", length=16, nullable=true)
     */
    private $ipv4;

    /**
     * @var string $userAgent
     *
     * @Column(name="user_agent", type="string", length=1024, nullable=true)
     */
    private $userAgent;

    /**
     * @var integer $created
     *
     * @Column(name="created", type="integer", nullable=false)
     */
    private $created;
    
    /**
     * @oneToMany(targetEntity="Model_Event", mappedBy="visitor")
     */
    private $events;
    
    public function __construct()
    {
    	$this->events = new ArrayCollection();
    }

    public function setId($id)
    {
    	$this->id = $id;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function setIpv4($ip)
    {
    	$this->ipv4 = $ip;
    }
    
    public function getIpv4()
    {
    	return $this->ipv4;
    }
    
    public function setUserAgent($ua)
    {
    	$this->userAgent = $ua;
    }
    
    public function getUserAgent()
    {
    	return $this->userAgent;
    }
    
    public function setCreated($timestamp)
    {
    	$this->created = $timestamp;
    }
    
    public function getCreated()
    {
    	return $this->created;
    }
    
    public function getEvents()
    {
    	return $this->events;
    }
    
    public function getUserAgentDetails()
    {
    	return get_browser($this->userAgent);
    }
}