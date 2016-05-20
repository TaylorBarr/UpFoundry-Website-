<?php


class PaymentTerms extends Model{
    public $name;
    public $num_days;
    public $is_default;

    function __construct($parameters = null){
        $this->table = 'payment_terms';

        parent::__construct($parameters);
    }
    function get($criteria = null){
        $terms = parent::get($criteria);


        $due_on_receipt = array(
            'name' => 'Due On Receipt', //todo:lang
            'num_days' => 0,
            'is_default' => 0
        );


        $has_default = false;

        foreach ($terms as $term) {
            if ($term['is_default'] == 1)
                $has_default = true;
        }

        if(!$has_default){
            $due_on_receipt['is_default'] = 1;
        }

        $terms[] = $due_on_receipt;

        return $terms;
    }
}
 
