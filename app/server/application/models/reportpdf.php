<?php


class ReportPDF extends Model{
    public $payments;
    public $invoices;
    public $time_entries;

    function build_html($template, $data = null)
    {
        $this->load_library('rain.tpl.class');
        raintpl::$tpl_dir = ROOT . "/application/templates/reports/"; // template directory

        $tpl = new raintpl(); //include Rain TPL

        if (isset($data)) {
            $tpl->assign($data);
        }

        return $tpl->draw($template, true); // draw the template
    }


    function build_header()
    {
        $html = $this->build_html('header');

        return $html;
    }

    function build_footer()
    {
        $html = $this->build_html('footer');

        return $html;
    }

    function build_report($details)
    {
        $type = $details['type'];
        $data = $details['data'];
        $title = $details['title'];
        $title_classes = isset($details['title_classes']) ? $details['title_classes'] : '';
        $omit_headers = isset($details['omit_headers']) ? $details['omit_headers'] : false;
        $html = '';

        if (!$omit_headers)
            $html = $this->build_header();

        $html .= $this->build_title_page($title, $title_classes);

        $html .= $this->build_html($type, array($type => $data));

        if (!$omit_headers)
            $html .= $this->build_footer();

        return $html;
    }


    function build_title_page($title, $classes = '')
    {
        return $this->build_html('title', array('title' => $title, 'classes' => $classes));
    }

    function build_invoices_report($is_first = false, $omit_headers = false)
    {
        return $this->build_report(array(
            'type' => 'invoices',
            'data' => $this->invoices,
            'title' => 'Invoices',
            'title_classes' => $is_first ? 'first' : '',
            'omit_headers' => $omit_headers
        ));
    }

    function build_payments_report($is_first = false, $omit_headers = false)
    {
        return $this->build_report(array(
            'type' => 'payments',
            'data' => $this->payments,
            'title' => 'Payments',
            'title_classes' => $is_first ? 'first' : '',
            'omit_headers' => $omit_headers
        ));
    }

    function build_time_entries_report($is_first = false, $omit_headers = false)
    {
        return $this->build_report(array(
            'type' => 'time_entries',
            'data' => $this->time_entries,
            'title' => 'Time Entries',
            'title_classes' => $is_first ? 'first' : '',
            'omit_headers' => $omit_headers
        ));
    }

    function build_all_types_report()
    {
        $html = $this->build_header();

        $omit_headers = true;

        $html .= $this->build_invoices_report(true, $omit_headers);
        $html .= $this->build_payments_report(false, $omit_headers);
        $html .= $this->build_time_entries_report(false, $omit_headers);

        $html .= $this->build_footer();

        return $html;
    }


    function build_html_for_type($type)
    {
        if ($type == 'all') {
            return $this->build_all_types_report();
        } else if ($type == 'invoices') {
            return $this->build_invoices_report(true);
        } else if ($type == 'payments') {
            return $this->build_payments_report(true);
        } else if ($type == 'time') {
            return $this->build_time_entries_report(true);
        } else return '';
    }

    function make(Reports $report, $type)
    {
        $this->load_library('dompdf-master/dompdf_config.inc');
        //require_once('server/dompdf-master/dompdf_config.inc.php');

        $paper_size = 'A4';

        $this->import_parameters($report);



        $html = $this->build_html_for_type($type);

        $dompdf = new DOMPDF;

        $dompdf->set_paper($paper_size);

        $dompdf->load_html($html);

        $dompdf->render();

        $pdf = $dompdf->output();

        $name = "report-$type-" . time() . '.pdf';

        $path = $this->path($name);

        file_put_contents($path, $pdf);

        return $name;
    }

    function path($filename)
    {
        return "tmp/$filename";
    }

    function download($file_path)
    {
        $file_path = $this->path($file_path);

        if (!file_exists($file_path))
            return false;

        //todo:if headers are the same for downloading other files, they should be stored in one place App:download_headers(); or better yet File::init_download($file_path) and it handles the whole thing
        //turn off error reporting to prevent the document from being corrupted
        error_reporting(0);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        ob_clean();
        flush();
        readfile($file_path);
        unlink($file_path);
        exit;
    }
}