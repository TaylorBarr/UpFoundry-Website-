<?php


class RecurringInvoiceItem extends InvoiceItem{
    public $item;
    public $quantity;
    public $rate;
    public $subtotal;
    public $recurring_invoice_id;

    //not saved to db
    public $formatted_subtotal;
    public $taxes;




    function save_taxes()
    {

        $this->delete_taxes();


        if (is_array($this->taxes)) {

            foreach ($this->taxes as $tax) {
                $tax = new TaxRate($tax['id']);

                $this->insert(array('recurring_invoice_item_id' => $this->id, 'tax_id' => $tax->id), 'recurring_invoice_items_tax_rates');
            }
        }
    }

    function delete_taxes()
    {
        $sql = "DELETE FROM recurring_invoice_items_tax_rates WHERE recurring_invoice_item_id = $this->id";
        $this->execute($sql);
    }

    function delete()
    {
        $this->import_parameters();

        //if the id isn't set, then this item hasn't been saved the database yet so there would be nothing to do
        if (isset($this->id)) {
            $result = parent::delete();

            $this->delete_taxes();


            return $result;


        } else return false;
    }

}