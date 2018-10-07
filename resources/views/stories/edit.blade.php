@extends('layouts.app')
@section('title')
Edit Story
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    Edit Story
                </div>
                <div class="card-body">
                    <div>
                        <a href="{{route('story.index')}}" class="btn btn-md btn-primary">Go Back</a>
                    </div>
                    <hr>
                    <form action="{{route('story.update' , $story->id)}}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="title">Title
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" required="required" class="form-control"
                                autofocus="autofocus" value="{{$story->title}}">
                        </div>

                        <div class="form-group">
                            <label for="story">Story
                                <span class="text-danger">*</span>
                            </label>
                            <textarea type="text" name="story" id="story" rows="8" required="required" class="form-control">{{$story->story}}</textarea>
                        </div>

                        <div class="text-center">
                            <input type="submit" value="UPDATE" class="btn btn-md btn-primary">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("textarea").summernote();
        });
    </script>
</div>
@endsection