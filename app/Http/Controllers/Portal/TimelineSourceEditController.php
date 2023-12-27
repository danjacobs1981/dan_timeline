<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Source;


class TimelineSourceEditController extends Controller
{

    public function showModalDelete(Timeline $timeline, Source $source)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $event_count = $source->events()->count();

            $modal_title = 'Delete Source';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Delete Source');
            $route = 'layouts.portal.snippets.edit-source-delete';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'source', 'event_count'));

        } else {

            abort(401);

        }
 
    }

    public function delete(Timeline $timeline, Source $source, Request $request)
    {

        // ajax for timeline privacy
        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $source->delete();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Source deleted successfully',
                    'timeline_id' => $timeline->id
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

}

