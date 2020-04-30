<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class ThirtyDayLineChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->options([ 'scales' => [ 'xAxes' => [ [ 'display' => false, ], ], 'yAxes' => [ [ 'display' => true, ], ], ], ]);
    }
}
