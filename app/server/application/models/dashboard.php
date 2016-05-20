<?php

class Dashboard extends Model
{
    protected $projects;
    protected $activity;
    protected $invoices;
    protected $upcoming_events;

    function get($criteria = null){
        $user = current_user();
        $projects = new Project();
        $activity = new Activity();
        $invoice = new Invoice();
        $upcoming_events = new UpcomingEvents();

        $this->projects = $projects->get("WHERE projects.is_archived = 0");
        $this->activity = $activity->get();
        $this->upcoming_events = $upcoming_events->get(current_user());

        $invoices = $invoice->get();


        $outstanding_total = 0;
        $overdue_total = 0;
        $invoice_total = 0;

        $now = time();

        foreach($invoices as $invoice){

            if(Invoice::is_active($invoice)){
                if(!Invoice::is_paid($invoice)){
                    $outstanding_invoices[] = $invoice;
                    $outstanding_total += $invoice['total'];
                }

                if(Invoice::is_overdue($invoice)){
                    $overdue_invoices[] = $invoice;
                    $overdue_total += $invoice['total'];
                }

                $invoice_total += $invoice['total'];
            }



        }


        $this->invoices = array(
//            'outstanding' => $outstanding_invoices,
            'outstanding_total' => $outstanding_total,
//            'overdue' => $overdue_invoices,
            'overdue_total' => $overdue_total,
            'total' => $invoice_total
        );
    }



    function user_can_access(User $user = null){
        return true;
    }
}