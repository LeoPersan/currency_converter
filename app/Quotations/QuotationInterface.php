<?php

namespace App\Quotations;

interface QuotationInterface
{
    public function loadQuotations($quotations);
    public function getQuotations();
    public function getValidCurrencies();
}
