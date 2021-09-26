<?php

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Models\Activity;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $request = RequestHelper::replaceIfExits(
            $request,
            [
                'num' => RequestHelper::sanitizeNumber($request->input('num')),
                'dateStart' => RequestHelper::sanitizeString($request->input('dateStart')),
            ]
        );
        $rules = ['num' => 'required|integer',
            'dateStart' => 'date',
        ];
        $this->validate($request, $rules);

        $dateStart = $request->query('dateStart');

        $ac = Activity::where('date_end', '>=', $dateStart)
            ->where('date_ini', '<=', $dateStart)->orderBy('popularity', 'DESC')->get();
        // dd($users);
        return $this->successResponse($ac);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        //
    }
}