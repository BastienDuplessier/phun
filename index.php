<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_quelqun = new Service(
    ["hello"],
    [Parameter::get('prenom', 'string', true)]
);

$hello->bindWith(
    function($get, $post) use ($hello_quelqun) {
        echo '<h1>Hello les gens (et le world)!</h1>';
        echo $hello_quelqun->link(
            'Dire coucou à Xavier!',
            ['prenom' => 'Xavier'],
            ["style" => "background-color:red;color:white;padding:8px;"] 
        );
    }
);

$hello_quelqun->bindWith(
    function($get, $post) use ($hello) {
       echo '<h1>Hello '.$get['prenom'].'!</h1>';
        echo $hello->link(
            'Dire coucou au monde!',
            [],
            ["style" => "background-color:green;color:white;padding:8px;"] 
        );
    }
);



Phun::start();
?>