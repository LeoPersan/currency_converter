<?php

namespace App;

use App\Converters\ConverterInterface;
use App\Quotations\QuotationInterface;
use Exception;
use InvalidArgumentException;

class CurrencyConverter
{
    private $quotations;
    private $converter;
    private $quotation_parser;
    
    public function __construct(QuotationInterface $quotation_parser = null, ConverterInterface $converter = null, $quotations = null)
    {
        if (null !== $quotation_parser)
            $this->setQuotationParser($quotation_parser);
        if (null !== $converter)
            $this->setConverter($converter);
        if (null !== $quotations)
            $this->setQuotations($quotations);
        if (null !== $quotation_parser && null !== $quotations)
            $this->loadQuotations();
    }

    public function setConverter(ConverterInterface $converter)
    {
        $this->converter = $converter;
        return $this;
    }

    public function setQuotationParser(QuotationInterface $quotation_parser)
    {
        $this->quotation_parser = $quotation_parser;
        return $this;
    }

    public function setQuotations($quotations)
    {
        $this->quotations = $quotations;
        return $this;
    }

    public function loadQuotations()
    {
        if (!($this->quotation_parser instanceof QuotationInterface))
            throw new Exception("Set the quote parser");
        if (!($this->converter instanceof ConverterInterface))
            throw new Exception("Set the converter");
        if ($this->quotations === null)
            throw new Exception("Set the quotations");

        $this->quotation_parser->loadQuotations($this->quotations);
        $this->converter->loadQuotations($this->quotation_parser);
    }

    public function run(string $from, string $to, float $amount)
    {
        if (!in_array($from, $this->quotation_parser->getValidCurrencies()))
            throw new InvalidArgumentException("Invalid currency: " . $from);
        if (!in_array($to, $this->quotation_parser->getValidCurrencies()))
            throw new InvalidArgumentException("Invalid currency: " . $to);
        
        return $this->converter->run($from, $to, $amount);
    }
}
