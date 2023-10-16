<?php

namespace App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater;

use App\Core\Invoice\Domain\Status\InvoiceStatus;

class GetInvoicesByStatusAndAmountGreaterQuery
{
    public function __construct(public readonly  InvoiceStatus $invoiceStatus, public readonly int $amount)
    {
    }
}
