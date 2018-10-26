<?php

require_once __DIR__ . '/../vendor/autoload.php';

use phpDocumentor\Reflection;
use uuf6429\BehatBreakpoint\Breakpoint;
use uuf6429\BehatBreakpoint\Context;

ob_start();

echo "\n";
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

    echo "- **[{$class->getShortName()}](src/Breakpoint/{$class->getShortName()}.php)** - *{$classDoc->getSummary()}*\n";
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
    $table = [];
    foreach ($parameters as $i => $parameter) {
        if ((isset($paramsDoc[$i]) && !($type = $paramsDoc[$i]->getType())) && !($type = $parameter->getType())) {
            $type = 'mixed';
        }
        $key = "    {$type} \${$parameter->getName()}";
        if ($parameter->isOptional()) {
            $key .= ' = ' . var_export($parameter->getDefaultValue(), true);
        }
        if ($i < count($parameters) - 1) {
            $key .= ',';
        }

        $val = '    // ';
        if ($parameter->isOptional()) {
            $val .= '(Optional) ';
        }
        $val .= (isset($paramsDoc[$i]) && ($desc = $paramsDoc[$i]->getDescription())) ? $desc : 'Undocumented';
        $val .= "\n  ";

        $table[$key] = $val;
    }
    $maxKeyLen = count($table) ? max(array_map('strlen', array_keys($table))) : 0;
    foreach ($table as $key => $val) {
        echo str_pad($key, $maxKeyLen) . $val;
    }
    echo ")\n";
    echo "  ```\n";
}

echo "\n### In Gherkin\n";
echo "\nFirst add the context to your behat project config (`behat.yml`) and then use any of the following steps in your feature files:\n";

$class = new ReflectionClass(Context::class);
foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    $methodDoc = $factory->create($method->getDocComment() ?: '/***/');
    $exampleDocs = $methodDoc->getTagsByName('example');
    $stepDefinitions = array_map(
        function (Reflection\DocBlock\Tags\BaseTag $stepDefinition) {
            return substr($stepDefinition->getDescription(), 2, -2);
        },
        array_merge(...array_map([$methodDoc, 'getTagsByName'], ['Given', 'When', 'Then']))
    );

    if (!count($stepDefinitions)) {
        continue;
    }

    echo sprintf("- %s. Definition(s):\n", $methodDoc->getSummary() ?: '_No Description_');
    echo "  ```gherkin\n";
    foreach (array_unique($stepDefinitions) as $stepDefinition) {
        echo "  Given {$stepDefinition}\n";
        echo "  Then {$stepDefinition}\n";
        echo "  When {$stepDefinition}\n";
    }
    echo "  ```\n";
    if (count($exampleDocs)) {
        echo "  Example(s):\n";
    }
    /** @var Reflection\DocBlock\Tags\Generic $exampleDoc */
    foreach ($exampleDocs as $exampleDoc) {
        echo "  ```gherkin\n";
        echo '  ' . str_replace("\n", "\n  ", $exampleDoc->getDescription()) . "\n";
        echo "  ```\n";
    }
}

echo "\n";

$content = ob_get_clean();
$startTag = '<!-- src/docgen.php -->';
$endTag = '<!-- /src/docgen.php -->';
$readmeFile = __DIR__ . '/../README.md';

file_put_contents($readmeFile,
    preg_replace(
        sprintf('/%s.+%s/s', preg_quote($startTag, '/'), preg_quote($endTag, '/')),
        "{$startTag}{$content}{$endTag}",
        file_get_contents($readmeFile)
    )
);
