<?php 

use DoctrineExtensions\NestedSet\Manager;
use DoctrineExtensions\NestedSet\Config;
use DoctrineExtensions\NestedSet\MultipleRootNode;

class Model_Service_NestedSet_Standard
	implements Model_Service_NestedSet_Interface
{
	/**
	 * EntityManager.
	 * @var \Doctrine\ORM\EntityManager $em
	 */
	protected $em;
	
	/**
	 * Manager nested setu - pomocná třída.
	 * @var \DoctrineExtensions\NestedSet\Manager
	 */
	protected $manager;
	
	/**
	 * Vytvoří service pro manipulaci s nested set objekty určité třídy. 
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 * @param string $entityClass String s názvem třídy, se kterou chceme pracovat. 
	 */
	public function __construct( \Doctrine\ORM\EntityManager $entityManager, $entityClass )
	{
		$this->em = $entityManager;
		
		// inicializace managera pro manipulaci s uzly kategorií
		$config = new Config($this->em, $entityClass);
		$this->manager = new Manager($config);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::fetchTreeByRoot()
	 */
	public function fetchTreeByRoot( MultipleRootNode $entity, $depth = null )
	{
		return $this->manager->fetchTree($entity->getRootValue(), $depth);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::createRoot()
	 */
	public function createRoot( MultipleRootNode $entity )
	{
		$this->manager->createRoot($entity);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::addChild()
	 */
	public function addChild( MultipleRootNode $parent, MultipleRootNode $child)
	{
		$parentNode = $this->manager->wrapNode($parent);
		$parentNode->addChild($child);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::moveAsFirstChild()
	 */
	public function moveAsFirstChild( MultipleRootNode $entity, MultipleRootNode $destination )
	{
		$entityNode = $this->manager->wrapNode($entity);
		$destinationNode = $this->manager->wrapNode($destination);
		$entityNode->moveAsFirstChildOf($destinationNode);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::moveAsNextSibling()
	 */
	public function moveAsNextSibling( MultipleRootNode $entity, MultipleRootNode $sibling )
	{
		$entityNode = $this->manager->wrapNode($entity);
		$siblingNode = $this->manager->wrapNode($sibling);
		$entityNode->moveAsNextSiblingOf($siblingNode);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::delete()
	 */
	public function delete( MultipleRootNode $entity )
	{
		$entityNode = $this->manager->wrapNode($entity);
		if($entityNode->isLeaf())
			$entityNode->delete();
		else
			throw new Exception("Entity isn't empty, it can't be deleted.");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::getPathToEntity()
	 */
	public function getPathToEntity( MultipleRootNode $entity )
	{
		$entityNode = $this->manager->wrapNode($entity);
		
		return $entityNode->getAncestors();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see model/Service/NestedSet/Model_Service_NestedSet_Interface::wrap()
	 */
	public function wrap( MultipleRootNode $entity )
	{
		return $this->manager->wrapNode($entity);
	}
}