<?php

namespace App\Quotations;

class JsonFile implements QuotationInterface
{
    private $quotations;
    private $currencies;

    public function loadQuotations($quotations)
    {
        $this->quotations = json_decode(file_get_contents($quotations));
        $this->currencies = [];
        foreach ($this->quotations as $quotation) {
            $this->currencies[] = $quotation->from;
            $this->currencies[] = $quotation->to;
        }
        $this->currencies = array_values($this->currencies);
        return $this;
    }

    public function getQuotations()
    {
        return $this->quotations;
    }

    public function getValidCurrencies()
    {
        return $this->currencies;
    }
}