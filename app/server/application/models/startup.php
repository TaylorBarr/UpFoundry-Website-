<?php


class Startup extends Model{

    function info(){
        $tax_rates = new TaxRate();
        $tax_rates = $tax_rates->get();

        if(current_user() !== false){
            $overdue_invoices_count = new Invoice();
            $overdue_invoices_count = $overdue_invoices_count->get_overdue_count();

            $payment_terms = new PaymentTerms();
            $payment_terms = $payment_terms->get();
        }
        else {
            $payment_terms = null;
            $overdue_invoices_count = null;
        }


        return array(
            'tax_rates' => $tax_rates,
            'payment_terms' => $payment_terms,
            'overdue_invoices_count' => $overdue_invoices_count
        );
    }
}
 
