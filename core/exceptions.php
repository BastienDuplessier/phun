<?php


/**
 *  Custom exception library
 *  
 *  @author Xavier Van de Woestyne
 *  @version 1.0
 *  @licence MIT License
 */


class CustomException extends Exception {
    public function __construct($trace, $code = 0, Exception $e = null) {
        parent::__construct($trace, $code, $e);
    }
    public function __toString() {
        return '<h1>'.get_class($this) . ": [{$this->code}]</h1> {$this->message}\n";
    }
}

class TypeUndefinedException extends CustomException {}
class ParametersMustBeUniq extends CustomException {}
class PathIsNotUniq extends CustomException {}
class NoPotentialService extends CustomException {}
class UnCallableProperty extends CustomException {}
class UnLinkableService extends CustomException {}
class NotAllParameters extends CustomException {}
class UnbindedService extends CustomException {}
?>