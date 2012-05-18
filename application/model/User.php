<?php

/**
 * Model_User
 *
 * @Table(name="user")
 * @Entity(repositoryClass="Model_Repository_User_Doctrine")
 */
class Model_User
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
     * @var string $email
     *
     * @Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=128, nullable=false)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @Column(name="salt", type="string", length=16, nullable=false)
     */
    private $salt;

    /**
     * @var boolean $superuser
     *
     * @Column(name="superuser", type="boolean", nullable=false)
     */
    private $superuser;

	public function getId()
	{
		return $this->id;
	}
	
	public function setName( $name )
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setPassword($pass)
	{
		$this->password = $pass;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}
	
	public function getSalt()
	{
		return $this->salt;
	}
	
	public function isSuperuser()
	{
		return $this->superuser;
	}
	
	public function setSuperuser($flag)
	{
		$this->superuser = $flag;
	}
}