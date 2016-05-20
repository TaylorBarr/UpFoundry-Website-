<?php

class ReportsController extends Controller{

    function make(){
        $report = new Reports();
        $result = $report->make();

        Response($result);
    }


    function download($name)
    {
        $report = new Reports();
        $report->download($name);
    }

}