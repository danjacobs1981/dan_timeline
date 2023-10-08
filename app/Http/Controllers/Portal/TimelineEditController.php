<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Timeline;
use App\Models\Select;

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
                    'status'=> 200,
                    'message' => 'Settings updated successfully',
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
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
                    'status'=> 200,
                    'message' => 'Privacy updated successfully',
                    'result' => $timeline->privacy
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
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

                $data = $request->data;

                Select::where('timeline_id', $timeline->id)->delete();

                if($data) {
                    foreach($data as $value) {
                        $store = new Select;
                        $store->timeline_id = $timeline->id;
                        $store->email = $value['value'];
                        $store->save();
                    }
                }

                return response()->json([
                    'status'=> 200,
                    'message' => 'Privacy sharing updated successfully',
                    'result' => $data
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function showModalPrivacy(string $id)
    {

            $timeline = Timeline::find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $privateUsers = $timeline->privateUsers;

                $modal_title = 'Privacy Options';
                $modal_buttons = array('close' => 'Done');
                $route = 'layouts.portal.snippets.modal.edit-privacy';
                return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'privateUsers'));

            } else {

                abort(401);

            }
 
    }

    public function showModalPrivacyShare(string $id)
    {

            $timeline = Timeline::select(['id', 'user_id'])->find($id);

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $privateUsers = $timeline->privateUsers->toJson();

                $modal_title = 'Share timeline privately';
                $modal_buttons = array('close' => 'Cancel', 'action' => 'Save');
                $route = 'layouts.portal.snippets.edit-privacy-share';
                return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'privateUsers'));

            } else {

                abort(401);

            }
 
    }

}
