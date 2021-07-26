<?php

namespace App;

use App\Amounts\AmountInterface;
use App\Converters\ConverterInterface;
use App\Quotations\QuotationInterface;
use Exception;
use InvalidArgumentException;

class CurrencyConverter
{
    protected $quotations;
    protected $converter;
    protected $quotation_parser;
    protected $old_amount;
    protected $new_amount;
    
    public function __construct(QuotationInterface $quotation_parser = null,
                                ConverterInterface $converter = null,
                                AmountInterface $amount = null,
                                $quotations = null)
    {
        if (null !== $quotation_parser)
            $this->setQuotationParser($quotation_parser);
        if (null !== $converter)
            $this->setConverter($converter);
        if (null !== $amount)
            $this->setAmount($amount);
        if (null !== $quotations)
            $this->setQuotations($quotations);
        if (null !== $quotation_parser && null !== $quotations && null !== $converter)
            $this->loadQuotations();
    }

    public function setConverter(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function setAmount(AmountInterface $amount)
    {
        $this->old_amount = $amount;
        $this->new_amount = $amount;
    }

    public function setQuotationParser(QuotationInterface $quotation_parser)
    {
        $this->quotation_parser = $quotation_parser;
    }

    public function setQuotations($quotations)
    {
        $this->quotations = $quotations;
    }

    public function loadQuotations()
    {
        if (!($this->quotation_parser instanceof QuotationInterface))
            throw new Exception("Set the quotation parser");
        if (!($this->converter instanceof ConverterInterface))
            throw new Exception("Set the converter");
        if ($this->quotations === null)
            throw new Exception("Set the quotations");

        $this->quotation_parser->loadQuotations($this->quotations);
        $this->converter->loadQuotations($this->quotation_parser);
    }

    public function run(string $from, string $to, float $old_amount)
    {
        if (($new_amount = $this->converter->run($from, $to, $old_amount)) !== false) {
            $this->old_amount->setAmount($old_amount)->setCurrency($from);
            $this->new_amount->setAmount($new_amount)->setCurrency($to);
            return $new_amount;
        }

        throw new InvalidArgumentException("Conversion \"$from\" => \"$to\" cannot be done");;
    }

    public function getNewAmount()
    {
        return $this->new_amount;
    }

    public function getOldAmount()
    {
        return $this->old_amount;
    }
}
