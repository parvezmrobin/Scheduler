@extends('layouts.app')
@section('content')
<div class="row">
    <div class="panel panel-primary col-md-8 col-md-offset-2 form-horizontal">
        <p class="panel-heading text-centre" style="font-size:xx-large" >Create Task</p>


    <div class="form-group">
        <label for="taskTitle" class="control-label col-md-2 col-md-offset-2">Title</label>
        <div class="col-md-6">
            <input id="taskTitle" type="text" name="taskTitle" class="form-control" placeholder="Name of the task" required>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="from" class="control-label col-md-2 col-md-offset-2">Starts at </label>
        <div class="col-md-6">
            <input id="from" type="datetime-local" name="from" class="form-control" v-model="task.from" required>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="to" class="control-label col-md-2 col-md-offset-2">Ends at</label>
        <div class="col-md-6">
            <input id="to" type="datetime-local" name="to" class="form-control" v-model="task.to" required>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="location" class="control-label col-md-2 col-md-offset-2">Location</label>
        <div class="col-md-6">
            <input id="location" type="text" name="location" class="form-control" placeholder="Location of the task" v-model="task.location" required>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="availability" class="control-label col-md-2 col-md-offset-2">Availability</label>
        <div class="col-md-6">
            <select class="form-control" name="availability" id="availability" v-model="task.availability">
                <option value="Free">Free</option>
                <option value="Busy">Busy</option>
                <option value="Unavailable">Unavailable</option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="privacy" class="control-label col-md-2 col-md-offset-2">Privacy</label>
        <div class="col-md-6">
            <select class="form-control" name="privacy" id="privacy" v-model="task.privacy">
                <option value="Public">Public</option>
                <option value="Circle">Circle</option>
                <option value="Private">Private</option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-group" v-if="task.type!=null">
        <label for="type" class="control-label col-md-2 col-md-offset-2">Type</label>
        <div class="col-md-6">
            <select class="form-control" name="type" id="type" v-model="task.type">
                <<option value="Family">Family</option>
                <option value="Friend">Friend</option>
                <option value="Work">Work</option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="tags" class="control-label col-md-2 col-md-offset-2">tags</label>
        <div class="col-md-6">
            <span v-for="(tag, index) in tags" class="tag-item">
                @{{tag.tag}}
                <a href="#" @click="removeTag(index)">x</a>
            </span>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="tag" class="control-label col-md-2 col-md-offset-2">Search for Tag</label>
        <div class="col-md-6">
            <input id="tag" type="search" v-model="searchkey" @search="onSearch" class="form-control">
        </div>


    </div>
    <br>
    <div class="form-group">
        <div class="col-md-2 col-md-offset-6">
            <button type="button" @click="addTag" class="btn btn-info">Add Tag</button>
        </div>
    </div>
    <div class="form-group">
        <label for="users" class="control-label col-md-2 col-md-offset-2">Users</label>
        <div class="col-md-6">
            <span v-for="(user, index) in users" class="tag-item">
                @{{user.first_name+ ' '+user.last_name}}
                <a href="#" @click="removeUser(index)">x</a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="user" class="control-label col-md-2 col-md-offset-2">Search for User</label>
        <div class="col-md-6">
            <input id="user" type="search" v-model="searchkey" @search="onSearch" class="form-control">
        </div>
    </div>
    <br>
    <div class="form-group">
        <div class="col-md-2 col-md-offset-6">
            <button type="button" @click="addUser" class="btn btn-info">Add User</button>
        </div>
    </div>


</div>


</div>
@endsection
