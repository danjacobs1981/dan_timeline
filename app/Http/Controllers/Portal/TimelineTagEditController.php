<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Tag;


class TimelineTagEditController extends Controller
{

    public function group(Timeline $timeline, Tag $tag, Request $request)
    {

        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $tag->update(['group_id' => $request->group_id]);

                return response()->json([
                    'status'=> 200,
                    'message' => 'Tag moved to group'
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function showModalDelete(Timeline $timeline, Tag $tag)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Delete Tag';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Delete Tag');
            $route = 'layouts.portal.snippets.modal.tag-delete';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'tag'));

        } else {

            abort(401);

        }
 
    }

    public function delete(Timeline $timeline, Tag $tag, Request $request)
    {

        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $tag->delete();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Tag deleted successfully',
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

