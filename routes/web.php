<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attendance', function () {
    return view('attendance');
})->name('attendance');

Route::post('/pastorals', function (Request $request) {

    return redirect()
        ->route('pastorals', $request->only([
            'type',
            'reason',
            'motiviation',
            'consequence',
            'where',
            'teacher',
            'ethnicity',
            'tutor',
            'house',
            'year',
        ]));
})->name('pastorals.filter');

Route::get('/pastorals', function (Request $request) {

    // $data = json_decode(Storage::get("pastoral_2025-02-13_231121_7889.json"));
    // $entries = collect(data_get($data, 'SMSDirectoryData.pastoral.data'));
    // $entries = $entries->map(function ($entry) {
    //     $ent = \Wing5wong\KamarDirectoryServices\DirectoryService\PastoralData::fromArray($entry);
    //     Pastoral::updateOrCreate(
    //         ['ref' => $ent->ref],
    //         $ent->toArray()
    //     );
    //     return $ent;
    // });

    $pastorals = Cache::remember('pastoralData2', 5, function () use ($request) {
        return Pastoral::query()
            ->whereHas('student')
            ->whereYear('dateevent', Carbon\Carbon::now()->year)
            ->when($request->input('type'), function ($query) use ($request) {
                return $query->whereIn('type', $request->input('type'));
            }, function ($query) {
                return $query->whereIn('type', ['C', 'D']);
            })
            ->when($request->input('student_id'), function ($query) use ($request) {
                return $query->where('student_id', $request->input('student_id'));
            })
            ->when($request->input('surname'), function ($query) use ($request) {
                return $query->where('surname', '%' . $request->input('surname') . '%');
            })
            ->when($request->input('firstname'), function ($query) use ($request) {
                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('firstname', 'LIKE', '%' . $request->input('firstname') . '%');
                });
            })
            ->when($request->input('gender'), function ($query) use ($request) {
                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('gender', $request->input('gender'));
                });
            })
            ->when($request->input('year'), function ($query) use ($request) {
                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('yearlevel', $request->input('year'));
                });
            })
            ->when($request->input('tutor'), function ($query) use ($request) {
                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('tutor', 'LIKE', '%' . $request->input('tutor') . '%');
                });
            })
            ->when($request->input('house'), function ($query) use ($request) {

                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('house', 'LIKE', '%' . $request->input('house') . '%');
                });
            })
            ->when($request->input('ethnicity'), function ($query) use ($request) {
                return $query->whereHas('student', function ($q) use ($request) {
                    $q->where('ethnicityL1', $request->input('ethnicity'));
                });
            })
            ->when($request->input('reason'), function ($query) use ($request) {
                return $query->where('reason', 'LIKE', '%' . $request->input('reason') . '%');
            })
            ->when($request->input('motivation'), function ($query) use ($request) {
                return $query->where('motivation', 'LIKE', '%' . $request->input('motivation') . '%');
            })
            ->when($request->input('consequence'), function ($query) use ($request) {
                return $query->where('action1', 'LIKE', '%' . $request->input('consequence') . '%');
            })
            ->when($request->input('where'), function ($query) use ($request) {
                return $query->where('location', 'LIKE', '%' . $request->input('where') . '%');
            })
            ->when($request->input('teacher'), function ($query) use ($request) {
                return $query->where('teacher', $request->input('teacher'));
            })
            ->get();
    });

    // day week month
    return view('pastorals')->with([
        'pastorals' => $pastorals
    ]);
})->name('pastorals');

Route::get('/recognitions', function () {
    return view('recognitions');
})->name('recognitions');
