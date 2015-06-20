<?php
/**
 *  Provide a service description
 *  
 *  @author Xavier Van de Woestyne
 *  @version 1.0
 *  @licence MIT License
 */

class Service {

    
    public static $storage = [];
    protected $uid;
    protected $path;
    protected $arguments;
    protected $controller;
    protected $type_mime;
    
    /**
     * Find the first corresponding service
     * @return a corresponding service
     * @throws NoPotentialService if no service founded
     */
    public static function findCurrent() {
        $current = null;
        foreach(Service::$storage as $service) {
            if ($service->isPotentialCandidate()) {
                return $service;
            }
        }
        throw new NoPotentialService("No service are candidate");
    }

    /**
     * Apply toString on an array (and sort the array)
     * 
     */
    protected static function mapToString($args) {
        $args = array_map(function($e) {
            return (string) $e;
        }, $args);
        sort($args, SORT_STRING);
        return $args;
    }

    /**
     * Create a Hashcode for a service
     * @param a path and the arguments of the Get Request
     */
    public static function makeUid($path, $args) {
        $args = join(Service::mapToString($args));
        $path = join($path, '/');
        return $path . $args;
        
    }

    /**
     * Check if Arguments seems correct
     * @param Args list 
     * @return true or false
     * @throws ParametersMustBeUniq if we have clone
     */
    public static function checkArguments($args) {
        $array = Service::mapToString($args);
        if (count($array) != count(array_unique($array)))
            throw new ParametersMustBeUniq("Parameters must have uniq name");
    }

    /**
     * Check if a service is uniq (potentially :P)
     * @param Service 's Hashcode
     * @return true or false
     * @throws PathIsNotUniq if we have a same path for another Service
     */
    public static function checkUnicity($uid) {
        if (array_key_exists($uid, Service::$storage)) {
            throw new PathIsNotUniq("Path must be uniq");
        }
    }

    /**
     * Constructor of a Service
     * @param the path (["a", "b"] for a/b)
     * @param args : a list of argument
     * @param type mime : the type of the returned document
     * @see parameters.php
     */
    public function __construct($path = [], $args = [], $mime = NULL) {
        $this->path = $path;
        Service::checkArguments($args);
        $this->arguments = $args;
        $this->uid = Service::makeUid($path, $args);
        Service::checkUnicity($this->uid);
        $this->type_mime = $mime;
        Service::$storage[$this->uid] = $this;
    }

    /**
     * Get all of GET parameters
     * @return a list of GET parameters
     */
    public function getArgs() {
        return array_filter($this->arguments, function($e){
            return $e->color() == "GET";
        });
    }
    
    /**
     * Get all of POST parameters
     * @return a list of POST parameters
     */
    public function postArgs() {
        return array_filter($this->arguments, function($e){
            return $e->color() == "POST";
        });
    }

    /**
     * Check if a service is linkable
     * @return true or false
     */
    public function isLinkable() {
        return count($this->postArgs()) == 0;
    }
    
    /**
     * Check if gived parameters according with the service
     * @param a list of GET parameters [name => value]
     * @return true or false
     * @throws NotAllParameters if the parameters list is not correct
     */
    protected function checkParams($params) {
        if (count($params) != count($this->getArgs())) {
            throw new NotAllParameters(
                "The number of given parameters is not good"
            );
        }
        foreach ($this->getArgs() as $param) {
            if($params[$param->getName()] === null) {
              throw new NotAllParameters(
                $param->getName()." is missing"
              ); 
            }
            $param->checkTypeOf($params[$param->getName()]);
        }
    }

    /**
     * Return a link of this service
     * @param the content of the link
     * @param the GET parameters of the service [name => value]
     * @param a list of HTML attributes ([attribute => value])
     * @return An HTML representation of the link
     * @throws Unlinkableservice if the service is not linkable
     */
    public function link($content, $params = [], $args = []) {
        if (!$this->isLinkable()) {
            throw new UnLinkableService("this service is not linkable");
        }
        $this->checkParams($params);
        $args["href"] = $this->uri($params);
        $attributes = $this->makeHtmlAttributes($args);
        return Html5\a($args, $content);
    }
    
    // Build the HTML attributes of the link
    protected function makeHtmlAttributes($attr) {
        $result = [];
        foreach($attr as $key => $value) {
            $result[] = $key.'="'.$value.'"';
        }
        return join($result, ' ');
    }

    /**
     * Return the uri of a service
     * @param the list of Get values
     * @return a string containing the URI
     */
    public function uri($values = []) {
        $result = $this->getPath();
        foreach ($this->getArgs() as $param) {
            $current = $values[$param->getName()];
            $result[] = ($param->isSuffixed()) ? $current :
                      $param->getName()."=".$current;
        }
        return '/'.Configuration\URL.'/'.join($result, '/');
    }

    /**
     * @return the UID of the service
     */
    public function getUid() {
        return $this->uid;
    }
    
    /**
     * @return the path of the service
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Link a view (a callback with function($get, $post) {}) to the service
     * @param the callback of the view (a lambda who take get and post as 
     *        arguments)
     */
    public function bindWith($callback) {
        $this->controller = $callback;
    }

    /**
     * Check if the service is a candidate for being running
     * @return true or false
     */
    public function isPotentialCandidate(){
        return Url\concordingWith($this);
    }

    /**
     * Write a formlet based on the current service
     * @param Associative array [name => html str]
     * @param Method ("GET" or "POST")
     * @return form as an HTML string
     */
    public function formlet($elts, $method = "POST") {
        $result = "<form method='$method' action='".$this->uri()."'>";
        foreach ($elts as $key => $value) {
            
        }
    }

    /**
     * execute the view of the service
     */
    public function render() {
        if ($this->controller === NULL) {
            throw new UnbindedService("The service is not bound to a view");
        }
        if ($this->type_mime !== NULL && is_string($this->type_mime)) {
            header("Content-Type: ".$this->type_mime);
        }
        return $this->controller->__invoke(
            $_SESSION['get_args'], $_SESSION['post_args']
        );

    }
}

?>