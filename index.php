<?php
require 'configuration.php';
require 'core/phun.php';

$hello = new Service();

$hello->bindWith(
    function($get, $post) {
        echo '<h1>Hello les gens (et le world)!</h1>';
    }
);

Phun::start();
?>