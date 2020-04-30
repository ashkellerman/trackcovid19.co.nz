<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138141237-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-138141237-2');
    </script>

    <title>{{ config('app.name', 'Track Covid-19') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.0.1/dist/alpine.js" defer></script>

<div>
    <header class="relative z-10 border-b border-gray-200 bg-white flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-end">
          <a href="/" class="block">
            <img class="h-12 w-auto" src="https://sc.edu/imgs/callout/icon_germ.png" alt="Track Covid-19">
        
          </a>
          <p class="hidden md:block ml-4 pt-1 text-sm leading-5 text-gray-500">
            Track Covid-19<br>
            Last updated {{ $statsLastUpdated }}
          </p>
        </div>
      
        <div class="flex text-sm leading-5">
            {{-- <a href="/global" class="font-medium text-gray-500 hover:text-gray-900">
                Global
            </a>
            <a href="/stats" class="ml-4 font-medium text-gray-500 hover:text-gray-900 sm:ml-12">
                Stats
            </a>
            <a href="/log" class="ml-4 font-medium text-gray-500 hover:text-gray-900 sm:ml-12">
                Log
            </a> --}}
        </div>
    </header>

  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold leading-tight text-gray-900">
        New Zealand Current Stats
      </h1>
    </div>
  </header>
  <main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex flex-wrap px-4 sm:px-0">
            <div class="w-full mb-5">
                <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                    <div class="flex">
                        <div class="py-1">
                          <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold">
                              New Zealand is currently at Alert Level 4 – Eliminate
                            </p>
                            <p class="text-sm pt-2">
                              This means that it is likely that the disease is not contained and you should stay home.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="mx-auto w-full mb-8">
              <div>
                  <div class="flex flex-wrap">
                      <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                          <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                              <div class="flex-auto p-4">
                                  <div class="flex flex-wrap">
                                      <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                        <h5 class="text-gray-500 uppercase font-bold text-xs mb-2">
                                          Active cases
                                        </h5>
                                        <span class="font-semibold text-xl text-gray-800">
                                          {{ $statActiveCases }}
                                        </span>
                                      </div>
                                      <div class="relative w-auto pl-4 flex-initial">
                                          <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                            <i class="far fa-chart-bar"></i>
                                          </div>
                                      </div>
                                  </div>
                                  <p class="text-sm text-gray-500 mt-4">
                                    @if(strstr($statActiveCasesPercentChange, '-'))
                                      <span class="text-red-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{$statActiveCasesPercentChange}}%  ({{ $statActiveCasesChange }})
                                      </span>
                                    @else
                                      <span class="text-green-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{$statActiveCasesPercentChange}}%
                                      </span>
                                    @endif
                                    <span class="whitespace-no-wrap">Since 24 hours ago</span>
                                  </p>
                              </div>
                          </div>
                      </div>
                      <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                          <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                              <div class="flex-auto p-4">
                                  <div class="flex flex-wrap">
                                      <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                          <h5 class="text-gray-500 uppercase font-bold text-xs mb-2">Recovered cases</h5>
                                          <span class="font-semibold text-xl text-gray-800">
                                            {{ $statRecoveredCases }}
                                          </span>
                                        </div>
                                      <div class="relative w-auto pl-4 flex-initial">
                                          <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-green-500">
                                            <i class="fas fa-chart-pie"></i>
                                          </div>
                                      </div>
                                  </div>
                                  <p class="text-sm text-gray-500 mt-4">
                                    @if(strstr($statRecoveredCasesPercentChange, '-'))
                                      <span class="text-red-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{$statRecoveredCasesPercentChange}}%
                                      </span>
                                    @else
                                      <span class="text-green-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{$statRecoveredCasesPercentChange}}%
                                      </span>
                                    @endif
                                    <span class="whitespace-no-wrap">Since 24 hours ago</span>
                                  </p>
                              </div>
                          </div>
                      </div>
                      <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                          <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                              <div class="flex-auto p-4">
                                  <div class="flex flex-wrap">
                                      <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                          <h5 class="text-gray-500 uppercase font-bold text-xs mb-2">Critical Cases</h5>
                                          <span class="font-semibold text-xl text-gray-800">
                                            {{ $statCriticalCases }}
                                          </span>
                                        </div>
                                      <div class="relative w-auto pl-4 flex-initial">
                                          <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                            <i class="fas fa-users"></i>
                                          </div>
                                      </div>
                                  </div>
                                  <p class="text-sm text-gray-500 mt-4">
                                    @if(strstr($statCriticalCasesPercentChange, '-'))
                                      <span class="text-red-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{ $statCriticalCasesPercentChange }}%
                                      </span>
                                    @else
                                      <span class="text-green-500 mr-2">
                                        <i class="fas fa-arrow-up"></i> {{ $statCriticalCasesPercentChange }}%
                                      </span>
                                    @endif
                                    <span class="whitespace-no-wrap">Since 24 hours ago</span>
                                  </p>
                              </div>
                          </div>
                      </div>
                      <div class="w-full lg:w-6/12 xl:w-3/12 px-4">
                          <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                              <div class="flex-auto p-4">
                                  <div class="flex flex-wrap">
                                      <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                          <h5 class="text-gray-500 uppercase font-bold text-xs mb-2">Total deaths</h5>
                                          <span class="font-semibold text-xl text-gray-800">
                                            {{ $statDeaths }}
                                          </span>
                                        </div>
                                      <div class="relative w-auto pl-4 flex-initial">
                                          <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                              <i class="fas fa-percent"></i>
                                          </div>
                                      </div>
                                  </div>
                                  <p class="text-sm text-gray-500 mt-4">
                                      @if(strstr($statDeathsPercentChange, '-'))
                                        <span class="text-red-500 mr-2">
                                          <i class="fas fa-arrow-up"></i> {{ $statDeathsPercentChange }}%  ({{ $statDeathsChange }})
                                        </span>
                                      @else
                                        <span class="text-green-500 mr-2">
                                          <i class="fas fa-arrow-up"></i> {{ $statDeathsPercentChange }}%  ({{ $statDeathsChange }})
                                        </span>
                                      @endif
                                      <span class="whitespace-no-wrap">Since 24 hours ago</span>
                                    </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <div class="w-full lg:w-full md:w-1/2 lg:pl-2 md:pl-2 mb-8">
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                  {!! $thirtyDayLineChart->container() !!}
              </div>
          </div>

          <div class="w-full lg:w-1/2 md:w-1/2 lg:pr-2 md:pr-2">
                  
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                  <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                      Lastest Statistics
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                      Please see <a href="https://www.health.govt.nz/" target="_blank">health.govt.nz</a> for more details
                    </p>
                  </div>
                  <div>
                    <dl>
                      @foreach($todaysStatistics as $key => $statistic)
                      {{-- {{strpos($statistic, '+')}} --}}
                      <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dd class="text-sm leading-5 font-medium text-gray-500">
                          {{ $key }}
                        </dd>

                        @if(strpos($statistic, '+') !== false)
                        <dd class="mt-1 text-sm leading-5 text-green-500 sm:mt-0 sm:col-span-2 text-right text-right">
                          {{ $statistic }}
                        </dd>
                        @elseif(strpos($statistic, '-') !== false)
                        <dd class="mt-1 text-sm leading-5 text-red-900 sm:mt-0 sm:col-span-2 text-right text-right">
                          {{ $statistic }}
                        </dd>
                        @else
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 text-right text-right">
                          {{ $statistic }}
                        </dd>
                        @endif
                      </div>
                      @endforeach
                    </dl>
                  </div>
              </div>
          </div>
        
          <div class="w-full lg:w-1/2 md:w-1/2 lg:pl-2 md:pl-2">
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                  {!! $chart->container() !!}
              </div>
          </div>

        </div>
    </div>
  </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart->script() !!}
{!! $thirtyDayLineChart->script() !!}
</body>
</html>
