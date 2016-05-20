<?php


class TasksController extends Controller{
    function assign(){
        $user = current_user();
        $task = new Task(Request::get('task_id'));
        if($this->check_authorization('update', $task)){
            $result = $task->assign(Request::get('assigned_to'));
        }
        else $result = false;

        Response($result);
    }
}
