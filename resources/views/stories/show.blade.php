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
                    View Story
                </div>
                <div class="card-body">
                  
                        @csrf
                        <div class="form-group">
                            <strong for="title">Title
                            </strong>
                           {{$story->title}}
                        </div>

                        <div class="form-group">
                            <strong for="story">Story
                            </strong>
                           
                        </div>
                        {!! $story->story !!}
                        <div class="text-center">
                                <a href="{{route('story.index')}}" class="btn btn-md btn-primary">Go Back</a>
                        </div>
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