<?php
require_once 'vendor/autoload.php';
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    protected $docs = [
        'docs/Stub.md' => 'Codeception\Stub',
        'docs/Expected.md' => 'Codeception\Stub\Expected',
        'docs/StubTrait.md' => 'Codeception\Test\Feature\Stub',
    ];

    public function docs()
    {
        foreach ($this->docs as $file => $class) {
            if (!class_exists($class, true) && !trait_exists($class, true)) {
                throw new Exception('ups');
            }
            $this->say("Here goes, $class");
            $this->taskGenDoc($file)
                ->docClass($class)
                ->filterMethods(function(\ReflectionMethod $method) {
                    if ($method->isConstructor() or $method->isDestructor()) return false;
                    if (!$method->isPublic()) return false;
                    if (strpos($method->name, '_') === 0) return false;
                    if (strpos($method->name, 'stub') === 0) return false;
                    return true;
                })
                ->processMethodDocBlock(
                    function (\ReflectionMethod $m, $doc) {
                        $doc = str_replace(array('@since'), array(' * available since version'), $doc);
                        $doc = str_replace(array(' @', "\n@"), array("  * ", "\n * "), $doc);
                        return $doc;
                    })
                ->processProperty(false)
                ->run();
        }
    }
}