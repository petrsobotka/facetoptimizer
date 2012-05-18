<?php 

/**
 * Pomocná třída, která uchovává staticky instanci přihlášeného uživatele. 
 * Záměrně není proměnná s uživatelem typovaná, aby to šlo použít pro různé projekty.

 * @author Petr Sobotka
 *
 */
class Czechline_LoggedOnUser
{
	private static $user = null;
	
	public static function setUser($user)
	{
		self::$user = $user;
	}
	
	public static function getUser()
	{
		/*
		if(isset(self::$user))
			return self::$user;
		else
			throw new Exception("User has not been set.");
		*/
		return self::$user;
	}
	
	public static function clear()
	{
		self::$user = null;
	}
}