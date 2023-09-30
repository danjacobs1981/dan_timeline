<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Timeline;

class TimelineEditController extends Controller
{

    public function settings(Request $request, string $id)
    {

        // ajax for timeline settings
        if($request->ajax()){
        
            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $this->validate($request,[
                    'title' => 'required', // this needs to be decent validation for title
                ]);

                $timeline->title = $request->title;
                $timeline->slug = Str::slug($request->title, "-");
                $timeline->comments = $request->comments;
                
                $timeline->save();

                return response()->json([
                    'status'=>200,
                    'message' => 'Settings updated successfully',
                ]);

            } else {

                return response()->json([
                    'status'=>401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function privacy(Request $request, string $id)
    {

        // ajax for timeline privacy
        if($request->ajax()){
        
            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline->privacy = $request->privacy;
                
                $timeline->save();

                return response()->json([
                    'status'=>200,
                    'message' => 'Privacy updated successfully',
                ]);

            } else {

                return response()->json([
                    'status'=>401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function privacyShare(Request $request, string $id)
    {

        // ajax for timeline privacy sharing email addresses
        if($request->ajax()){
        
            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline->privacy = $request->privacy;
                
                $timeline->save();

                return response()->json([
                    'status'=>200,
                    'message' => 'Privacy sharing updated successfully',
                ]);

            } else {

                return response()->json([
                    'status'=>401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function showModalPrivacy(string $id)
    {

            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $modal_title = 'Privacy Options';
                $route = 'layouts.portal.snippets.modal.edit-privacy';
                $routeParams = array();
                return view('layouts.modal.master', compact('modal_title', 'route', 'routeParams', 'timeline'));

            } else {

                abort(401);

            }
 
    }

    public function showModalPrivacyShare(string $id)
    {

            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $modal_title = 'Share timeline privately';
                $route = 'layouts.portal.snippets.edit-privacy-share';
                $routeParams = array();
                return view('layouts.modal.master', compact('modal_title', 'route', 'routeParams', 'timeline'));

            } else {

                abort(401);

            }
 
    }

}
