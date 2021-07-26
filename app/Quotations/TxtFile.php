<?php

namespace App\Quotations;

class TxtFile extends JsonFile
{
    protected function parseQuotations($quotations)
    {
        return file($quotations);
    }

    protected function parseQuotation($quotation)
    {
        preg_match('/^([\d\.]+) ([A-Z]{3}) = ([\d\.]+) ([A-Z]{3})/', $quotation, $matches);
        return (object) [
            'from' => $matches[2],
            'to' => $matches[4],
            'quotation' => $matches[3] / $matches[1],
        ];
    }
}