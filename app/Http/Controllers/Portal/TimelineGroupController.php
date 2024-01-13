<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Timeline;
use App\Models\Group;

class TimelineGroupController extends Controller
{

    /**
     * Display a listing of the group.
     */
    public function index(): never
    {
        abort(404);
    }

    /**
     * Show the form for creating a new group.
     */
    public function create(Timeline $timeline, Request $request)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Create New Group';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Create Group', 'form' => 'formGroupCreateEdit');
            $route = 'layouts.portal.pages.timeline.group.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline'));

        } else {

            abort(401);

        }

    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Timeline $timeline, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $data = $request->validate(
                    [
                        'group' => [
                            'required',
                            'string',
                            'max:50',
                            Rule::unique('groups')->where(function ($query) use ($request, $timeline_id){
                                $query->where('timeline_id', $timeline_id);
                                $query->where('group', $request['group']);
                            })
                        ]
                    ],
                    $messages = [
                        'group.required' => 'The group requires a title',
                        'group.max' => 'The group must be 50 characters or less',
                        'group.unique' => 'A group with this title already exists'
                    ]
                );

                $data['timeline_id'] = $timeline_id;

                // add the tag
                $id = Group::create($data)->id;

                return response()->json([
                    'status'=> 200,
                    'message' => 'Group created successfully',
                    'group' => $data['group'],
                    'group_id' => $id,
                    'update' => false
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }

    }

    /**
     * Display the specified source.
     */
    public function show(string $id): never
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Timeline $timeline, Group $group)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Edit Group';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Update Group', 'form' => 'formGroupCreateEdit');
            $route = 'layouts.portal.pages.timeline.group.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'group'));

        } else {

            abort(401);

        }

    }

    /**
     * Update the specified group in storage.
     */
    public function update(Timeline $timeline, Group $group, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                if ($request->group != $group->group) { // check something has changed

                    $data = $request->validate(
                        [
                            'group' => [
                                'required',
                                'string',
                                'max:50',
                                Rule::unique('groups')->where(function ($query) use ($request, $timeline_id, $group){
                                    $query->where('timeline_id', $timeline_id);
                                    $query->where('group', $request['group']);
                                    $query->where('id', '!=', $group->id);
                                })
                            ]
                        ],
                        $messages = [
                            'group.required' => 'The group requires a title',
                            'group.max' => 'The group must be 50 characters or less',
                            'group.unique' => 'A group with this title already exists'
                        ]
                    );

                    //dd($data);
        
                    // update the group
                    Group::where('timeline_id', $timeline_id)->where('id', $group->id)->update($data);

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Group updated successfully',
                        'group' => $data['group'],
                        'group_id' => $group->id,
                        'update' => true
                    ]);
    
                } else {

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Group remains the same',
                        'group' => $group->group,
                        'group_id' => $group->id,
                        'update' => true
                    ]);

                }

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }

    }

    /**
     * Remove the specified source from storage.
     */
    public function destroy(string $id): never
    {
        abort(404);
    }

}