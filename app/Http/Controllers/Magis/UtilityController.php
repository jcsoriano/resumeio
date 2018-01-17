<?php

namespace App\Http\Controllers\Magis;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Magis\Services\TypeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UtilityController extends Controller
{
    /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Upload handler
     *
     * @param  Request $request
     * @param  string  $photo
     * @param  string  $visibility
     * @param  string  $name
     * @return json
     */
    public function upload(Request $request, $photo = 'photo', $visibility = 'private', $name = 'file')
    {
        $rules = 'required';

        if ($request->has('rules')) {
            $rules = 'required|'.$request->input('rules');
        } elseif ($photo == 'photo') {
            $rules = 'required|image';
        }

        $this->validate($request, [$name => $rules]);

        if (!$request->hasFile($name)) {
            return [
                'status' => 'error',
                'error' => 'No file uploaded.'
            ];
        }

        if (!$request->file('file')->isValid()) {
            return [
                'status' => 'error',
                'error' => 'There was an error in uploading.'
            ];
        }
        
        $path = $request->has('path') ? $request->input('path') : 'misc';
        
        $response = [
            'status' => 'success',
            'path' => 'storage/'.$request->$name->store($path, $visibility == 'public' ? 'public' : 'local'),
        ];
        
        return $response;
    }

    /**
     * Returns the typeMap array
     *
     * @return array
     */
    public function typeMap()
    {
        return ['typeMap' => TypeService::$map];
    }

    public function barMultiple()
    {
        $data = [
            [
                "year" => 2005,
                "income" => 23.5,
                "expenses" => 18.1
            ],
            [
                "year" => 2006,
                "income" => 26.2,
                "expenses" => 22.8
            ],
            [
                "year" => 2007,
                "income" => 30.1,
                "expenses" => 23.9
            ],
            [
                "year" => 2008,
                "income" => 29.5,
                "expenses" => 25.1
            ],
            [
                "year" => 2009,
                "income" => 24.6,
                "expenses" => 25
            ]
        ];
        return view('magis.samples.graph', compact('data'));
    }

    public function calendar()
    {
        $events = [
            [
                'title' => 'All Day Event',
                'start' => '2016-12-01'
            ],
            [
                'title' => 'Long Event',
                'start' => '2016-12-07',
                'end' => '2016-12-10'
            ],
            [
                'id' => 999,
                'title' => 'Repeating Event',
                'start' => '2016-12-09T16 =>00 =>00'
            ],
            [
                'id' => 999,
                'title' => 'Repeating Event',
                'start' => '2016-12-16T16 =>00 =>00'
            ],
            [
                'title' => 'Conference',
                'start' => '2016-12-11',
                'end' => '2016-12-13'
            ],
            [
                'title' => 'Meeting',
                'start' => '2016-12-12T10 =>30 =>00',
                'end' => '2016-12-12T12 =>30 =>00'
            ],
            [
                'title' => 'Lunch',
                'start' => '2016-12-12T12 =>00 =>00'
            ],
            [
                'title' => 'Meeting',
                'start' => '2016-12-12T14 =>30 =>00'
            ],
            [
                'title' => 'Happy Hour',
                'start' => '2016-12-12T17 =>30 =>00'
            ],
            [
                'title' => 'Dinner',
                'start' => '2016-12-12T20 =>00 =>00'
            ],
            [
                'title' => 'Birthday Party',
                'start' => '2016-12-13T07 =>00 =>00'
            ],
            [
                'title' => 'Click for Google',
                'url' => 'http =>//google.com/',
                'start' => '2016-12-28'
            ]
        ];

        return view('magis.defaults.calendar', compact('events'));
    }

    public function pie()
    {
        $data = [
            [
                "country" => "Lithuania",
                "value" => 260
            ],
            [
                "country" => "Ireland",
                "value" => 201
            ],
            [
                "country" => "Germany",
                "value" => 65
            ],
            [
                "country" => "Australia",
                "value" => 39
            ],
            [
                "country" => "UK",
                "value" => 19
            ],
            [
                "country" => "Latvia",
                "value" => 10
            ]
        ];
        return view('magis.samples.graph', compact('data'));
    }

    public function test()
    {
        MetricSnapshot::create([
            'users' => User::count(),
            'user_verified' => User::where('verified', '=', 1)->count(),
            'resumes' => Resume::count(),
            'individual_verified' => User::verified()->individuals()->count(),
            'companies' => User::companies()->count(),
            'company_verified' => User::companies()->verified()->count(),
            'job_postings' => JobPosting::count(),
            'job_questionnaires' => JobQuestionnaire::count(),
            'job_applications' => JobApplication::count(),
            'job_questionnaire_answers' => JobQuestionnaireAnswer::count(),
        ]);
    }

    public function bar()
    {
        $data = [
            [
                "country" => "USA",
                "visits" => 4025,
            ], [
                "country" => "China",
                "visits" => 1882,
            ], [
                "country" => "Japan",
                "visits" => 1809,
            ], [
                "country" => "Germany",
                "visits" => 1322,
            ], [
                "country" => "UK",
                "visits" => 1122,
            ], [
                "country" => "France",
                "visits" => 1114,
            ], [
                "country" => "India",
                "visits" => 984,
            ], [
                "country" => "Spain",
                "visits" => 711,
            ], [
                "country" => "Netherlands",
                "visits" => 665,
            ], [
                "country" => "Russia",
                "visits" => 580,
            ], [
                "country" => "South Korea",
                "visits" => 443,
            ], [
                "country" => "Canada",
                "visits" => 441,
            ], [
                "country" => "Brazil",
                "visits" => 395,
            ], [
                "country" => "Italy",
                "visits" => 386,
            ], [
                "country" => "Australia",
                "visits" => 384,
            ], [
                "country" => "Taiwan",
                "visits" => 338,
            ], [
                "country" => "Poland",
                "visits" => 328,
            ]
        ];

        return view('magis.samples.graph', compact('data'));
    }

    /**
     * Put some test code here and access it via /test
     *
     * @return Illuminate\Http\Response
     */
    public function line()
    {
        $data = [
            [
                "date" => "2012-07-27",
                "value" => 13
            ], [
                "date" => "2012-07-28",
                "value" => 11
            ], [
                "date" => "2012-07-29",
                "value" => 15
            ], [
                "date" => "2012-07-30",
                "value" => 16
            ], [
                "date" => "2012-07-31",
                "value" => 18
            ], [
                "date" => "2012-08-01",
                "value" => 13
            ], [
                "date" => "2012-08-02",
                "value" => 22
            ], [
                "date" => "2012-08-03",
                "value" => 23
            ], [
                "date" => "2012-08-04",
                "value" => 20
            ], [
                "date" => "2012-08-05",
                "value" => 17
            ], [
                "date" => "2012-08-06",
                "value" => 16
            ], [
                "date" => "2012-08-07",
                "value" => 18
            ], [
                "date" => "2012-08-08",
                "value" => 21
            ], [
                "date" => "2012-08-09",
                "value" => 26
            ], [
                "date" => "2012-08-10",
                "value" => 24
            ], [
                "date" => "2012-08-11",
                "value" => 29
            ], [
                "date" => "2012-08-12",
                "value" => 32
            ], [
                "date" => "2012-08-13",
                "value" => 18
            ], [
                "date" => "2012-08-14",
                "value" => 24
            ], [
                "date" => "2012-08-15",
                "value" => 22
            ], [
                "date" => "2012-08-16",
                "value" => 18
            ], [
                "date" => "2012-08-17",
                "value" => 19
            ], [
                "date" => "2012-08-18",
                "value" => 14
            ], [
                "date" => "2012-08-19",
                "value" => 15
            ], [
                "date" => "2012-08-20",
                "value" => 12
            ], [
                "date" => "2012-08-21",
                "value" => 8
            ], [
                "date" => "2012-08-22",
                "value" => 9
            ], [
                "date" => "2012-08-23",
                "value" => 8
            ], [
                "date" => "2012-08-24",
                "value" => 7
            ], [
                "date" => "2012-08-25",
                "value" => 5
            ], [
                "date" => "2012-08-26",
                "value" => 11
            ], [
                "date" => "2012-08-27",
                "value" => 13
            ], [
                "date" => "2012-08-28",
                "value" => 18
            ], [
                "date" => "2012-08-29",
                "value" => 20
            ], [
                "date" => "2012-08-30",
                "value" => 29
            ], [
                "date" => "2012-08-31",
                "value" => 33
            ], [
                "date" => "2012-09-01",
                "value" => 42
            ], [
                "date" => "2012-09-02",
                "value" => 35
            ], [
                "date" => "2012-09-03",
                "value" => 31
            ], [
                "date" => "2012-09-04",
                "value" => 47
            ], [
                "date" => "2012-09-05",
                "value" => 52
            ], [
                "date" => "2012-09-06",
                "value" => 46
            ], [
                "date" => "2012-09-07",
                "value" => 41
            ], [
                "date" => "2012-09-08",
                "value" => 43
            ], [
                "date" => "2012-09-09",
                "value" => 40
            ], [
                "date" => "2012-09-10",
                "value" => 39
            ], [
                "date" => "2012-09-11",
                "value" => 34
            ], [
                "date" => "2012-09-12",
                "value" => 29
            ], [
                "date" => "2012-09-13",
                "value" => 34
            ], [
                "date" => "2012-09-14",
                "value" => 37
            ], [
                "date" => "2012-09-15",
                "value" => 42
            ], [
                "date" => "2012-09-16",
                "value" => 49
            ], [
                "date" => "2012-09-17",
                "value" => 46
            ], [
                "date" => "2012-09-18",
                "value" => 47
            ], [
                "date" => "2012-09-19",
                "value" => 55
            ], [
                "date" => "2012-09-20",
                "value" => 59
            ], [
                "date" => "2012-09-21",
                "value" => 58
            ], [
                "date" => "2012-09-22",
                "value" => 57
            ], [
                "date" => "2012-09-23",
                "value" => 61
            ], [
                "date" => "2012-09-24",
                "value" => 59
            ], [
                "date" => "2012-09-25",
                "value" => 67
            ], [
                "date" => "2012-09-26",
                "value" => 65
            ], [
                "date" => "2012-09-27",
                "value" => 61
            ], [
                "date" => "2012-09-28",
                "value" => 66
            ], [
                "date" => "2012-09-29",
                "value" => 69
            ], [
                "date" => "2012-09-30",
                "value" => 71
            ], [
                "date" => "2012-10-01",
                "value" => 67
            ], [
                "date" => "2012-10-02",
                "value" => 63
            ], [
                "date" => "2012-10-03",
                "value" => 46
            ], [
                "date" => "2012-10-04",
                "value" => 32
            ], [
                "date" => "2012-10-05",
                "value" => 21
            ], [
                "date" => "2012-10-06",
                "value" => 18
            ], [
                "date" => "2012-10-07",
                "value" => 21
            ], [
                "date" => "2012-10-08",
                "value" => 28
            ], [
                "date" => "2012-10-09",
                "value" => 27
            ], [
                "date" => "2012-10-10",
                "value" => 36
            ], [
                "date" => "2012-10-11",
                "value" => 33
            ], [
                "date" => "2012-10-12",
                "value" => 31
            ], [
                "date" => "2012-10-13",
                "value" => 30
            ], [
                "date" => "2012-10-14",
                "value" => 34
            ], [
                "date" => "2012-10-15",
                "value" => 38
            ], [
                "date" => "2012-10-16",
                "value" => 37
            ], [
                "date" => "2012-10-17",
                "value" => 44
            ], [
                "date" => "2012-10-18",
                "value" => 49
            ], [
                "date" => "2012-10-19",
                "value" => 53
            ], [
                "date" => "2012-10-20",
                "value" => 57
            ], [
                "date" => "2012-10-21",
                "value" => 60
            ], [
                "date" => "2012-10-22",
                "value" => 61
            ], [
                "date" => "2012-10-23",
                "value" => 69
            ], [
                "date" => "2012-10-24",
                "value" => 67
            ], [
                "date" => "2012-10-25",
                "value" => 72
            ], [
                "date" => "2012-10-26",
                "value" => 77
            ], [
                "date" => "2012-10-27",
                "value" => 75
            ], [
                "date" => "2012-10-28",
                "value" => 70
            ], [
                "date" => "2012-10-29",
                "value" => 72
            ], [
                "date" => "2012-10-30",
                "value" => 70
            ], [
                "date" => "2012-10-31",
                "value" => 72
            ], [
                "date" => "2012-11-01",
                "value" => 73
            ], [
                "date" => "2012-11-02",
                "value" => 67
            ], [
                "date" => "2012-11-03",
                "value" => 68
            ], [
                "date" => "2012-11-04",
                "value" => 65
            ], [
                "date" => "2012-11-05",
                "value" => 71
            ], [
                "date" => "2012-11-06",
                "value" => 75
            ], [
                "date" => "2012-11-07",
                "value" => 74
            ], [
                "date" => "2012-11-08",
                "value" => 71
            ], [
                "date" => "2012-11-09",
                "value" => 76
            ], [
                "date" => "2012-11-10",
                "value" => 77
            ], [
                "date" => "2012-11-11",
                "value" => 81
            ], [
                "date" => "2012-11-12",
                "value" => 83
            ], [
                "date" => "2012-11-13",
                "value" => 80
            ], [
                "date" => "2012-11-14",
                "value" => 81
            ], [
                "date" => "2012-11-15",
                "value" => 87
            ], [
                "date" => "2012-11-16",
                "value" => 82
            ], [
                "date" => "2012-11-17",
                "value" => 86
            ], [
                "date" => "2012-11-18",
                "value" => 80
            ], [
                "date" => "2012-11-19",
                "value" => 87
            ], [
                "date" => "2012-11-20",
                "value" => 83
            ], [
                "date" => "2012-11-21",
                "value" => 85
            ], [
                "date" => "2012-11-22",
                "value" => 84
            ], [
                "date" => "2012-11-23",
                "value" => 82
            ], [
                "date" => "2012-11-24",
                "value" => 73
            ], [
                "date" => "2012-11-25",
                "value" => 71
            ], [
                "date" => "2012-11-26",
                "value" => 75
            ], [
                "date" => "2012-11-27",
                "value" => 79
            ], [
                "date" => "2012-11-28",
                "value" => 70
            ], [
                "date" => "2012-11-29",
                "value" => 73
            ], [
                "date" => "2012-11-30",
                "value" => 61
            ], [
                "date" => "2012-12-01",
                "value" => 62
            ], [
                "date" => "2012-12-02",
                "value" => 66
            ], [
                "date" => "2012-12-03",
                "value" => 65
            ], [
                "date" => "2012-12-04",
                "value" => 73
            ], [
                "date" => "2012-12-05",
                "value" => 79
            ], [
                "date" => "2012-12-06",
                "value" => 78
            ], [
                "date" => "2012-12-07",
                "value" => 78
            ], [
                "date" => "2012-12-08",
                "value" => 78
            ], [
                "date" => "2012-12-09",
                "value" => 74
            ], [
                "date" => "2012-12-10",
                "value" => 73
            ], [
                "date" => "2012-12-11",
                "value" => 75
            ], [
                "date" => "2012-12-12",
                "value" => 70
            ], [
                "date" => "2012-12-13",
                "value" => 77
            ], [
                "date" => "2012-12-14",
                "value" => 67
            ], [
                "date" => "2012-12-15",
                "value" => 62
            ], [
                "date" => "2012-12-16",
                "value" => 64
            ], [
                "date" => "2012-12-17",
                "value" => 61
            ], [
                "date" => "2012-12-18",
                "value" => 59
            ], [
                "date" => "2012-12-19",
                "value" => 53
            ], [
                "date" => "2012-12-20",
                "value" => 54
            ], [
                "date" => "2012-12-21",
                "value" => 56
            ], [
                "date" => "2012-12-22",
                "value" => 59
            ], [
                "date" => "2012-12-23",
                "value" => 58
            ], [
                "date" => "2012-12-24",
                "value" => 55
            ], [
                "date" => "2012-12-25",
                "value" => 52
            ], [
                "date" => "2012-12-26",
                "value" => 54
            ], [
                "date" => "2012-12-27",
                "value" => 50
            ], [
                "date" => "2012-12-28",
                "value" => 50
            ], [
                "date" => "2012-12-29",
                "value" => 51
            ], [
                "date" => "2012-12-30",
                "value" => 52
            ], [
                "date" => "2012-12-31",
                "value" => 58
            ], [
                "date" => "2013-01-01",
                "value" => 60
            ], [
                "date" => "2013-01-02",
                "value" => 67
            ], [
                "date" => "2013-01-03",
                "value" => 64
            ], [
                "date" => "2013-01-04",
                "value" => 66
            ], [
                "date" => "2013-01-05",
                "value" => 60
            ], [
                "date" => "2013-01-06",
                "value" => 63
            ], [
                "date" => "2013-01-07",
                "value" => 61
            ], [
                "date" => "2013-01-08",
                "value" => 60
            ], [
                "date" => "2013-01-09",
                "value" => 65
            ], [
                "date" => "2013-01-10",
                "value" => 75
            ], [
                "date" => "2013-01-11",
                "value" => 77
            ], [
                "date" => "2013-01-12",
                "value" => 78
            ], [
                "date" => "2013-01-13",
                "value" => 70
            ], [
                "date" => "2013-01-14",
                "value" => 70
            ], [
                "date" => "2013-01-15",
                "value" => 73
            ], [
                "date" => "2013-01-16",
                "value" => 71
            ], [
                "date" => "2013-01-17",
                "value" => 74
            ], [
                "date" => "2013-01-18",
                "value" => 78
            ], [
                "date" => "2013-01-19",
                "value" => 85
            ], [
                "date" => "2013-01-20",
                "value" => 82
            ], [
                "date" => "2013-01-21",
                "value" => 83
            ], [
                "date" => "2013-01-22",
                "value" => 88
            ], [
                "date" => "2013-01-23",
                "value" => 85
            ], [
                "date" => "2013-01-24",
                "value" => 85
            ], [
                "date" => "2013-01-25",
                "value" => 80
            ], [
                "date" => "2013-01-26",
                "value" => 87
            ], [
                "date" => "2013-01-27",
                "value" => 84
            ], [
                "date" => "2013-01-28",
                "value" => 83
            ], [
                "date" => "2013-01-29",
                "value" => 84
            ], [
                "date" => "2013-01-30",
                "value" => 81
            ]
        ];
        return view('magis.samples.graph', compact('data'));
    }

    /**
     * Root triage
     *
     * @return Illuminate\Http\Response
     */
    public function root()
    {
        return redirect(Auth::user()->resumes()->first()->slug);
    }

    /**
     * Debugging helper that outputs permissions
     *
     * @return Illuminate\Http\Response
     */
    public function sessions()
    {
        return session('permissions');
    }

    /**
     * Log-out endpoint so you can log-out with a 'GET' request
     *
     * @return Illuminate\Http\Response
     */
    public function logout()
    {
        return '<form id="form" action="'.url('logout').'" method="POST">'.csrf_field().'</form>'
                .'<script>document.getElementById("form").submit()</script>';
    }

    /**
     * Returns all images in a directory
     *
     * @param  Request $request
     * @return array
     */
    public function gallery(Request $request)
    {
        $this->validate($request, ['path' => 'required']);
        $path = $request->input('path');

        if (!File::exists(storage_path('app/public/'.$path))) {
            return [
                'status' => 'success',
                'files' => [],
            ];
        }
        
        $files = array_map(function ($file) {
            return basename($file);
        }, File::files(storage_path('app/public/'.$path)));

        return [
            'status' => 'success',
            'files' => array_values($files)
        ];
    }
}
