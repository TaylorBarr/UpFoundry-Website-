<?php

class EstimateItem extends InvoiceItem{
    public $estimate_id;

    function base_select_query($estimate_id)
    {
        return "SELECT estimate_items.* , GROUP_CONCAT( estimate_items_tax_rates.tax_id ) AS tax_ids
                FROM estimate_items
                LEFT JOIN estimate_items_tax_rates ON estimate_items.id = estimate_items_tax_rates.estimate_item_id
                WHERE estimate_items.estimate_id = $estimate_id
                GROUP BY estimate_items.id";
    }

    function save(){
        $this->import_parameters();

        if(isset($this->invoice_id) && !empty($this->invoice_id)){
            $this->set('estimate_id', $this->invoice_id);
            $this->unset_param('invoice_id');
        }


        return parent::save();
    }

    function save_taxes()
    {

        $this->delete_taxes();


        if (is_array($this->taxes)) {
            foreach ($this->taxes as $tax) {
                $tax = new TaxRate($tax['id']);

                //we're saving all the relevant tax data, just in case the actual tax record is changed later, we don't
                //want that to change the total on this invoice
                $tax_data = array(
                    'estimate_item_id' => $this->id,
                    'tax_id' => $tax->id,
                    'tax_name' => $tax->name,
                    'tax_rate' => $tax->rate
                );

                $this->insert($tax_data, 'estimate_items_tax_rates');
            }
        }
    }


    function delete_taxes()
    {
        $sql = "DELETE FROM estimate_items_tax_rates WHERE estimate_item_id = $this->id";
        $this->execute($sql);
    }
}
 
