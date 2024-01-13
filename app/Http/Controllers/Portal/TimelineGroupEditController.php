<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Group;


class TimelineGroupEditController extends Controller
{

    public function showModalDelete(Timeline $timeline, Group $group)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Delete Group';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Delete Group');
            $route = 'layouts.portal.snippets.modal.group-delete';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'group'));

        } else {

            abort(401);

        }
 
    }

    public function delete(Timeline $timeline, Group $group, Request $request)
    {

        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $group->delete();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Group deleted successfully',
                    'timeline_id' => $timeline->id,
                    'group_id' => $group->id
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

