<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;


class TimelineEventEditController extends Controller
{

    public function showModalDate(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Change Event Date';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Change Date', 'form' => 'formEventEditDate');
            $route = 'layouts.portal.snippets.edit-event-date';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            abort(401);

        }

    }

    public function showModalLocation(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Event Location';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Done');
            $route = 'layouts.portal.snippets.edit-event-location';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            abort(401);

        }

    }

    public function showModalDelete(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Delete Event';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Delete Event');
            $route = 'layouts.portal.snippets.edit-event-delete';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            abort(401);

        }
 
    }

    public function delete(Timeline $timeline, Event $event, Request $request)
    {

        // ajax for timeline privacy
        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $event->delete();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Event deleted successfully',
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

