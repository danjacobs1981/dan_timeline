<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Group;
use App\Models\Tag;

use Image;

class TimelineTagController extends Controller
{

    /**
     * Display a listing of the tag.
     */
    public function index(Timeline $timeline, Request $request)
    {

        if ($request->ajax()){

            // if an event, get tags that are saved to this event
            $tags_saved = [];
            if($request->event_id && $request->event_id != 1) { // event_id being 1 means it's a new event
                $tags_saved = Event::where('timeline_id', $timeline->id)->find($request->event_id)->tagsIDs()->all();
            }

            if ($request->sort == 'za') {
                $timeline_tags = $timeline->tags->sortByDesc('tag', SORT_NATURAL|SORT_FLAG_CASE);
            } else if ($request->sort == 'added') {
                $timeline_tags = $timeline->tags->sortByDesc('created_at');
            } else if ($request->sort == 'modified') {
                $timeline_tags = $timeline->tags->sortByDesc('updated_at');
            } else {
                $timeline_tags = $timeline->tags->sortBy('tag', SORT_NATURAL|SORT_FLAG_CASE);
            }

            //dd($timeline->tags);
            //dd($timeline->tags()->select('*', 'groups.id as group_id', 'tags.id as tag_id')->join('groups', 'groups.id', '=', 'tags.group_id')->get());
            //dd($timeline->groups()->with(['tags'])->get());
            // Group::where('timeline_id', $timeline->id)->with(['tags.groups'])->orderBy('group','asc')->get()

            $tags_html = null;
            $tags_count = 'Add a tag to get started!';
            $tags_count_saved = '<span>0</span> of 0 available tags currently selected.';

            if ($timeline_tags->count()) {

                $tags_html = view('layouts.portal.ajax.timeline.tags', [ 'timeline_groups' => $timeline->groups, 'timeline_tags' => $timeline_tags ])->render();

                $tags_count = 'A total of '.$timeline_tags->count().' tag(s) are available.';

                if (count($tags_saved)) {
                    $tags_count_saved = '<span>'.count($tags_saved).'</span> of '.$timeline_tags->count().' available tags currently selected.';
                } else {
                    $tags_count_saved = '<span>0</span> of '.$timeline_tags->count().' available tags currently selected.';
                }

            }

            return response()->json(array(
                'success' => true,
                'tags_html' => $tags_html,
                'tags_count' => $tags_count,
                'tags_count_saved' => $tags_count_saved,
                'tags_saved' => $tags_saved
            ));

        }

    }

    /**
     * Show the form for creating a new tag.
     */
    public function create(Timeline $timeline, Request $request)
    {

        /*if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Create Tag';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Create Source', 'form' => 'formSourceCreateEdit');
            $route = 'layouts.portal.pages.timeline.source.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline'));

        } else {

            abort(401);

        }*/

    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Timeline $timeline, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $data = $request->validate(
                    [
                        'tag' => [
                            'required',
                            'string',
                            'max:40',
                            'regex:/^[a-zA-Z0-9 ]+$/',
                            Rule::unique('tags')->where(function ($query) use ($request, $timeline_id){
                                $query->where('timeline_id', $timeline_id);
                                $query->where('tag', $request['tag']);
                            })
                        ],
                        'image' => 'nullable',
                        'icon' => 'nullable',
                        'group_id' => 'nullable',
                    ],
                    $messages = [
                        'tag.required' => 'The tag requires a title',
                        'tag.max' => 'The tag must be 40 characters or less',
                        'tag.regex' => 'The tag must be made up of alphanumeric characters only',
                        'tag.unique' => 'A tag with this title already exists',
                    ]
                );

                $data['timeline_id'] = $timeline_id;

                // add the tag
                $id = Tag::create($data)->id;

                return response()->json([
                    'status'=> 200,
                    'message' => 'Tag created successfully',
                    'tag_id' => $id
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
     * Show the form for editing the specified tag.
     */
    public function edit(Timeline $timeline, Tag $tag)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Edit Tag';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Update Tag', 'form' => 'formTagCreateEdit');
            $route = 'layouts.portal.pages.timeline.tag.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'tag'));

        } else {

            abort(401);

        }

    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Timeline $timeline, Tag $tag, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                if ($request->tag != $tag->tag || $request->group_id != $tag->group_id) { // check something has changed - NOTE ADD MORE HERE

                    $data = $request->validate(
                        [
                            'tag' => [
                                'required',
                                'string',
                                'max:40',
                                'regex:/^[a-zA-Z0-9 ]+$/',
                                Rule::unique('tags')->where(function ($query) use ($request, $timeline_id, $tag){
                                    $query->where('timeline_id', $timeline_id);
                                    $query->where('tag', $request['tag']);
                                    $query->where('id', '!=', $tag->id);
                                })
                            ],
                            'image' => 'nullable',
                            'icon' => 'nullable',
                            'group_id' => 'nullable',
                        ],
                        $messages = [
                            'tag.required' => 'The tag requires a title',
                            'tag.max' => 'The tag must be 40 characters or less',
                            'tag.regex' => 'The tag must be made up of alphanumeric characters only',
                            'tag.unique' => 'A tag with this title already exists',
                        ]
                    );

                    //dd($data);
        
                    // update the tag
                    Tag::where('timeline_id', $timeline_id)->where('id', $tag->id)->update($data);

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Tag updated successfully',
                        'tag_id' => $tag->id
                    ]);
    
                } else {

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Tag remains the same',
                        'tag_id' => $tag->id
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