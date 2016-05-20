<?php


class UpcomingEvents extends Model{
    function get($user = null){
        if(!isset($user)){
            $user = current_user();
        }

        $upcoming_end_date = new DateTime();
        $upcoming_end_date->setTime(0, 0);
        $upcoming_end_date->add(new DateInterval('P10D')); //todo: customizalbe number of days
        $upcoming_end_date = $upcoming_end_date->getTimestamp();


        if($user->role !== 'client'){
            //get upcoming projects
            $sql = "SELECT * FROM projects WHERE due_date < $upcoming_end_date AND is_archived = 0";
            $sql = $this->modify_sql_for_user_type($sql, $user);;
            $projects = $this->select($sql);

        }
        else $projects = array();

        //get upcoming tasks
        $sql = "SELECT tasks.*, projects.name AS project_name
                FROM tasks
                LEFT JOIN projects
                  ON tasks.project_id = projects.id
                WHERE tasks.due_date < $upcoming_end_date AND tasks.is_complete = 0 AND tasks.assigned_to = $user->id";
        $sql = $this->modify_sql_for_user_type($sql, $user, 'tasks');;
        $tasks = $this->select($sql);

        $events = array_merge($tasks, $projects);

        usort($events, function ($a, $b) {
            if ($a['due_date'] == $b['due_date']) {
                return 0;
            }

            return ($a['due_date'] < $b['due_date']) ? -1 : 1;
        });

        return $events;
    }
}
 
