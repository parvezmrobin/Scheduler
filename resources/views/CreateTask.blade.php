@extends('layout.app')
@section('content')
<div class="row">
    <div class="form-group">
        <label for="taskTitle" class="control-label col-md-4">Title</label>
        <div class="col-md-8">
            <input id="taskTitle" type="text" name="taskTitle" class="form-control" placeholder="Name of the task" required>
        </div>
    </div>

</div>
@endsection
