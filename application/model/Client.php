<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Model_Client
 *
 * @Table(name="client")
 * @Entity(repositoryClass="Model_Repository_Client_Doctrine")
 */
class Model_Client
{
    /**
     * @var string $id
     *
     * @Column(name="id", type="string", length=32, nullable=false)
     * @Id
     */
    private $id;

    /**
     * @var string $name
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string $secret
     *
     * @Column(name="secret", type="string", length=32, nullable=false)
     */
    private $secret;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToMany(targetEntity="Model_Experiment", inversedBy="clients", indexBy="id")
     * @JoinTable(name="client_binding",
     *   joinColumns={
     *     @JoinColumn(name="client_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @JoinColumn(name="experiment_id", referencedColumnName="id")
     *   }
     * )
     */
    private $experiments;

    public function __construct($id, $secret, $name)
    {
    	$this->id = $id;
    	$this->secret = $secret;
    	$this->name = $name;
        $this->experiments = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getSecret()
    {
    	return $this->secret;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function getExperiments()
    {
    	return $this->experiments;
    }
}