<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Timeline;

class GodController extends Controller
{

    /*public function __construct()
    {
      $this->middleware('auth')->only(['index']);
    }*/

    public function index() 
    {

		$users = User::all();
		$timelines = Timeline::all();

        return view('layouts.portal.pages.god', compact('users', 'timelines'));
        
    }

    public function users(Request $request, $action) 
    {

		$message = 'User(s) update complete!';

		if ($action == 'promote') {

			User::whereIn('id', $request->ids)->update(['premium' => 1]);
			foreach($request->ids as $id) {
				Timeline::where('user_id', $id)->update(['tagging' => 1, 'adverts' => 0]);
			}	
			$message = 'Promoted '.count($request->ids).' users to premium and updated their timelines';

		}
		
		if ($action == 'demote') {

			User::whereIn('id', $request->ids)->update(['premium' => 0]);
			foreach($request->ids as $id) {
				Timeline::where('user_id', $id)->update(['tagging' => 0, 'adverts' => 1]);
			}
			$message = 'Demoted '.count($request->ids).' users to regular and updated their timelines';
			
		}

        return back()->with('action', $message);
        
    }

    public function timelines(Request $request, $action) 
    {

		$message = 'Timeline(s) update complete!';

		if ($action == 'feature') {

			Timeline::whereIn('id', $request->ids)->update(['featured' => 1]);
			$message = 'Featured '.count($request->ids).' timelines';
		
		}
		
		if ($action == 'defeature') {

			Timeline::whereIn('id', $request->ids)->update(['featured' => 0]);
			$message = 'Defeatured '.count($request->ids).' timelines';
			
		}

        return back()->with('action', $message);
        
    }

}