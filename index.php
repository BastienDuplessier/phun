<?php
require 'configuration.php';
require 'core/phun.php';


// Home service
$hello = new Service(['hello']);
$hello_xavier = new Service(
    ['hello'], [Parameter::get('to', 'string', true)]
);

// Services linking
$hello->bindWith(function($get, $post) use($hello_xavier) {
    echo '<h1>Hello World!</h1>';
    echo $hello_xavier->link(
        'Dire bonjour à PHUN',
        ['to' => 'PHUN']
    );
});

$hello_xavier->bindWith(function($get, $post) use ($hello) {
    echo '<h1>Hello '.$get['to'].'!</h1>';
    echo $hello->link('Dire bonjour au monde');
});



Phun::start();
?>