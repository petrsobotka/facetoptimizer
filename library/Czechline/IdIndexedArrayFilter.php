<?php 

/**
 * Tahle třída má za úkol projít pole objektů a udělat z nich ploché pole indexované podle
 * ID objektu. Volitelně se v prvku pole nechá buď celý původní objekt, nebo jen řetězec se jménem. 
 * Objekty tedy musí implementovat metodu getName(). Interface nevyžaduji, protože bych to musel doplnit i do 
 * entit a to se mi nechce. 
 * @author Petr Sobotka
 *
 */
class Czechline_IdIndexedArrayFilter
{

	/**
	 * Pro předané pole objektů vrátí pole indexované podle ID obktů.
	 * Objekty musí implementovat metody getId() a getName().
	 * @param array $input 
	 * @param bool $flatten Zda mají být prvkem pole celé objekty (false), nebo zda má dojít ke zploštění (true). 
	 */
	public static function filter(array $input, $flatten = false)
	{
    	$result = array();
    	foreach($input as $object)
    	{
    		if($flatten)
    			$result[$object->getId()] = $object->getName();
    		else
    			$result[$object->getId()] = $object;
    	}
    	
    	return $result;
	}

}