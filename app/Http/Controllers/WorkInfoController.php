<?php

namespace App\Http\Controllers;

use App\Models\WorkInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkInfoController extends Controller
{
    public function start_stop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:start,end',
            'time' => 'required',
            'location' => 'required:array',
            'location.lat' => 'required',
            'location.lng' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        try {
            if ($request->type == 'start')
            {

                auth()->user()->closeOngoingWorkInfo();

                $workInfo = new WorkInfo([
                    'user_id' => auth()->id(),
                    'status'  => 'ongoing',
                    'start_time' => new Carbon($request->time),
                    'start_location' => $request->location,
                    'date'  => new Carbon(),
                ]);
            }
            else
            {

                $workInfo = auth()->user()->workInfo()->where('status', 'ongoing')->first();

                $workInfo->status = 'end';
                $workInfo->end_location = $request->location;
                $workInfo->end_time = new Carbon($request->time);
                $workInfo->current_location = null;
            }

            $workInfo->save();
        }catch (\Exception $exception) {
            return response()->error('Unexpected error. Contact to developer');
        }

        return response()->json();
    }

    public function updateCurrentLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required:array',
            'location.lat' => 'required',
            'location.lng' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->error('Unexpected error. Contact to developer');
        }

        $ongoingWorkInfo = auth()->user()->workInfo()->where('status', 'ongoing')->first();

        if ($ongoingWorkInfo) {
            $ongoingWorkInfo->current_location = $request->location;
            $ongoingWorkInfo->save();
        }

        return response()->json();
    }
}
