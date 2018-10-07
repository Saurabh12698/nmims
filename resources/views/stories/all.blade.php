@extends('layouts.app')
@section('title')
All stories
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    All stories
                </div>
                <div class="card-body">
                    <div class="text-right">
                        <a href="{{route('story.create')}}" class="btn btn-md btn-primary">Add new</a>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-stripped">
                            <tr>
                                <th>id</th>
                                <th>title</th>
                                <th>posted on</th>
                                <th>view</th>
                                <th>edit</th>
                                <th>delete</th>
                            </tr>
                            @forelse($stories as $story)
                            <tr>
                                <td>{{$story->id}}</td>
                                <td>{{$story->title}}</td>
                                <td>{{$story->created_at}}</td>
                                <td><a href="{{route('story.show' , $story->id)}}" class="btn btn-sm btn-primary">view</a></td>
                                <td><a href="{{route('story.edit' , $story->id)}}" class="btn btn-sm btn-secondary">edit</a></td>
                                <td><a href="javascript:void(0)" data-object-id="{{$story->id}}" class="btn btn-sm btn-danger btn-delete">delete</a></td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No stories posted yet.</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="formDelete" method="POST" action="/admin/story/">
        @csrf
        @method('DELETE')
    </form>
</div>
<script>
    $(document).ready(function () {
        $(".btn-delete").click(function(){
            if (window.confirm("Are you sure to delete this product?\nThis action cannot be undone !")) {
                var action = $("#formDelete").attr("action") + $(this).attr("data-object-id");
                $("#formDelete").attr("action", action);
                $("#formDelete").submit();
                $(this).html('wait...');
            }
        });
    });
</script>
@endsection