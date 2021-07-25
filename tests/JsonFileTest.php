<?php

namespace Test;

use App\Exceptions\FileNotFoundException;
use App\Quotations\JsonFile;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \App\Quotations\JsonFile
 */
class JsonFileTest extends TestCase
{
    public function testLoadQuotations()
    {
        $converter = new JsonFile;
        $converter->loadQuotations(__DIR__ . '/currencies.json');
        $this->assertEquals(
            json_decode(file_get_contents(__DIR__ . '/currencies.json')),
            $converter->getQuotations()
        );
        $this->assertEquals(
            ['USD', 'BRL', 'EUR'],
            $converter->getValidCurrencies()
        );
    }

    /**
     * @covers \App\Exceptions\FileNotFoundException
     */
    public function testLoadQuotationsFileNotExists()
    {
        $converter = new JsonFile;
        $this->expectException(FileNotFoundException::class);
        $converter->loadQuotations(__DIR__ . '/currencies2.json');
    }

    public function testLoadQuotationsArray()
    {
        $converter = new JsonFile;
        $this->expectException(TypeError::class);
        $converter->loadQuotations([]);
    }
}
