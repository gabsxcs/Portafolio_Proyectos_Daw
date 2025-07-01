<?php
// Configuració del CLI de Doctrine.
// Dependencia de l'objecte ConsoleRunner
use Doctrine\ORM\Tools\Console\ConsoleRunner;
// Incluem el bootstrap per obtenir l' "Entity Manager"
require_once __DIR__.'/../src/bootstrap.php';
// Retornem l'objecte HelperSet de consola
return ConsoleRunner::createHelperSet($entityManager);