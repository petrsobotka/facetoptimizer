<?php 

class Model_Repository_Experiment_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_Experiment_Interface
{
	
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	public function retrieveAll()
	{
		return $this->findAll();
	}

	public function retrieveAllWithEventCountByUser(Model_User $user)
	{
		$query = $this->_em->createQuery("SELECT e, COUNT(ev) eventCount FROM Model_Experiment e LEFT JOIN e.events ev JOIN e.userBindings b WHERE b.user = :user GROUP BY e.id");
		$query->setParameter('user', $user);
		return $query->getResult();
	}

}