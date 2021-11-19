<?php
require_once __DIR__.'/vendor/autoload.php';

class Robofile extends \Robo\Tasks
{
    public function release()
    {
        $this->test();

        $version = file_get_contents('VERSION');
        
        $this->docs();

        // create GitHub release
        $this->taskGitHubRelease($version)
            ->uri('Codeception/Specify')
            ->askDescription()
            ->run();
    }

    public function changed($description)
    {
        $this->taskChangelog()
            ->version(file_get_contents('VERSION'))
            ->change($description)
            ->run();
    }

    protected $docs = [
        'docs/GlobalConfig.md' => '\Codeception\Specify\Config',
        'docs/LocalConfig.md' => '\Codeception\Specify\ConfigBuilder',
    ];

    public function docs()
    {
        foreach ($this->docs as $file => $class) {
            class_exists($class, true);
            $this->taskGenDoc($file)
                ->docClass($class)
                ->processProperty(false)
                ->run();
        }
    }


    public function test()
    {
        $res = $this->taskPHPUnit()->run();
        if (!$res) exit;
    }
}
