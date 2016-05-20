<?php

class ReportCSV
{

    public $file;
    public $report;

    function make(Reports $report, $type)
    {

        $this->report = $report;

        $name = "report-$type-" . time() . '.csv';
        //create a file pointer connected to the output stream
        $this->file = fopen("tmp/$name", 'w');

        if ($type == 'all') {
             $this->build_all();
        } else if ($type == 'invoices') {
             $this->build_invoices();
        } else if ($type == 'payments') {
             $this->build_payments();
        } else if ($type == 'time') {
             $this->build_time_entries();
        }

        fclose($this->file);

        return $name;
    }

    function build_separator(){
        fputcsv($this->file, array(''));
        fputcsv($this->file, array('-----------------------------------------------------'));
        fputcsv($this->file, array('-----------------------------------------------------'));
        fputcsv($this->file, array(''));
    }


    function build_all(){
        $this->build_invoices();
        $this->build_separator();
        $this->build_payments();
        $this->build_separator();
        $this->build_time_entries();
    }
    function build_invoices(){
        fputcsv($this->file, array('Invoice #', 'Client', 'Total', 'Status', 'Due Date'));

        foreach ($this->report->invoices as $invoice) {
            $data = array(
                'number' => $invoice->number,
                'client' => $invoice->client_name,
                'total' => $invoice->total,
                'status_text' => $invoice->status_text,
                'due_date' => $invoice->formatted_due_date,
            );

            fputcsv($this->file, $data);
        }
    }

    function build_payments(){
        fputcsv($this->file, array('Client', 'Invoice #', 'Amount', 'Date', 'Payment Method'));

        foreach ($this->report->payments as $payment) {
            $data = array(
                'client_name' => $payment->client_name,
                'invoice_number' => $payment->client_name,
                'amount' => $payment->amount,
                'date' => $payment->formatted_date,
                'method' => $payment->payment_method,
            );

            fputcsv($this->file, $data);
        }
    }

    function build_time_entries(){

        fputcsv($this->file, array('User', 'Project', 'Task', 'Date', 'Time'));

        foreach ($this->report->time_entries as $time_entry) {
            $data = array(
                'client_name' => $time_entry->user_name,
                'project' => $time_entry->project_name,
                'task' => $time_entry->task,
                'date' => $time_entry->formatted_start_date,
                'time' => $time_entry->formatted_time,
            );

            fputcsv($this->file, $data);
        }
    }
}
