<?php 

class Model_Repository_UserBinding_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_UserBinding_Interface
{

	public function retrieveByExperimentAndUser(Model_Experiment $experiment, Model_User $user)
	{
		$query = $this->_em->createQuery("SELECT b FROM Model_UserBinding b WHERE b.user = :user AND b.experiment = :experiment");
		$query->setParameter('user', $user);
		$query->setParameter('experiment', $experiment);
		
		try {
			$b = $query->getSingleResult();
		} catch( \Doctrine\ORM\NoResultException $e){
			$b = null;
		}
		return $b;
	}
}