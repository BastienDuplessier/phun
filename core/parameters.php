<?php

/**
 * Parameters representation
 *  
 *  @author Xavier Van de Woestyne
 *  @version 1.0
 *  @licence MIT License
 */


/**
 * Description of an abstract parameter (with a type and a name)
 */
abstract class Parameter{
    
    protected $name;
    protected $type;
    
    
    abstract public function __toString();
    abstract public function color();

    // List of available types
    public static $types = [
        "int",
        "float",
        "string",
        "char",
        "bool"
    ];

    /**
     * Return a Get Parameter
     * @return a GETParameter Object
     * @param name the name of the parameter
     * @param type the type of the parameter
     * @param suffix, a flatten GET style
     */
    public static function get($name, $type, $suffix = false) {
        return new GETParameter($name, $type, $suffix);
    }

    /**
     * same of get but for post attributes
     * @see get
     */
    public static function post($name, $type) {
        return new POSTParamter($name, $type);
    }

    /**
     * build an abstract Parameter
     */
    public function __construct($name, $type) {
        $this->name = $name;
        $this->setType($type);
    }

    /**
     * Set a type to the parameter
     * @throws TypeUndefinedException if the type is not correct
     */
    public function setType($type) {
        if (!in_array($type, Parameter::$types, true))
            throw new TypeUndefinedException("Undefined type $type");
        $this->type = $type;
    }

    /**
     * @return the name of the parameter
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @return the type of the parameter
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return the suffix state of the parameter
     */
    public function isSuffixed(){
        return false;
    }

    /**
     * Check if a data is according with a Parameter
     * @param the data
     * @return true or false
     */
    public function accordingWith($data) {
        if ($this->type == 'string') return $data;
        if ($this->type == 'char') return (strlen($data) == 1) ? $data : false;
        switch($this->type){
        case 'int': $filter = FILTER_VALIDATE_INT; break;
        case 'float': $filter = FILTER_VALIDATE_FLOAT; break;
        case 'bool': $filter = FILTER_VALIDATE_BOOLEAN; break;
        }
        return filter_var($data, $filter);
    }

    /**
     * Check if a data is well typed
     * @param the data
     * @throws NotAllparameters if the type is not correct
     */
    public function checkTypeOf($data) {
        switch($this->type){
        case 'int':
            if (!is_int($data)){
                throw new NotAllParameters(
                    "$this->name is not an int");
            }
            break;
        case 'float':
            if (!is_float($data)){
                throw new NotAllParameters(
                    "$this->name is not a float");
            }
            break;
        case 'string':
            if (!is_string($data)){
                throw new NotAllParameters(
                    "$this->name is not a string");
            }
            break;
        case 'char':
            if (!is_string($data) && strlen($data) != 1){
                throw new NotAllParameters(
                    "$this->name is not a char");
            }
            break;
        case 'bool':
            if (is_bool($data) === false){
                throw new NotAllParameters(
                    "$this->name is not a boolean");
            }
            break;
        }
    }
    
}

// A concrete Get Parameter description
class GETParameter extends Parameter {
    
    protected $suffixed;

    // Create a GET Parameter
    public function __construct($name, $type, $suffixed = false) {
        parent::__construct($name, $type);
        $this->suffixed = $suffixed;
    }

    /**
     * @see Parameter
     */
    public function isSuffixed() {
        return $this->suffixed;
    }
    
     /**
     * Provide a String representation of the parameter
     */
    public function __toString() {
        return "[GET:$this->name]";
    }

    /**
     * An easy acces to the type of the parameter
     */
    public function color() {
        return "GET";
    }

    /**
     * @see Parameter
     */
    public function accordingWith($data) {
        if (!$this->isSuffixed()) {
            $s = explode('=', $data);
            if (count($s) != 2 || $s[0] != $this->getName())
                return false;
            $data = $s[1];
        }
        $result = parent::accordingWith($data);
        if ($result !== false) {
            $_SESSION['get_args'][$this->name] = $result;
            return true;
        }
        return false;
    }
    
}


// A concrete POST parameter description
class POSTParamter extends Parameter {

    /**
     * @see GETParameter
     */
    public function __toString() {
        return "[POST:$this->type($this->name)]";
    }
    
    /**
     * @see GETParameter
     */
    public function color() {
        return "POST";
    }

    /**
     * @see Parameter
     */
    public function accordingWith($data = 0) {
        if($_POST[$this->name] === NULL){
            return false;
        }
        $result = parent::accordingWith($_POST[$this->name]);
        if ($result !== false) {
            $_SESSION['post_args'][$this->name] = $result;
            return true;
        }
        return false;
    }
}
?>