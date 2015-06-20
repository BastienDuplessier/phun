<?php
namespace Url;
/**
 *  A Small library for URL manipulation
 *  Url is a low-level library to provide a correct URL subsitution.
 *  
 *  @author Xavier Van de Woestyne
 *  @version 1.0
 *  @licence MIT License
 */

/**
 * Split the current URI
 * @return an array with each member of an URL
 */
function tokenize() {
    $arr = preg_split('/\/|\?|\&/', $_SERVER['REQUEST_URI']);
        $res = array_filter($arr, function($e) {
            return strlen($e) > 0;
        });
        return array_values($res);
    }

/**
 * Split the URI and extract the "basename"
 * @return an array with each member of an URL
 */
function tokenizeUnbase() {
    $unbased  = tokenize();
    $tokens = explode('/',\Configuration\URL);
    foreach($tokens as $i => $token) {
        if ($unbased[$i] == $token) array_shift($unbased);
        else break;
    }
    return $unbased;
}

    /**
     * Clean the temp parameters
     */
function cleanBuffer() {
    $_SESSION['get_args'] = [];
    $_SESSION['post_args'] = [];
}

/**
 * Check if the HTTP request has a correspondance with a passed Service
 * @param $service: the service needed for the verification
 * @return true if it was a concordance, false else
 */
function concordingWith(\Service $service) {
    cleanBuffer();
    $uri  = tokenizeUnbase();
    $path = $service->getPath();
        $len  = count($path);
        $part = array_slice($uri, 0, $len);
        if ($part != $path) {
            return false;
        }
        $rest = array_slice($uri, $len, count($uri)-$len);
        return accordingParamsWith($service, $rest)
            && postIsValid($service); 
}

/**
 * Check if the POST part of the request has a correspondance
 * @param $service: the service needed for the verification
     * @return true if it was a concordance, false else
     */
function postIsValid(\Service $s) {
    $params = $s->postArgs();
    foreach($params as $param) {
        if (!$param->accordingWith()) {
                Url::cleanBuffer();
                return false;
        }
    }
    return true;
}

/**
 * Check if the GET part of the request has a correspondance
 * @param $service: the service needed for the verification
 * @return true if it was a concordance, false else
 */
function accordingParamsWith(\Service $s, $rest) {
    $params = $s->getArgs();
    if (count($params) != count($rest)) return false;
    foreach($params as $i => $param) {
        if (!$param->accordingWith($rest[$i])){
            cleanBuffer();
            return false;
        }
    }
        return true;
}    
?>