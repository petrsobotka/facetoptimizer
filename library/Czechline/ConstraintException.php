<?php 

/**
 * Výjimka, jejíž sémantika znamená, že byl porušen nějaký constraint Modelu.
 */
class Czechline_ConstraintException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}