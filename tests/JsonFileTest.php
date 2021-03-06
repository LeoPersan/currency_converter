<?php

namespace Test;

use App\Exceptions\FileNotFoundException;
use App\Quotations\JsonFile;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TypeError;

/**
 * @covers \App\Quotations\JsonFile
 */
class JsonFileTest extends TestCase
{
    public function testLoadQuotations()
    {
        $json_file = new JsonFile;
        $json_file->loadQuotations(__DIR__ . '/currencies.json');
        $this->assertEquals(
            json_decode(file_get_contents(__DIR__ . '/currencies.json')),
            $json_file->getQuotations()
        );
        $this->assertEquals(
            ['USD', 'BRL', 'EUR'],
            $json_file->getValidCurrencies()
        );
    }

    public function testParseQuotations()
    {
		$reflector = new ReflectionClass(JsonFile::class);
		$method = $reflector->getMethod('parseQuotations');
		$method->setAccessible(true);
        $json_file = new JsonFile;
        $this->assertEquals(
            json_decode(file_get_contents(__DIR__ . '/currencies.json')),
            $method->invoke($json_file, __DIR__ . '/currencies.json')
        );
    }

    public function testParseQuotation()
    {
		$reflector = new ReflectionClass(JsonFile::class);
		$method = $reflector->getMethod('parseQuotation');
		$method->setAccessible(true);
        $json_file = new JsonFile;
        $this->assertEquals(
            json_decode(file_get_contents(__DIR__ . '/currencies.json'))[0],
            $method->invoke($json_file, json_decode(file_get_contents(__DIR__ . '/currencies.json'))[0])
        );
    }

    /**
     * @covers \App\Exceptions\FileNotFoundException
     */
    public function testLoadQuotationsFileNotExists()
    {
        $json_file = new JsonFile;
        $this->expectException(FileNotFoundException::class);
        $json_file->loadQuotations(__DIR__ . '/currencies2.json');
    }

    public function testLoadQuotationsArray()
    {
        $json_file = new JsonFile;
        $this->expectException(TypeError::class);
        $json_file->loadQuotations([]);
    }
}
