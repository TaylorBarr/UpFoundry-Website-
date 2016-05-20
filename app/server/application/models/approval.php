<?php


class Approval extends Model{
    public $object_type;
    public $object_id;
    public $is_approved;
    public $is_rejected;
    public $is_active;
    public $date;


    function get_existing_approval(){
        $sql = "SELECT * FROM approvals WHERE object_type = '$this->object_type' AND object_id = $this->object_id AND is_active = 1";
        $approval = $this->select($sql);

        if(count($approval) > 0){
            $this->import_parameters($approval[0]);
            return $this;
        }
        else return false;
    }

    function create(Model $entity){
        $this->set('object_type', lcfirst(get_class($entity)));
        $this->set('object_id', $entity->id);
        $this->set('is_active', 1);

        return $this->get_existing_approval();
    }

    function save(){
        //don't want to import data about the entity this approval is being created for, and that data currently resides
        //in the post parameters
        $this->params_imported = true;
        $this->set('date', time());
        parent::save();
    }

    function already_responded(){
        return $this->is_rejected == 1 || $this->is_approved == 1;
    }

    function reject(){
        if($this->is_active == 1 && !$this->already_responded()){
            $this->set('is_rejected', 1);
            $this->set('is_approved', 0);
            $this->save();
        }
    }

    function approve(){
        if ($this->is_active == 1 && !$this->already_responded()) {
            $this->set('is_rejected', 0);
            $this->set('is_approved', 1);
            $this->save();
        }
    }
}
