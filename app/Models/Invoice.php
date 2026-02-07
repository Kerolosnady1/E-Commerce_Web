<?php

namespace App\Models;

/**
 * Invoice is an alias for SaleInvoice
 * This class exists for backwards compatibility
 */
class Invoice extends SaleInvoice
{
    // Use the same table as SaleInvoice
    protected $table = 'sale_invoices';
}
