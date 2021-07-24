<?php

namespace Test;

use App\Amounts\PrintAmount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TypeError;

class PrintAmountTest extends TestCase
{
    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testSetAmount()
    {
        $amount = new PrintAmount;
        $amount->setAmount(0.05);
        $this->assertEquals($amount->getAmount(), 0.05);
        $amount->setAmount(2.0981239786);
        $this->assertEquals($amount->getAmount(), 2.10);
        $amount->setAmount('0.0000000001');
        $this->assertEquals($amount->getAmount(), 0.0000000001);
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testExceptionWithStringInSetAmount()
    {
        $amount = new PrintAmount;
        $this->expectException(TypeError::class);
        $amount->setAmount('');
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testExceptionWithArrayInSetAmount()
    {
        $amount = new PrintAmount;
        $this->expectException(TypeError::class);
        $amount->setAmount([]);
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testSetCurrency()
    {
        $amount = new PrintAmount;
        $amount->setCurrency('BRL')->setAmount(0.05);
        $this->assertEquals($amount->getFormatedAmount(), '0.05 BRL');
        $amount->setCurrency('AuD')->setAmount(2.0981239786);
        $this->assertEquals($amount->getFormatedAmount(), '2.10 AUD');
        $amount->setCurrency('eur')->setAmount('0.0000000001');
        $this->assertEquals($amount->getFormatedAmount(), '0.0000000001 EUR');
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testExceptionWithIntInSetCurrency()
    {
        $amount = new PrintAmount;
        $this->expectException(InvalidArgumentException::class);
        $amount->setCurrency(1);
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testExceptionWithInvalidCurrencyInSetCurrency()
    {
        $amount = new PrintAmount;
        $this->expectException(InvalidArgumentException::class);
        $amount->setCurrency('asdc');
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testExceptionWithArrayInSetCurrency()
    {
        $amount = new PrintAmount;
        $this->expectException(TypeError::class);
        $amount->setCurrency([]);
    }

    /**
     * @covers \App\Amounts\PrintAmount
     */
    public function testToString()
    {
        $amount = new PrintAmount;
        $amount->setCurrency('BRL')->setAmount(0.05);
        $this->assertEquals((string) $amount, '0.05 BRL');
        $amount->setCurrency('AuD')->setAmount(2.0981239786);
        $this->assertEquals((string) $amount, '2.10 AUD');
        $amount->setCurrency('eur')->setAmount('0.0000000001');
        $this->assertEquals((string) $amount, '0.0000000001 EUR');
    }
}
