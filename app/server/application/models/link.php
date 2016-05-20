<?php

Class Link extends Model{
    public $object_type;
    public $object_id;
    public $slug;
    public $expires;
    public $created;


    function get($object = null){
        if($this->get_existing_link($object) == false){
            $this->generate($object);
        }

        return $this;
    }

    function get_existing_link($object){
        $object_type = get_class($object);
        $object_id = $object->id;

        $sql = "SELECT * FROM links WHERE object_type = '$object_type' AND object_id = $object_id";
        $result = $this->select($sql);

        if(count($result) > 0){
            $this->import_parameters($result[0]);
            return true;
        }
        else return false;
    }

    function generate($object){
        $object_type = get_class($object);
        $object_id = $object->id;
        $slug = $this->make_slug($this->object_type, $this->object_id);
        $created = new DateTime('now');

        $this->set('object_type', $object_type);
        $this->set('object_id', $object_id);
        $this->set('slug', $slug);
        $this->set('created', $created->getTimestamp());

        $created->add(new DateInterval('P1D'));
        $this->set('expires', $created->getTimestamp());

        //parameters, including an object id may be passed in the POST params. We don't want those to be imported.
        $this->params_imported = true;

        $this->save();
    }

    function is_valid_slug($slug){
        $slug = Request::clean($slug);
        $sql = "SELECT object_type, object_id FROM links WHERE slug = '$slug'";
        $data = $this->select($sql);

        if(count($data) > 0){
            return true;
        }
        else return false;
    }

    function asset($slug){

        $slug = Request::clean($slug);
        $sql = "SELECT object_type, object_id FROM links WHERE slug = '$slug'";
        $data = $this->select($sql);

        if(count($data) > 0){
            $type = $data[0]['object_type'];
            $id = $data[0]['object_id'];
            $model = new $type($id);
            return array(
                'type'=> lcfirst($type),
                $type => $model
            );
        }
        else{
            $this->set_error('slug', 'Invalid link');
            return false;
        }
    }

    function url(){
        return get_config('base_url') . '#link/' . $this->slug;
    }

    function make_slug($object_type, $object_id){
        //since this doesn't ensure uniqueness, we'll query to the db to make sure there are no duplicates
        //http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string
        $slug = md5(uniqid($object_type . $object_id, true));

        $sql = "SELECT id FROM links WHERE slug = '$slug'";
        $results = $this->select($sql);

        if(count($results)>0){
            //there was duplicate, runt he funciton again
            return $this->make_slug($object_type, $object_id);
        }
        else return $slug;
    }
}