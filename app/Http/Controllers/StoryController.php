<?php

namespace App\Http\Controllers;

use App\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::latest()->get();
        return view('stories.all')->with('stories' , $stories);
    }

    public function list(){
        $stories = Story::latest()->get();
        return response()->json($stories, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stories.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'story' => 'required|string'
        ]);

        Story::create([
            'title' => $request->title,
            'story' => $request->story,
        ]);
        return redirect(route('story.index'))->with('message_success' , "New story has been posted");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story)
    {
        return view('stories.show')->with('story' , $story);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story)
    {
        return view('stories.edit')->with('story' , $story);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $request->validate([
            'title' => 'required|string',
            'story' => 'required|string'
        ]);

        $story->update([
            'title' => $request->title,
            'story' => $request->story,
        ]);
        return redirect(route('story.index'))->with('message_success' , "Story has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $story->delete();
        return redirect()->back()->with('message_success' , "Story has been deleted...");
    }
}
