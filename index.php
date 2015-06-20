<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_quelqun = new Service(
    ["hello"],
    [Parameter::get('prenom', 'string', true)]
);


use Html5 as D;

function page($body) {
    echo
        D\html(
            [
                D\head([
                    D\title([], ["Test"]),
                    D\meta(["charset" => "utf-8"], []),
                ]),
                D\body([], $body)
            ]);
}


$hello->bindWith(
    function($get, $post) use ($hello_quelqun) {
        page([ D\h1([], ["Salut le monde!"]),
               $hello_quelqun->link(
                   ["Dire coucou à Xavier"],
                    ["prenom" => "xavier"],
                   ["style" => "background-color:green;
                          color:white;
                          padding:8px;"
                   ])]);
    }
);

$hello_quelqun->bindWith(
    function($get, $post) use ($hello) {
        page([ D\h1([], ["Salut ".$get['prenom']]),
               $hello->link(
                   ["Dire coucou au monde"],
                   [],
                   ["style" => "background-color:red;
                          color:white;
                          padding:8px;"
                   ])]);
    }
);




Phun::start();
?>