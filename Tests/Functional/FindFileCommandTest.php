<?php
namespace Vilks\FileSearchBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Bundle\FrameworkBundle\Client;

class FindFileCommandTest extends WebTestCase
{
    public function testDefaultDoesNotInstall()
    {
        $client = self::createClient();
        $output = $this->runCommand($client, sprintf('find-file normal -p %s/Fixtures', __DIR__));

        $files = [
            'Go/1/o/multi_bytes.txt',
            'Go/1/o/normal.txt',
            'Go/1/normal.txt',
            'test/multi_bytes.txt'
        ];
        foreach ($files as $file) {
            $this->assertContains(sprintf('%s/Fixtures/%s', __DIR__, $file), $output);
        }

        $this->assertCount(4, explode(PHP_EOL, trim($output)));
    }

    public function runCommand(Client $client, $command)
    {
        $application = new Application(WebTestCase::createKernel());
        $application->setAutoExit(false);

        $fp = tmpfile();
        $input = new StringInput($command);
        $output = new StreamOutput($fp);

        $application->run($input, $output);

        fseek($fp, 0);
        $output = '';
        while (!feof($fp)) {
            $output = fread($fp, 4096);
        }
        fclose($fp);

        return $output;
    }
}
