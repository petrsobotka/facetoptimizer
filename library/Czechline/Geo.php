<?php

/**
 * Třída zajišťující výpočet různých geografických údajů. 
 * Převzato především z následujících zdrojů:
 * 
 * http://php.vrana.cz/prace-se-zemepisnymi-souradnicemi.php
 * http://php.vrana.cz/vzdalenost-dvou-zemepisnych-bodu.php
 *
 */
class Czechline_Geo {
	
	/** Vzdálenost dvou zeměpisných bodů
	* @param float zeměpisná šířka prvního bodu ve stupních
	* @param float zeměpisná délka prvního bodu ve stupních
	* @param float zeměpisná šířka druhého bodu ve stupních
	* @param float zeměpisná délka druhého bodu ve stupních
	* @return float nejmenší vzdálenost bodů v kilometrech
	* @copyright Jakub Vrána, http://php.vrana.cz/
	*/
	public static function gps_distance($lat1, $lng1, $lat2, $lng2) {
	    static $great_circle_radius = 6372.795;
	    return acos(
	        cos(deg2rad($lat1))*cos(deg2rad($lng1))*cos(deg2rad($lat2))*cos(deg2rad($lng2))
	        + cos(deg2rad($lat1))*sin(deg2rad($lng1))*cos(deg2rad($lat2))*sin(deg2rad($lng2))
	        + sin(deg2rad($lat1))*sin(deg2rad($lat2))
	    ) * $great_circle_radius;
	}
	
	/** Převod desetinného čísla na stupně, minuty a vteřiny
	* @param float stupně vyjádřené desetinným číslem
	* @param string řetězec doplněný při kladné hodnotě (např. N nebo E)
	* @param string řetězec doplněný při záporné hodnotě (např. S nebo W)
	* @return string řetězec ve tvaru s°m'v.vvv"N
	* @copyright Jakub Vrána, http://php.vrana.cz/
	*/
	public static function float2gps($float, $positive, $negative) {
	    $x = abs($float);
	    $deg = floor($x);
	    $min_sec = ($x - $deg) * 60;
	    $min = floor($min_sec);
	    $sec = ($min_sec - $min) * 60;
	    return  $deg . "°$min'" . number_format($sec, 3) . '"' . ($float >= 0 ? $positive : $negative);
	}
	
	/** Převod zeměpisné šířky a délky v desetinném čísle na stupně, minuty a vteřiny
	* @param float zeměpisná šířka vyjádřená desetinným číslem
	* @param float zeměpisná délka vyjádřená desetinným číslem
	* @return string řetězec ve tvaru s°m'v.vvv"N, s°m'v.vvv"E
	* @copyright Jakub Vrána, http://php.vrana.cz/
	*/
	public static function floats2gps($lat, $lng) {
	    return self::float2gps($lat, "N", "S") . ", " . self::float2gps($lng, "E", "W");
	}
	
	/** Převod stupňů, minut a vteřin na desetinné číslo
	* @param int stupně
	* @param int minuty
	* @param float vteřiny
	* @return float stupně vyjádřené desetinným číslem
	* @copyright Jakub Vrána, http://php.vrana.cz/
	*/
	public static function gps2float($deg, $min, $sec = 0) {
	    return $deg + $min/60 + $sec/60/60;
	}
	
}