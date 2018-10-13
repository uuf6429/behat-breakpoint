<?php

require_once __DIR__ . '/../vendor/autoload.php';

use phpDocumentor\Reflection;
use uuf6429\BehatBreakpoint\Breakpoint;
use uuf6429\BehatBreakpoint\Context;

$YEL = "\e[33m";
$DEF = "\e[0m";
$CLS = "\e[H\e[J";

echo $CLS . $YEL;

echo "\n{$YEL}<!-- src/docgen.php -->\n";

echo "\n### In Code\n";
echo "\nConstruct the desired breakpoint from the ones listed below and `trigger()` it.\n\n";

$factory = Reflection\DocBlockFactory::createInstance();
foreach (
    [
        Breakpoint\AlertBreakpoint::class,
        Breakpoint\ConsoleBreakpoint::class,
        Breakpoint\PopupBreakpoint::class,
        Breakpoint\XdebugBreakpoint::class,
    ]
    as $className
) {
    $class = new ReflectionClass($className);
    $classDoc = $factory->create($class->getDocComment() ?: '/***/');

    echo "- **[{$class->getShortName()}](src/Breakpoint/{$class->getShortName()})** - *{$classDoc->getSummary()}*\n";
    echo "  ```php\n";
    echo "  new \\$className(";

    $ctorDoc = null;
    $parameters = [];
    if ($class->hasMethod('__construct')) {
        $ctor = $class->getMethod('__construct');
        $ctorDoc = $factory->create($ctor->getDocComment() ?: '/***/');
        $parameters = $ctor->getParameters();
        /** @var Reflection\DocBlock\Tags\Param[] $paramsDoc */
        $paramsDoc = $ctorDoc->getTagsByName('param');
    }

    if ($parameters) {
        echo "\n  ";
    }
    foreach ($parameters as $i => $parameter) {
        echo "    \${$parameter->getName()}";
        if ($parameter->isOptional()) {
            echo ' = ' . var_export($parameter->getDefaultValue(), true);
        }
        if ($i < count($parameters) - 1) {
            echo ',';
        }
        echo '    // ';
        if ($parameter->isOptional()) {
            echo '(Optional) ';
        }

        echo (isset($paramsDoc[$i]) && ($desc = $paramsDoc[$i]->getDescription())) ? $desc : 'Undocumented';

        echo "\n  ";
    }
    echo ")\n";
    echo "  ```\n";
}

echo "\n### In Gherkin\n";
echo "\nFirst add the context to your behat project config (`behat.yml`) and then use any of the following steps in your feature files:\n";

$class = new ReflectionClass(Context::class);
foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    $methodDoc = $factory->create($method->getDocComment() ?: '/***/');
    foreach (['Given', 'When', 'Then'] as $defKey) {
        foreach ($methodDoc->getTagsByName($defKey) as $stepDef) {
            if ($stepDef instanceof Reflection\DocBlock\Tags\BaseTag && ($desc = substr($stepDef->getDescription(), 2, -2))) {
                echo "- {$methodDoc->getSummary()}\n";
                echo "  ```gherkin\n";
                echo "  {$defKey} {$desc}\n";
                echo "  ```\n";
            }
        }
    }
}

echo "\n<!-- /src/docgen.php -->\n";

echo "{$DEF}\n";
