<?php 

use DoctrineExtensions\NestedSet\MultipleRootNode;

interface Model_Service_NestedSet_Interface
{
	/**
	 * Vrátí kompletní strom pro předaný kořenový element. 
	 * @param MultipleRootNode $entity Entita modelu, která implementuje MultipleRootNode
	 * @param integer $depth
	 */
	public function fetchTreeByRoot( MultipleRootNode $entity, $depth );
	
	/**
	 * Z předané entity udělá nový kořen. 
	 * @param MultipleRootNode $entity Entita modelu, která implementuje MultipleRootNode
	 */
	public function createRoot( MultipleRootNode $entity );
	
	/**
	 * Metoda zajistí přidání entity jako potomka do jiné entity.
	 * @param MultipleRootNode $parent Rodičovská entita. 
	 * @param MultipleRootNode $child Entita nového potomka.
	 */
	public function addChild( MultipleRootNode $parent, MultipleRootNode $child);
	
	/**
	 * Metoda zajistí přesun předané entity na místo prvního potomka
	 * druhé předané entity.
	 * @param MultipleRootNode $entity Přesouvaná entita.
	 * @param MultipleRootNode $destination Entita, do níž se přesunuje.
	 */
	public function moveAsFirstChild( MultipleRootNode $entity, MultipleRootNode $destination );
	
	/**
	 * Metoda zajistí přesun dané entity jako následujícího souseda pod druhou
	 * předanou entitu.
	 * @param MultipleRootNode $entity Přesouvaná entita.
	 * @param MultipleRootNode $sibling Entita, pod níž se přesouvá.
	 */
	public function moveAsNextSibling( MultipleRootNode $entity, MultipleRootNode $sibling );
	
	/**
	 * Meotda zajistí oddstranění předané enity ze stromu.
	 * @param MultipleRootNode $entity Entita, která má být odstraněna.
	 */
	public function delete( MultipleRootNode $entity );
	
	/**
	 * Metoda vrátí pole předchůdců dané enity. 
	 * @param MultipleRootNode $entity
	 */
	public function getPathToEntity( MultipleRootNode $entity );
	
	/**
	 * Metoda obalí entitu do wrapperu, který poskytuje další metody např. pro navigaci stromem atd.
	 * @param MultipleRootNode $entity
	 */
	public function wrap( MultipleRootNode $entity );
}