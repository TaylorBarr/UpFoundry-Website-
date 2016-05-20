<?php

use phpSweetPDO\SQLHelpers\Basic as Helpers;

Class InvoiceItem extends Model
{
    public $item;
    public $quantity;
    public $rate;
    public $subtotal;
    public $invoice_id;
    public $task;
    public $task_id;

    //not saved to db
    public $formatted_subtotal;
    public $taxes;

    function validate(){
        $this->validator_tests = array(
            'item' => 'required'
        );

        return parent::validate();
    }

    function base_select_query($invoice_id)
    {
        return "SELECT invoice_items. * , GROUP_CONCAT( invoice_items_tax_rates.tax_id ) AS tax_ids
                FROM invoice_items
                LEFT JOIN invoice_items_tax_rates ON invoice_items.id = invoice_items_tax_rates.invoice_item_id
                WHERE invoice_items.invoice_id = $invoice_id
                GROUP BY invoice_items.id";
    }

    //this is used by the estimate and recurring invoice objects
    function process_taxes($tax_ids){
        if(isset($tax_ids) && !empty($tax_ids)){
            $sql = "SELECT * FROM tax_rates WHERE id IN($tax_ids)";
            $this->taxes = $this->select($sql);

            foreach($this->taxes as &$tax){

                $tax['amount'] = (float)$this->subtotal * (float)$tax->rate;
            }
        }

    }


    function get($invoice_id = null){
        //nothing to do if we don't have an invoice item
        if($invoice_id == null)
            return false;

        //$sql = "SELECT * FROM invoice_items WHERE invoice_id = " . $invoice_id;
        $sql = $this->base_select_query($invoice_id);

        return parent::get($sql);
    }

    function save(){
        $this->import_parameters();

        $this->unset_param('formatted_subtotal');
        $this->unset_param('task');
        $this->unset_param('taxes');

        $subtotal = round((float)$this->rate * (float)$this->quantity, 2);
        $this->set('subtotal', $subtotal);

        if(!isset($this->rate))
            $this->set('rate', 0);

        if (!isset($this->quantity))
            $this->set('quantity', 0);

        $result =  parent::save();

        $this->save_taxes();
    }

    function save_taxes(){

        $this->delete_taxes();


        if(is_array($this->taxes)){
            foreach($this->taxes as $tax){
                $tax = new TaxRate($tax['id']);

                $this->insert(array('invoice_item_id'=>$this->id, 'tax_id' => $tax->id), 'invoice_items_tax_rates');
            }
        }
    }

    function delete_taxes(){
        $sql = "DELETE FROM invoice_items_tax_rates WHERE invoice_item_id = $this->id";
        $this->execute($sql);
    }

    function delete(){
        $this->import_parameters();

        //if the id isn't set, then this item hasn't been saved the database yet so there would be nothing to do
        if(isset($this->id)){
            $result = parent::delete();

            $this->delete_taxes();

            //the total will change on this invoice since we're deleting an item
            $invoice = new Invoice($this->invoice_id);
            $invoice->calculate_total(true);

            return $result;


        }
        else return false;
    }

    function prep_for_display(){
        $this->formatted_subtotal = number_format($this->subtotal, 2);
    }
    function set_subtotal(){}
}
 
