<?php 

class Model_Repository_User_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_User_Interface
{

	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_User_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_User_Interface::retrieveByUsername()
	 */
	public function retrieveByUsername($username)
	{
		return $this->retrieveByEmail($username);
	}
	
	public function retrieveByEmail($email)
	{
		$query = $this->_em->createQuery("SELECT u FROM Model_User u WHERE u.email = :email");
		$query->setParameter('email', $email);
		
		try {
			$user = $query->getSingleResult();
		} catch( \Doctrine\ORM\NoResultException $e){
			$user = null;
		}
		return $user;
	}
}