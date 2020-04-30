<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\TotalCasesPieChart;
use App\Charts\ThirtyDayLineChart;
use App\NewZealandSummary;
use Zttp\Zttp;
use App\Country;
use App\Statistic;
use Zttp\ZttpResponse;
use Carbon\Carbon;
use DB;


class PageController extends Controller
{
    protected $lastThirtyDays;
    protected $lastThirtyDaysTotalCasesData;
    protected $lastThirtyDaysTotalRecoveredCasesData;


    public function welcome()
    {
        // Set country of data wanted for main page.
        $country = Country::where('name', 'New-Zealand')->first();

        $statisticsOfLastThirtyDays = DB::select( DB::raw("SELECT id, cases_total, cases_recovered, recorded_at
        FROM statistics
        WHERE id IN (
            SELECT MAX(id)
            FROM statistics
            GROUP BY DATE_FORMAT(`recorded_at`, ' %e/%m/%Y')
        ) ORDER BY `recorded_at` ASC;
        ") );

        $this->generateLastThirtyDaysChartDay($statisticsOfLastThirtyDays);

        $thirtyDayLineChart = new ThirtyDayLineChart;
        $thirtyDayLineChart->labels($this->lastThirtyDays);
        $thirtyDayLineChart->dataset('Recovered Cases', 'line', $this->lastThirtyDaysTotalRecoveredCasesData)
        ->color("rgb(50, 255, 126)")
        ->backgroundcolor("rgb(50, 255, 126)");
        $thirtyDayLineChart->dataset('Active Cases', 'line', $this->lastThirtyDaysTotalCasesData)
        ->color("rgb(255, 99, 132)")
        ->backgroundcolor("rgb(255, 99, 132)");


        $statistics = Statistic::where('country_id', $country->getId())->latest('recorded_at')->first();

        $yesterday = Carbon::now()->subDays(1);
        

        $yesterdayStatistics = Statistic::where('country_id', $country->getId())
        ->whereDate('recorded_at', $yesterday )
        ->latest('recorded_at')
        ->first(); 
        dd($yesterdayStatistics);

        // Set stat cards data
        $statActiveCases = $statistics->cases_active;
        $statActiveCasesChange = $statistics->cases_new;
        $statActiveCasesPercentChange = number_format((1 - $yesterdayStatistics->cases_active / $statistics->cases_active) * 100, 2);

        $statRecoveredCases = $statistics->cases_recovered;
        $statRecoveredCasesPercentChange = number_format((1 - $yesterdayStatistics->cases_recovered / $statistics->cases_recovered) * 100, 2);

        $statCriticalCases = $statistics->cases_critical;
        $statCriticalCasesPercentChange = number_format((1 - $yesterdayStatistics->cases_critical / $statistics->cases_critical) * 100, 2);

        $statDeaths = $statistics->deaths_total;
        $statDeathsChange = $statistics->deaths_new;
        $statDeathsPercentChange = number_format((1 - $yesterdayStatistics->deaths_total / $statistics->deaths_total) * 100, 2);
            


        $statsLastUpdated = Carbon::parse($statistics->recorded_at)->diffForHumans();

        $todaysStatistics = [
            'New cases' => $statistics->cases_new,
            'Total active cases' => $statistics->cases_active,
            'Total recovered cases' => $statistics->cases_recovered,
            'Total critical cases' => $statistics->cases_critical,
            'New deaths' => $statistics->deaths_new == null ? 0 : $statistics->deaths_new,
            'Total deaths' => $statistics->deaths_total,
        ];

        $data = [
            'Active cases' => $statistics->cases_active,
            'Recovered cases' => $statistics->cases_recovered,
            'Critical cases' => $statistics->cases_critical,
            'Deaths' => $statistics->deaths_total,
        ];

        $chart = new TotalCasesPieChart;
        $chart->labels(array_keys($data));
        $chart->dataset('My dataset', 'doughnut', array_values($data))->backgroundColor(['#4299e1','#32ff7e', '#ed8936', '#f56565']);
        $chart->minimalist(1);
        $chart->displayLegend(1);

        return view('welcome', [
            'statsLastUpdated' => $statsLastUpdated,
            'todaysStatistics' => $todaysStatistics,
            'chart' => $chart,
            'thirtyDayLineChart' => $thirtyDayLineChart,
            'statActiveCases' => $statActiveCases,
            'statActiveCasesChange' => $statActiveCasesChange,
            'statActiveCasesPercentChange' => $statActiveCasesPercentChange,
            'statRecoveredCases' => $statRecoveredCases,
            'statRecoveredCasesPercentChange' => $statRecoveredCasesPercentChange,
            'statCriticalCases' => $statCriticalCases,
            'statCriticalCasesPercentChange' => $statCriticalCasesPercentChange,
            'statDeaths' => $statDeaths,
            'statDeathsChange' => $statDeathsChange,
            'statDeathsPercentChange' => $statDeathsPercentChange
        ]);
    }

    private function generateLastThirtyDaysChartDay($statisticsOfLastThirtyDays)
    {
                
        $this->lastThirtyDays = collect([]);
        foreach($statisticsOfLastThirtyDays as $dateRow) {
            $this->lastThirtyDays->push(Carbon::parse($dateRow->recorded_at)->format('d/M/Y'));
        }

        $this->lastThirtyDaysTotalCasesData = collect([]);
        foreach($statisticsOfLastThirtyDays as $dateRow) {
            $this->lastThirtyDaysTotalCasesData->push($dateRow->cases_total);
        }

        $this->lastThirtyDaysTotalRecoveredCasesData = collect([]);
        foreach($statisticsOfLastThirtyDays as $dateRow) {
            $this->lastThirtyDaysTotalRecoveredCasesData->push($dateRow->cases_recovered);
        }
    }
}
