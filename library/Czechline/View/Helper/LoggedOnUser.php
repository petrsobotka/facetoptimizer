<?php

/**
 * Tento helper se pouziva v adminu. Zajistuje pouze vytazeni uzivatelskeho jmena aktualne 
 * prihlaseneho uzivatele ze session (Zend_Auth) a pak vytazeni celelho objektu prislusneho 
 * uzivatele z DB, abychonm s nim mohli pracovat v sablone.¨
 * 
 * TODO: mozna by to chtelo prepsat jako lazy load. To znamena mit vnitrni promennou,
 * do ktere by se User ulozil a pri dalsim volani uz by se nesahalo do DB. Jeste lepsi 
 * by bylo udelat statickou metodu Model_Service_User::getLogedOnUser(), ktera by resila tuto funkcinalitu
 * a v tomto helperu bychom akorat volali tu service....
 * 
 * @author Petr Sobotka
 *
 */
class Czechline_View_Helper_LoggedOnUser
{
	
	public function loggedOnUser()
	{
		return Czechline_LoggedOnUser::getUser();
    }
}