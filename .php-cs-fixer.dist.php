<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/migrations')
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    '@Symfony:risky' => true,
    '@PHP70Migration' => true,
    '@PHP70Migration:risky' => true,
    '@PHP71Migration' => true,
    '@PHP71Migration:risky' => true,
    '@PHP73Migration' => true,
    '@PHP74Migration' => true,
    '@PHP74Migration:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHPUnit75Migration:risky' => true,
    '@PHPUnit84Migration:risky' => true,
    'declare_strict_types' => false,
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
