<?php

class Reports extends Model
{
    public $payments;
    public $invoices;
    public $time_entries;
    public $criteria;
    public $filter_options;
    public $applied_filters;
    public $start_date;
    public $end_date;
    public $days;

    public $time_entries_daily_totals;
    public $time_entries_total;
    public $invoice_totals;

    public $time_frame_selection;


    function __construct($parameters = null)
    {
        $this->payments = array();
        $this->invoices = array();
        $this->time_entries = array();
        $this->criteria = '';
        $this->applied_filters = array();


        parent::__construct($parameters);

    }


    function has_filter($type)
    {
        return isset($this->applied_filters[$type]);
    }

    function get_filter($type)
    {

        return $this->applied_filters[$type];

    }

    function add_criteria($criteria, $string)
    {
        if ($criteria == '')
            $criteria .= " WHERE $string";
        else $criteria .= " AND $string";

        return $criteria;
    }

    function get_invoices()
    {
        $criteria = '';


        if ($this->has_filter('project_id'))
            $criteria = $this->add_criteria($criteria, 'invoices.project_id = ' . $this->get_filter('project_id'));

        if ($this->has_filter('client_id'))
            $criteria = $this->add_criteria($criteria, 'invoices.client_id = ' . $this->get_filter('client_id'));

        //todo:do I need to take into account date formats?
        if ($this->has_filter('start_date'))
            $criteria = $this->add_criteria($criteria, 'invoices.date >= ' . $this->get_filter('start_date'));

        if ($this->has_filter('end_date'))
            $criteria = $this->add_criteria($criteria, 'invoices.date <= ' . $this->get_filter('end_date'));


        $invoices = new Invoice();
        $this->invoices = $invoices->get($criteria);
    }


    function get_payments()
    {
        $criteria = '';
        $sql = "SELECT payments.*, clients.name as client_name, invoices.id AS invoice_id, invoices.project_id, invoices.client_id,
                invoices.number as invoice_number FROM payments
                LEFT JOIN clients
                  ON clients.id = payments.client_id
                LEFT JOIN invoices
                  ON invoices.id = payments.invoice_id";


        if ($this->has_filter('project_id'))
            $criteria = $this->add_criteria($criteria, 'invoices.project_id = ' . $this->get_filter('project_id'));

        if ($this->has_filter('client_id'))
            $criteria = $this->add_criteria($criteria, 'invoices.client_id = ' . $this->get_filter('client_id'));

        //todo:do I need to take into account date formats?
        if ($this->has_filter('start_date'))
            $criteria = $this->add_criteria($criteria, 'payments.payment_date >= ' . $this->get_filter('start_date'));

        if ($this->has_filter('end_date'))
            $criteria = $this->add_criteria($criteria, 'payments.payment_date <= ' . $this->get_filter('end_date'));


        $sql .= $criteria;


        $this->payments = $this->select($sql);
    }

    function get_time_entries()
    {
        $criteria = '';
        $sql = "SELECT time_entries.*, CONCAT(users.first_name, ' ', users.last_name) as user_name, tasks.task, tasks.project_id, tasks.id, tasks.project_id, projects.name AS project_name
                FROM time_entries
                LEFT JOIN users on time_entries.user_id = users.id
                LEFT JOIN tasks on time_entries.task_id = tasks.id
                LEFT JOIN projects on tasks.project_id = projects.id";


        if ($this->has_filter('project_id'))
            $criteria = $this->add_criteria($criteria, 'tasks.project_id = ' . $this->get_filter('project_id'));

        if ($this->has_filter('client_id'))
            $criteria = $this->add_criteria($criteria, 'tasks.client_id = ' . $this->get_filter('client_id'));

        //todo:do I need to take into account date formats?
        if ($this->has_filter('start_date'))
            $criteria = $this->add_criteria($criteria, 'time_entries.start_date >= ' . $this->get_filter('start_date'));

        if ($this->has_filter('end_date'))
            $criteria = $this->add_criteria($criteria, 'time_entries.start_date <= ' . $this->get_filter('end_date'));


        $sql .= $criteria;

        $this->time_entries = $this->select($sql);

    }

    function get_filters()
    {
        $sql = "SELECT id, name FROM clients";
        $clients = $this->select($sql);

        $sql = "SELECT id, name FROM projects";
        $projects = $this->select($sql);

        $sql = "SELECT id, CONCAT(users.first_name, ' ', users.last_name) as user_name FROM users";
        $users = $this->select($sql);

        $this->filter_options = array(
            'clients' => $clients,
            'projects' => $projects,
            'users' => $users
        );

    }

    function get($criteria = null)
    {
        $this->set_applied_filters();
        $this->get_invoices();
        $this->get_payments();
        $this->get_time_entries();
        $this->get_filters();
        $this->analyze_date_range();
        $this->calculate_time_entries_daily_totals();
        $this->calculate_invoice_totals();
    }


    function set_applied_filters()
    {

        $values = Request::get('filters');


        $this->applied_filters = $values;

        $this->set_time_frame_selection();


    }

    function set_time_frame_selection()
    {

        $this->time_frame_selection = isset($this->applied_filters['time_frame_selection']) ? $this->applied_filters['time_frame_selection'] : false;

        $this->set_time_frame();


    }

    function has_no_time_frame_specified()
    {
        return $this->time_frame_selection == false;
    }


    /**
     * Return the first day of the Week/Month/Quarter/Year that the
     * current/provided date falls within
     *
     * @param string $period The period to find the first day of. ('year', 'quarter', 'month', 'week')
     * @param DateTime $date The date to use instead of the current date
     *
     * @return DateTime
     * @throws InvalidArgumentException
     */
    function firstDayOf($period, DateTime $date = null)
    {
        $period = strtolower($period);
        $validPeriods = array('year', 'quarter', 'month', 'week');

        if (!in_array($period, $validPeriods))
            throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case 'year':
                $newDate->modify('first day of january ' . $newDate->format('Y'));
                break;
            case 'quarter':
                $month = $newDate->format('n');

                if ($month < 4) {
                    $newDate->modify('first day of january ' . $newDate->format('Y'));
                } elseif ($month > 3 && $month < 7) {
                    $newDate->modify('first day of april ' . $newDate->format('Y'));
                } elseif ($month > 6 && $month < 10) {
                    $newDate->modify('first day of july ' . $newDate->format('Y'));
                } elseif ($month > 9) {
                    $newDate->modify('first day of october ' . $newDate->format('Y'));
                }
                break;
            case 'month':
                $newDate->modify('first day of this month');
                break;
            case 'week':
                $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
                break;
        }

        return $newDate;
    }

    function set_time_frame()
    {
        //$this->time_frame_selection = 'all';

        if ($this->has_no_time_frame_specified()) {
            $this->set_default_date_range();
        } else if ($this->time_frame_selection == 'year') {
            $year = new DateTime();
            $this->set_start_date('first day of january ' . $year->format('Y'));
            $this->set_end_date();
        } else if ($this->time_frame_selection == 'month') {
            $this->set_default_date_range();
        } else if ($this->time_frame_selection == 'week') {

            $week = new DateTime();
            //echo '23:59 ' . (($week->format('w') === '0') ? 'today' : 'sunday this week');

            $this->set_start_date('midnight ' . (($week->format('w') === '0') ? 'monday last week' : 'monday this week'));
            $this->set_end_date('23:59 ' . (($week->format('w') === '0') ? 'now' : 'sunday this week'));
        } else if ($this->time_frame_selection == 'all') {
            $this->get_earliest_date();
            $this->get_latest_date();
        } else if ($this->time_frame_selection == 'custom') {
            if (isset($this->applied_filters)) {

                if (isset($this->applied_filters['start_date'])) {
                    $this->start_date = new DateTime($this->applied_filters['start_date']);
                    $this->start_date->setTime(0, 0);

                    $this->applied_filters['start_date'] = $this->start_date->getTimestamp();
                }

                if (isset($this->applied_filters['end_date'])) {
                    $this->end_date = new DateTime($this->applied_filters['end_date']);
                    $this->end_date->setTime(23, 59);

                    $this->applied_filters['end_date'] = $this->end_date->getTimestamp();
                }
            }
        } else if (!isset($this->applied_filters['start_date']) && !isset($this->applied_filters['end_date'])) {
            $this->set_default_date_range();
        }


        //if the time_frame_selection is custom, the dates will be set in set applied_filters

    }

    function set_start_date($date_time_string = null)
    {
        $this->start_date = new DateTime($date_time_string);
        $this->applied_filters['start_date'] = $this->start_date->getTimestamp();
    }

    function set_end_date($date_time_string = null)
    {
        $this->end_date = new DateTime($date_time_string);
        $this->applied_filters['end_date'] = $this->end_date->getTimestamp();
    }

    function set_default_date_range()
    {
        $this->time_frame_selection = 'month';

        $this->set_start_date('midnight first day of this month');
        $this->set_end_date('23:59 last day of this month');

    }

    function get_earliest_date()
    {
        //if there is no start date, then the start date will be the earliset date in the system for the items
        //tracked by reporting
        $sql = "SELECT MIN(payment_date) FROM payments";
        $result = $this->select($sql);
        $earliest_payment_date = $result[0]['MIN(payment_date)'];

        $sql = "SELECT MIN(date) FROM invoices";
        $result = $this->select($sql);
        $earliest_invoice_date = $result[0]['MIN(date)'];

        $sql = "SELECT MIN(start_date) FROM time_entries";
        $result = $this->select($sql);
        $earliest_time_entry_date = $result[0]['MIN(start_date)'];

        $earliest = min($earliest_invoice_date, $earliest_payment_date, $earliest_time_entry_date);

        $this->start_date = new DateTime();
        $this->start_date->setTimestamp($earliest);
        $this->start_date->setTime(0, 0);
        $this->applied_filters['start_date'] = $earliest;
    }

    function get_latest_date()
    {
        //if there is no end date, then the end date is end of day today
        $this->end_date = new DateTime();
        $this->end_date->setTime(23, 59);
        $this->applied_filters['end_date'] = $this->end_date->getTimestamp();
    }

    function analyze_date_range()
    {
        if (!isset($this->start_date)) {
            $this->get_earliest_date();
        }

        if (!isset($this->end_date)) {
            $this->get_latest_date();
        }

        $this->days = $this->end_date->diff($this->start_date)->days;
    }

    function calculate_time_entries_daily_totals()
    {
        //get a copy so we don't manipulate/change the original
        $day = $this->copy_date($this->start_date);
        $total = 0;

        $entries = array();

        for ($i = 0; $i <= $this->days; $i++) {
            $day_string = $day->format('m-d-y'); //todo:should take into accoutn unser date preferences

            $day->setTime(23, 59);
            $end_of_day = $day->getTimestamp();

            $day->setTime(0, 0);
            $start_of_day = $day->getTimestamp();

            $entries[$day_string] = 0;
            foreach ($this->time_entries as $entry) {
                if ($entry['start_date'] >= $start_of_day && $entry['start_date'] <= $end_of_day) {
                    $entries[$day_string] += (int)$entry['time'];
                    $total += (int)$entry['time'];
                }
            }

            $day->add(new DateInterval('P1D'));
        }

        $this->time_entries_daily_totals = $entries;
        $this->time_entries_total = $total;
    }

    function calculate_invoice_totals()
    {
        $invoice_totals = array(
            'paid' => 0,
            'unpaid' => 0,
            'total' => 0
        );

        foreach ($this->invoices as $invoice) {
            $total = (float)$invoice['total'];

            if(Invoice::is_active($invoice)){
                if (Invoice::is_paid($invoice)) {
                    $invoice_totals['paid'] += $total;
                } else $invoice_totals['unpaid'] += $total;
            }
        }

        $invoice_totals['total'] = $invoice_totals['unpaid'] + $invoice_totals['paid'];

        $this->invoice_totals = $invoice_totals;
    }

    function copy_date(DateTime $date)
    {
        $copy = new DateTime();
        $copy->setTimestamp($date->getTimestamp());

        return $copy;
    }

    function get_old($criteria = null)
    {
        $first_day_of_year = strtotime('Jan 1, ' . date('Y'));
        $last_day_of_year = strtotime('Dec 31, ' . date('Y'));

        $sql = "SELECT payments.*, clients.name as client_name, invoices.number as invoice_number FROM payments
                LEFT JOIN clients
                  ON clients.id = payments.client_id
                LEFT JOIN invoices
                  ON invoices.id = payments.invoice_id
                WHERE payment_date >= $first_day_of_year
                AND payment_date <= $last_day_of_year
                ORDER BY payment_date ASC";


        $this->payments_by_month = array();
        $this->payment_totals_by_month = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

        $this->payments = $this->select($sql);

        if (is_array($this->payments)) {
            foreach ($this->payments as $payment) {
                $month_number = date('n', $payment['payment_date']);

                if (!isset($this->payments_by_month[$month_number]) || !is_array($this->payments_by_month[$month_number]))
                    $this->payments_by_month[$month_number] = array();

                $this->payments_by_month[$month_number][] = $payment;

                $this->payment_totals_by_month[$month_number - 1] += $payment->amount;
            }
        }

        $this->get_totals();

        $sql = "SELECT SUM(total)
                FROM invoices
                WHERE balance != 0";
        $outstanding_invoices = $this->select($sql);
        $this->outstanding_invoices_total = $outstanding_invoices[0]['SUM(total)'];

        if ($this->outstanding_invoices_total == null)
            $this->outstanding_invoices_total = 0;


        $sql = "SELECT payments.client_id, SUM(payments.amount) as total_payments, clients.name
                FROM payments
                LEFT JOIN clients
                  ON clients.id = payments.client_id
                WHERE payments.payment_date >= $first_day_of_year
                AND payments.payment_date <= $last_day_of_year
                GROUP BY payments.client_id";

        $this->payments_by_client = $this->select($sql);
        return $this->to_array();

    }


    function get_totals()
    {
        //subtract 1 because the months array starts at 0
        $this_month = date('n', time()) - 1;

        $this->payments_this_month = $this->payment_totals_by_month[$this_month];

        $last_month = $this_month > 0 ? $this_month - 1 : false;
        if ($last_month) {
            $payments_last_month = $this->payment_totals_by_month[$last_month];

            if ($payments_last_month > 0)
                $this->payments_this_month_change_percentage = (($this->payments_this_month / $payments_last_month) - 1) * 100;
            else $this->payments_this_month_change_percentage = 0;
        }
    }

    function make()
    {
        $type = Request::get('type');
        $file_format = Request::get('format');

        $this->get();
        $this->format();

        if ($file_format == 'csv') {
            $csv = new ReportCSV();
            return $csv->make($this, $type);
        }
        else {
            $pdf = new ReportPDF();
            return $pdf->make($this, $type);
        }
    }

    //todo:might need it's own class to share with reportcsv because it definitely doesn't belong here
    function format()
    {
        $date_format = 'm/j/Y'; //todo:config needs to be separate from other date formats because invoice_date_format doesn't work

        foreach ($this->invoices as &$invoice) {
            $invoice->formatted_due_date = date($date_format, $invoice->due_date);
        }

        foreach ($this->payments as &$payment) {
            $payment->formatted_date = date($date_format, $payment->payment_date);
        }

        foreach ($this->time_entries as &$time_entry) {
            $time_entry->formatted_start_date = date($date_format, $time_entry->start_date);
            $time_entry->formatted_time = gmdate('i \m\i\n\s, s \s\e\c\s', $time_entry->time);
        }
    }

    function download($path)
    {
        $pdf = new ReportPDF();
        $pdf->download($path);
    }
}