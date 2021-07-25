<?php

namespace Test;

use App\Amounts\AmountInterface;
use App\Converters\ConverterInterface;
use App\CurrencyConverter;
use App\Quotations\QuotationInterface;
use ArgumentCountError;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TypeError;

/**
 * @covers \App\CurrencyConverter
 */
class CurrencyConverterTest extends TestCase
{
    public function testConstruct()
    {
        $quotations = __DIR__.'/currencies.json';
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('run')->willReturn(5.65);
        $amount_interface = $this->createMock(AmountInterface::class);
        $amount_interface->method('setAmount')->willReturnSelf();
        $amount_interface->method('setCurrency')->willReturnSelf();

        $converter = new CurrencyConverter($quotation_interface, $converter_interface, $amount_interface, $quotations);

		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('converter');
		$property->setAccessible(true);
        $this->assertEquals($converter_interface, $property->getValue($converter));

		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('quotation_parser');
		$property->setAccessible(true);
        $this->assertEquals($quotation_interface, $property->getValue($converter));

		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('quotations');
		$property->setAccessible(true);
        $this->assertEquals($quotations, $property->getValue($converter));

        $this->assertEquals($amount_interface, $converter->getNewAmount());
        $this->assertEquals($amount_interface, $converter->getOldAmount());
    }

    public function testSetConverter()
    {
        $converter = new CurrencyConverter;
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter->setConverter($converter_interface);
		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('converter');
		$property->setAccessible(true);
        $this->assertEquals($converter_interface, $property->getValue($converter));
    }

    public function testSetConverterException()
    {
        $converter = new CurrencyConverter;
        $this->expectException(TypeError::class);
        $converter->setConverter((object)[]);
    }

    public function testSetAmount()
    {
        $converter = new CurrencyConverter;
        $amount_interface = $this->createMock(AmountInterface::class);
        $converter->setAmount($amount_interface);
        $this->assertEquals($amount_interface, $converter->getNewAmount());
        $this->assertEquals($amount_interface, $converter->getOldAmount());
    }

    public function testSetAmountException()
    {
        $converter = new CurrencyConverter;
        $this->expectException(TypeError::class);
        $converter->setAmount((object)[]);
    }

    public function testSetQuotationParser()
    {
        $converter = new CurrencyConverter;
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $converter->setQuotationParser($quotation_interface);
		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('quotation_parser');
		$property->setAccessible(true);
        $this->assertEquals($quotation_interface, $property->getValue($converter));
    }

    public function testSetQuotationParserException()
    {
        $converter = new CurrencyConverter;
        $this->expectException(TypeError::class);
        $converter->setQuotationParser((object)[]);
    }

    public function testSetQuotations()
    {
        $converter = new CurrencyConverter;
        $quotations = __DIR__.'/currencies.json';
        $converter->setQuotations($quotations);
		$reflector = new ReflectionClass(CurrencyConverter::class);
		$property = $reflector->getProperty('quotations');
		$property->setAccessible(true);
        $this->assertEquals($quotations, $property->getValue($converter));
    }

    public function testSetQuotationsException()
    {
        $converter = new CurrencyConverter;
        $this->expectException(ArgumentCountError::class);
        $converter->setQuotations();
    }

    public function testLoadQuotations()
    {
        $converter = new CurrencyConverter;
        $quotations = __DIR__.'/currencies.json';
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('loadQuotations')->willReturn(true);
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('loadQuotations')->willReturn(true);
        $converter->setQuotationParser($quotation_interface);
        $converter->setConverter($converter_interface);
        $converter->setQuotations($quotations);
        $this->assertEmpty($converter->loadQuotations());
    }

    public function testLoadQuotationsExceptionWithoutQuotationParser()
    {
        $converter = new CurrencyConverter;
        $quotations = __DIR__.'/currencies.json';
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('loadQuotations')->willReturn(true);
        $converter->setConverter($converter_interface);
        $converter->setQuotations($quotations);
        $this->expectException(Exception::class);
        $converter->loadQuotations();
    }

    public function testLoadQuotationsExceptionWithoutConverter()
    {
        $converter = new CurrencyConverter;
        $quotations = __DIR__.'/currencies.json';
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('loadQuotations')->willReturn(true);
        $converter->setQuotationParser($quotation_interface);
        $converter->setQuotations($quotations);
        $this->expectException(Exception::class);
        $converter->loadQuotations();
    }

    public function testLoadQuotationsExceptionWithoutQuotations()
    {
        $converter = new CurrencyConverter;
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('loadQuotations')->willReturn(true);
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('loadQuotations')->willReturn(true);
        $converter->setQuotationParser($quotation_interface);
        $converter->setConverter($converter_interface);
        $this->expectException(Exception::class);
        $converter->loadQuotations();
    }

    public function testRun()
    {
        $converter = new CurrencyConverter;
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('run')->willReturn(5.65);
        $amount_interface = $this->createMock(AmountInterface::class);
        $amount_interface->method('setAmount')->willReturnSelf();
        $amount_interface->method('setCurrency')->willReturnSelf();
        $converter->setQuotationParser($quotation_interface);
        $converter->setConverter($converter_interface);
        $converter->setAmount($amount_interface);
        $this->assertEquals(5.65, $converter->run('USD', 'BRL', 1));
    }

    public function testRunInvalidArgumentException()
    {
        $converter = new CurrencyConverter;
        $quotation_interface = $this->createMock(QuotationInterface::class);
        $quotation_interface->method('getValidCurrencies')->willReturn(['USD', 'BRL', 'EUR']);
        $converter_interface = $this->createMock(ConverterInterface::class);
        $converter_interface->method('run')->willReturn(false);
        $amount_interface = $this->createMock(AmountInterface::class);
        $amount_interface->method('setAmount')->willReturnSelf();
        $amount_interface->method('setCurrency')->willReturnSelf();
        $converter->setQuotationParser($quotation_interface);
        $converter->setConverter($converter_interface);
        $converter->setAmount($amount_interface);
        $this->expectException(InvalidArgumentException::class);
        $converter->run('USD', 'BRL', 1);
    }
}
