<?php

namespace Test;

use App\Exceptions\FileNotFoundException;
use App\Quotations\TxtFile;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TypeError;

/**
 * @covers \App\Quotations\TxtFile
 * @covers \App\Quotations\JsonFile
 */
class TxtFileTest extends TestCase
{
    public function testLoadQuotations()
    {
        $json_file = new TxtFile;
        $json_file->loadQuotations(__DIR__ . '/currencies.txt');
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
		$reflector = new ReflectionClass(TxtFile::class);
		$method = $reflector->getMethod('parseQuotations');
		$method->setAccessible(true);
        $json_file = new TxtFile;
        $this->assertEquals(
            file(__DIR__ . '/currencies.txt'),
            $method->invoke($json_file, __DIR__ . '/currencies.txt')
        );
    }

    public function testParseQuotation()
    {
		$reflector = new ReflectionClass(TxtFile::class);
		$method = $reflector->getMethod('parseQuotation');
		$method->setAccessible(true);
        $json_file = new TxtFile;
        $this->assertEquals(
            (object) [
                'from' => 'USD',
                'to' => 'BRL',
                'quotation' => 5.65,
            ],
            $method->invoke($json_file, '1 USD = 5.65 BRL')
        );
    }

    /**
     * @covers \App\Exceptions\FileNotFoundException
     */
    public function testLoadQuotationsFileNotExists()
    {
        $json_file = new TxtFile;
        $this->expectException(FileNotFoundException::class);
        $json_file->loadQuotations(__DIR__ . '/currencies2.txt');
    }

    public function testLoadQuotationsArray()
    {
        $json_file = new TxtFile;
        $this->expectException(TypeError::class);
        $json_file->loadQuotations([]);
    }
}
