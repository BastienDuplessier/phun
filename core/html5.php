<?php
/**
 *  HTML5 helpers
 *  
 *  @author Xavier Van de Woestyne
 *  @version 2.0
 *  @licence MIT License
 */

namespace Html5;

class Balise {

    protected $attributes;
    protected $body;
    protected $extended;
    protected $tag;
    protected $prefix;

    public function __construct(
        $tag, $attr, $body, $extended = true, $pref=NULL) {
        $this->tag = $tag;
        $this->attributes = $attr;
        $this->body = (is_array($body)) ? $body : [$body];
        $this->extended = $extended;
        $this->prefix = $pref;
    }

    protected function isPCDATA($data) {
        return is_string($data)
            || is_numeric($data)
            || is_double($data)
            || is_bool($data)
            || is_integer($data)
            || is_real($data)
            || is_scalar($data)
            || is_float($data)
            || is_string($data);
    }

    protected function makeAttributes() {
        $result = "";
        foreach($this->attributes as $key => $value) {
            $result .=  ' '.$key.'="'.$value.'"';
        }
        return $result;
    }

    public function render() {
        $result   = ($this->prefix !== NULL) ? $this->prefix : "";
        $result  .= '<'.\strtolower($this->tag);
        $result .= $this->makeAttributes();
        if (!$this->extended) { return $result . '>';}
        $body = array_map(
            function($elt) {
                return ($this->isPCDATA($elt)) ? $elt : $elt->render();
            }, $this->body
        );
        $result .= '>'.array_reduce(
            $body, function ($acc, $elt) { return $acc .= $elt;}
        );
        $result .= '</'.\strtolower($this->tag).'>';
        return $result;
    }

    public function __toString() {
        return $this->render();
    }
    
}


function html($body){
	return new Balise('html', [], $body, true, '<!DOCTYPE html>');
}
function a($attr, $body){
	return new Balise('a', $attr, $body);
}
function abbr($attr, $body){
	return new Balise('abbr', $attr, $body);
}
function address($attr, $body){
	return new Balise('address', $attr, $body);
}
function area($attr, $body){
	return new Balise('area', $attr, $body, false);
}
function article($attr, $body){
	return new Balise('article', $attr, $body);
}
function aside($attr, $body){
	return new Balise('aside', $attr, $body);
}
function audio($attr, $body){
	return new Balise('audio', $attr, $body);
}
function b($attr, $body){
	return new Balise('b', $attr, $body);
}
function base($attr, $body){
	return new Balise('base', $attr, $body, false);
}
function bdi($attr, $body){
	return new Balise('bdi', $attr, $body);
}
function bdo($attr, $body){
	return new Balise('bdo', $attr, $body);
}
function blockquote($attr, $body){
	return new Balise('blockquote', $attr, $body);
}
function body($attr, $body){
	return new Balise('body', $attr, $body);
}
function br($attr, $body){
	return new Balise('br', $attr, $body, false);
}
function button($attr, $body){
	return new Balise('button', $attr, $body);
}
function canvas($attr, $body){
	return new Balise('canvas', $attr, $body);
}
function caption($attr, $body){
	return new Balise('caption', $attr, $body);
}
function cite($attr, $body){
	return new Balise('cite', $attr, $body);
}
function code($attr, $body){
	return new Balise('code', $attr, $body);
}
function col($attr, $body){
	return new Balise('col', $attr, $body, false);
}
function colgroup($attr, $body){
	return new Balise('colgroup', $attr, $body);
}
function command($attr, $body){
	return new Balise('command', $attr, $body, false);
}
function datalist($attr, $body){
	return new Balise('datalist', $attr, $body);
}
function dd($attr, $body){
	return new Balise('dd', $attr, $body);
}
function del($attr, $body){
	return new Balise('del', $attr, $body);
}
function details($attr, $body){
	return new Balise('details', $attr, $body);
}
function dfn($attr, $body){
	return new Balise('dfn', $attr, $body);
}
function div($attr, $body){
	return new Balise('div', $attr, $body);
}
function dl($attr, $body){
	return new Balise('dl', $attr, $body);
}
function dt($attr, $body){
	return new Balise('dt', $attr, $body);
}
function em($attr, $body){
	return new Balise('em', $attr, $body);
}
function embed($attr, $body){
	return new Balise('embed', $attr, $body, false);
}
function fieldset($attr, $body){
	return new Balise('fieldset', $attr, $body);
}
function figcaption($attr, $body){
	return new Balise('figcaption', $attr, $body);
}
function figure($attr, $body){
	return new Balise('figure', $attr, $body);
}
function footer($attr, $body){
	return new Balise('footer', $attr, $body);
}
function form($attr, $body){
	return new Balise('form', $attr, $body);
}
function h1($attr, $body){
	return new Balise('h1', $attr, $body);
}
function h2($attr, $body){
	return new Balise('h2', $attr, $body);
}
function h3($attr, $body){
	return new Balise('h3', $attr, $body);
}
function h4($attr, $body){
	return new Balise('h4', $attr, $body);
}
function h5($attr, $body){
	return new Balise('h5', $attr, $body);
}
function h6($attr, $body){
	return new Balise('h6', $attr, $body);
}
function head($body){
	return new Balise('head', [], $body);
}
function header($attr, $body){
	return new Balise('header', $attr, $body);
}
function hgroup($attr, $body){
	return new Balise('hgroup', $attr, $body);
}
function hr($attr, $body){
	return new Balise('hr', $attr, $body, false);
}
function i($attr, $body){
	return new Balise('i', $attr, $body);
}
function iframe($attr, $body){
	return new Balise('iframe', $attr, $body);
}
function img($attr, $body){
	return new Balise('img', $attr, $body, false);
}
function input($attr, $body){
	return new Balise('input', $attr, $body, false);
}
function ins($attr, $body){
	return new Balise('ins', $attr, $body);
}
function keygen($attr, $body){
	return new Balise('keygen', $attr, $body, false);
}
function kbd($attr, $body){
	return new Balise('kbd', $attr, $body);
}
function label($attr, $body){
	return new Balise('label', $attr, $body);
}
function legend($attr, $body){
	return new Balise('legend', $attr, $body);
}
function li($attr, $body){
	return new Balise('li', $attr, $body);
}
function link($attr, $body){
	return new Balise('link', $attr, $body, false);
}
function map($attr, $body){
	return new Balise('map', $attr, $body);
}
function mark($attr, $body){
	return new Balise('mark', $attr, $body);
}
function math($attr, $body){
	return new Balise('math', $attr, $body);
}
function menu($attr, $body){
	return new Balise('menu', $attr, $body);
}
function meta($attr, $body){
	return new Balise('meta', $attr, $body, false);
}
function meter($attr, $body){
	return new Balise('meter', $attr, $body);
}
function nav($attr, $body){
	return new Balise('nav', $attr, $body);
}
function noscript($attr, $body){
	return new Balise('noscript', $attr, $body);
}
function _object($attr, $body){
	return new Balise('object', $attr, $body);
}
function ol($attr, $body){
	return new Balise('ol', $attr, $body);
}
function optgroup($attr, $body){
	return new Balise('optgroup', $attr, $body);
}
function option($attr, $body){
	return new Balise('option', $attr, $body);
}
function output($attr, $body){
	return new Balise('output', $attr, $body);
}
function p($attr, $body){
	return new Balise('p', $attr, $body);
}
function param($attr, $body){
	return new Balise('param', $attr, $body, false);
}
function pre($attr, $body){
	return new Balise('pre', $attr, $body);
}
function progress($attr, $body){
	return new Balise('progress', $attr, $body);
}
function q($attr, $body){
	return new Balise('q', $attr, $body);
}
function rp($attr, $body){
	return new Balise('rp', $attr, $body);
}
function rt($attr, $body){
	return new Balise('rt', $attr, $body);
}
function ruby($attr, $body){
	return new Balise('ruby', $attr, $body);
}
function s($attr, $body){
	return new Balise('s', $attr, $body);
}
function samp($attr, $body){
	return new Balise('samp', $attr, $body);
}
function script($attr, $body){
	return new Balise('script', $attr, $body);
}
function section($attr, $body){
	return new Balise('section', $attr, $body);
}
function select($attr, $body){
	return new Balise('select', $attr, $body);
}
function small($attr, $body){
	return new Balise('small', $attr, $body);
}
function source($attr, $body){
	return new Balise('source', $attr, $body, false);
}
function span($attr, $body){
	return new Balise('span', $attr, $body);
}
function strong($attr, $body){
	return new Balise('strong', $attr, $body);
}
function style($attr, $body){
	return new Balise('style', $attr, $body);
}
function sub($attr, $body){
	return new Balise('sub', $attr, $body);
}
function summary($attr, $body){
	return new Balise('summary', $attr, $body);
}
function sup($attr, $body){
	return new Balise('sup', $attr, $body);
}
function svg($attr, $body){
	return new Balise('svg', $attr, $body);
}
function table($attr, $body){
	return new Balise('table', $attr, $body);
}
function tbody($attr, $body){
	return new Balise('tbody', $attr, $body);
}
function td($attr, $body){
	return new Balise('td', $attr, $body);
}
function textarea($attr, $body){
	return new Balise('textarea', $attr, $body);
}
function tfoot($attr, $body){
	return new Balise('tfoot', $attr, $body);
}
function th($attr, $body){
	return new Balise('th', $attr, $body);
}
function thead($attr, $body){
	return new Balise('thead', $attr, $body);
}
function time($attr, $body){
	return new Balise('time', $attr, $body);
}
function title($attr, $body){
	return new Balise('title', $attr, $body);
}
function tr($attr, $body){
	return new Balise('tr', $attr, $body);
}
function track($attr, $body){
	return new Balise('track', $attr, $body, false);
}
function u($attr, $body){
	return new Balise('u', $attr, $body);
}
function ul($attr, $body){
	return new Balise('ul', $attr, $body);
}
function _var($attr, $body){
	return new Balise('var', $attr, $body);
}
function video($attr, $body){
	return new Balise('video', $attr, $body);
}
function wbr($attr, $body){
	return new Balise('wbr', $attr, $body, false);
}
