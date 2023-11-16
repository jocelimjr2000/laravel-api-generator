<?php
/**
 * https://gist.github.com/sallar/5257396
 */

require(__DIR__ . '/../vendor/autoload.php');

use JocelimJr\LaravelApiGenerator\Helpers\Console;

// ::log method usage
// -------------------------------------------------------
//Console::log('Im Red!', 'red');
//Console::log('Im Blue on White!', 'white', true, 'blue');
//Console::log('I dont have an EOF', false);
//Console::log("\tThis is where I come in.", 'light_green');
//Console::log('You can swap my variables', 'black', 'yellow');
//Console::log(str_repeat('-', 60));

// Direct usage
// -------------------------------------------------------
//Console::blue('Blue Text') . "\n";
//Console::black('Black Text on Magenta Background', 'magenta') . "\n";
//Console::red('Im supposed to be red, but Im reversed!', 'reverse') . "\n";
//Console::red('I have an underline', 'underline') . "\n";
//Console::blue('I should be blue on light gray but Im reversed too.', 'light_gray', 'reverse') . "\n";

// Ding!
// -------------------------------------------------------
//echo Console::bell();

Console::log(['Message1', 'Message2'], 'black', true, 'red');
