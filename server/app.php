<?php
/*
require the twig environment.
*/
require_once '../vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('../public');
$twig = new \Twig\Environment($loader);

/*this section is for config the template format*/
$lexer = new \Twig\Lexer($twig, [
    'tag_block' => ['{','}'],        //the defult is {%  %}
    'tag_variable' => ['{{$','}}']   //it's the defult config
]);
$twig->setLexer($lexer);

/*
after start the hosting by "php -S localhost:4000" in the '/ACC' file
open the 'http://localhost:4000/server/app.php'
*/
echo $twig->render('ssr.html', [
    'name' => 'farouk',
    'age'  => 18
]);

/*explain :
this app waitig for get req by 'http://localhost:4000/server/app.php'
after that get into the '/ACC/public/ssr.html' make change in the '{{  }}' field
and render the result
*/