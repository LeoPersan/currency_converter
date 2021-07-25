<?php

namespace Test;

use App\Converters\Converter;
use App\Quotations\QuotationInterface;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TypeError;

/**
 * @covers \App\Converters\Converter
 */
class ConverterTest extends TestCase
{
    public function getQuotations()
    {
        return [
            (object) [
                'from' => 'EUR',
                'to' => 'USD',
                'quotation' => 1.18,
            ],
            (object) [
                'from' => 'USD',
                'to' => 'BRL',
                'quotation' => 5.65,
            ],
        ];
    }

    public function testLoadQuotations()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
		$reflector = new ReflectionClass(Converter::class);
		$property = $reflector->getProperty('quotation_parser');
		$property->setAccessible(true);
        $this->assertEquals($quotation_parser, $property->getValue($converter));
    }

    public function testLoadQuotationsException()
    {
        $converter = new Converter;
        $this->expectException(TypeError::class);
        $converter->loadQuotations((object)[]);
    }

    public function testRunConversionZero()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $quotation_parser->method('getQuotations')->willReturn($this->getQuotations());
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->assertEquals(0, $converter->run('USD', 'BRL', 0));
    }

    public function testRunDirectConversion()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $quotation_parser->method('getQuotations')->willReturn($this->getQuotations());
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->assertEquals(11.3, $converter->run('USD', 'BRL', 2));
    }

    public function testRunInvertedConversion()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $quotation_parser->method('getQuotations')->willReturn($this->getQuotations());
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->assertEquals(2, $converter->run('BRL', 'USD', 11.3));
    }

    public function testRunIndirectConversion()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $quotation_parser->method('getQuotations')->willReturn($this->getQuotations());
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->assertEquals(6.667, $converter->run('EUR', 'BRL', 1));
        $this->assertEquals(1, $converter->run('BRL', 'EUR', 6.667));
    }

    public function testRunUknownFromCurrency()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->expectException(InvalidArgumentException::class);
        $converter->run('BTC', 'EUR', 1);
    }

    public function testRunUknownToCurrency()
    {
        $quotation_parser = $this->createMock(QuotationInterface::class);
        $quotation_parser->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $converter = new Converter;
        $converter->loadQuotations($quotation_parser);
        $this->expectException(InvalidArgumentException::class);
        $converter->run('EUR', 'BTC', 1);
    }

    public function testRunWithoutQuotationParser()
    {
        $converter = new Converter;
        $this->expectException(Exception::class);
        $converter->run('EUR', 'BTC', 1);
    }

    public function testRunWithArrayAsCurrency()
    {
        $converter = new Converter;
        $this->expectException(TypeError::class);
        $converter->run('EUR', [], 1);
    }

    public function testRunWithArrayAsCurrency2()
    {
        $converter = new Converter;
        $this->expectException(TypeError::class);
        $converter->run([], 'EUR', 1);
    }

    public function testRunWithStringAsAmount()
    {
        $converter = new Converter;
        $this->expectException(TypeError::class);
        $converter->run('USD', 'EUR', '');
    }
}
