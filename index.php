<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();
$hello_xavier = new Service(["hello", "Xavier"]);

$hello->bindWith(
    function($get, $post) use ($hello_xavier) {
        echo '<h1>Hello les gens (et le world)!</h1>';
        echo $hello_xavier->link(
            'Dire coucou à Xavier!',
            [],
            ["style" => "background-color:red;color:white;padding:8px;"] 
        );
    }
);

$hello_xavier->bindWith(
    function($get, $post) use ($hello) {
        echo '<h1>Hello Xavier!</h1>';
        echo $hello->link(
            'Dire coucou au monde!',
            [],
            ["style" => "background-color:green;color:white;padding:8px;"] 
        );
    }
);



Phun::start();
?>