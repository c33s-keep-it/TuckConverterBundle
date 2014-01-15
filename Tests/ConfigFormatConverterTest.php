<?php
namespace Tuck\ConverterBundle\Tests;

use SplFileInfo;
use Tuck\ConverterBundle\ConfigFormatConverter;
use Tuck\ConverterBundle\Dumper\DumperFactory;
use Tuck\ConverterBundle\Loader\LoaderFactory;

class ConfigFormatConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigFormatConverter
     */
    protected $converter;

    public function setup()
    {
        $this->converter = new ConfigFormatConverter(
            new LoaderFactory(),
            new DumperFactory()
        );
    }

    public function testConvertingXmlToYml()
    {
        $newConfig = $this->converter->convertFile($this->loadConfigFileMock('simple.xml'), 'yml');

        $this->assertStringEqualsFile(
            $this->loadConfigFileMock('simple.yml')->getRealPath(),
            $newConfig
        );
    }

    /**
     * @expectedException \Tuck\ConverterBundle\Exception\UnknownFileException
     */
    public function testGivingInvalidFileGivesError()
    {
        $this->converter->convertFile(new SplFileInfo('fakefile.xml'), 'yml');
    }

    /**
     * @expectedException \Tuck\ConverterBundle\Exception\UnknownFormatException
     */
    public function testInvalidFormatGivesError()
    {
        $this->converter->convertFile($this->loadConfigFileMock('simple.xml'), 'fakeformat');
    }

    protected function loadConfigFileMock($filename)
    {
        return new SplFileInfo(__DIR__.'/../Resources/mock_config/'.$filename);
    }
}
