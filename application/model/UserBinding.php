<?php

/**
 * Model_UserBinding
 *
 * @Table(name="user_binding")
 * @Entity(repositoryClass="Model_Repository_UserBinding_Doctrine")
 */
class Model_UserBinding
{
    /**
     * @var Model_User $user
     *
     * @Id
     * @ManyToOne(targetEntity="Model_User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;
    
    /**
     * @var Model_Experiment $experiment
     *
     * @Id
     * @ManyToOne(targetEntity="Model_Experiment")
     * @JoinColumns({
     *   @JoinColumn(name="experiment_id", referencedColumnName="id")
     * })
     */
    private $experiment;

    /**
     * @var string $role
     *
     * @Column(name="role", type="string", length=32, nullable=false)
     */
    private $role;


	public function __construct( Model_User $user, Model_Experiment $experiment, $role)
	{
		$this->user = $user;
		$this->experiment = $experiment;
		$this->role = $role;
	}
	
	public function getRole()
	{
		return $this->role;
	}
	
}