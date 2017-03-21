@extends('layouts.app')
@section('content')
<div class="row" id="vm">
    <div class="panel panel-primary col-md-8 col-md-offset-2 form-horizontal">
        <p class="panel-heading text-centre" style="font-size:xx-large" >Create Task</p>
        <div class="alert alert-danger" v-show="error.length!=0">
            <p style="font-size:large">@{{error}}</p>
        </div>

        <div class="form-group">
            <label for="taskTitle" class="control-label col-md-4">Title</label>
            <div class="col-md-6">
                <input id="taskTitle" type="text" name="taskTitle" v-model='task.title' class="form-control" placeholder="Name of the task" >
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="from" class="control-label col-md-4">Starts at </label>
            <div class="col-md-6">
                <input id="from" type="datetime-local" name="from" class="form-control" v-model="task.from" required>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="to" class="control-label col-md-4">Ends at</label>
            <div class="col-md-6">
                <input id="to" type="datetime-local" name="to" class="form-control" v-model="task.to" required>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="location" class="control-label col-md-4">Location</label>
            <div class="col-md-6">
                <input id="location" type="text" name="location" class="form-control" placeholder="Location of the task" v-model="task.location" required>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="availability" class="control-label col-md-4">Availability</label>
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
            <label for="privacy" class="control-label col-md-4">Privacy</label>
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
            <label for="type" class="control-label col-md-4">Type</label>
            <div class="col-md-6">
                <select class="form-control" name="type" id="type" v-model="task.type">
                    <<option value="Family">Family</option>
                    <option value="Friend">Friend</option>
                    <option value="Work">Work</option>
                </select>
            </div>
        </div>
        <br>
        <div class="form-group" v-show="tags.length!=0">
            <label for="tags" class="control-label col-md-4">tags</label>
            <div class="col-md-6">
                <span v-for="(tag, index) in tags" class="tag-item">
                    @{{tag.tag}}
                    <a href="#" @click="removeTag(index)">x</a>
                </span>
            </div>
        </div>
        <br>
        <div class="form-group">
            <label for="tag" class="control-label col-md-4">Search for Tag</label>
            <div class="col-md-6">
                <input id="tag" type="search" v-model="tsearchkey" @search="tonSearch" class="form-control">
            </div>
            <div class="col-md-6 col-md-offset-4 alert alert-info" v-show="!(tag_status==='okay')">
                <span v-show="tag_status==='none'">No Result Found</span>
                <span v-show="tag_status==='search'">Searching...</span>
            </div>
        </div>
        <br>
        <div class="form-group" v-show="res_tags.length!=0">
            <div class="col-md-6 col-md-offset-4">
                <select class="form-control" multiple v-model="select_tag_id">
                    <option :value="tag.id" v-for="tag in res_tags">@{{tag.tag}}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" @click="addTag" class="btn btn-info">Add Tag</button>
            </div>
        </div>
        <div class="form-group" v-show="users.length!=0">
            <label for="users" class="control-label col-md-4">Users</label>
            <div class="col-md-6">
                <span v-for="(user, index) in users" class="tag-item">
                    @{{user.first_name+ ' '+user.last_name}}
                    <a href="#" @click="removeUser(index)">x</a>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="user" class="control-label col-md-4">Search for User</label>
            <div class="col-md-6">
                <input id="user" type="search" v-model="usearchkey" @search="uonSearch" class="form-control">
            </div>
            <div class="col-md-6 col-md-offset-4 alert alert-info" v-show="!(user_status==='okay')">
                <span v-show="user_status==='none'">No Result Found</span>
                <span v-show="user_status==='search'">Searching...</span>
            </div>

        </div>
        <br>
        <div class="form-group" v-show="res_users.length!=0">
            <div class="col-md-6 col-md-offset-4">
                <select class="form-control" multiple v-model="select_user_id">
                    <option :value="user.id" v-for="user in res_users">@{{user.first_name+ " "+user.last_name}}</option>
                </select>
            </div>


            <div class="col-md-2">
                <button type="button" @click="addUser" class="btn btn-info">Add User</button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-md-offset-4">
                <button type="button" @click="create" class="btn btn-info">Create</button>
            </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="/js/moment.min.js" charset="utf-8"></script>
    <script type="text/javascript">
    /* global Vue,_, moment */
    new Vue({
        el : '#vm',
        data : {
            task : {
                title: '',
                from: '',
                to: '',
                location: '',
                availability: '',
                privacy: '',
                type: ''
            },
            tags : [],
            users : [],
            tsearchkey : '',
            usearchkey : '',
            res_users : [],
            res_tags : [],
            select_user_id: [],
            select_tag_id: [],
            error : '',
            user_status : 'okay',
            tag_status : 'okay'
        },
        methods : {
            addUser : function () {
                for (var id in this.select_user_id) {
                    var findUser = _.findIndex(this.res_users, ['id', this.select_user_id[id]]);
                    this.users.splice(this.users.length, 0, this.res_users[findUser]);
                }
                this.usearchkey = '';
                this.res_users = [];

            },
            uonSearch : function () {
                this.user_status = 'search';
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data['token'];
                    Vue.http.get('{{url("api/v1/task/users")}}?token=' + token + '&user=' + this.usearchkey)
                    .then((response) => {
                        if(response.status !== 200) {
                            console.log(response.statusText);
                            return;
                        }
                        this.res_users = response.data;
                        if(this.res_users.length > 0) {
                            this.user_status = 'okay';
                        } else {
                            this.user_status = 'none';
                        }
                    });
                });
            },
            removeUser : function (index) {
                users.splice(index, 1);
            },
            tonSearch : function () {
                this.tag_status = 'search';
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data['token'];
                    Vue.http.get('{{url("api/v1/task/tags")}}?token=' + token + '&tag=' + this.tsearchkey)
                    .then((response) => {
                        if(response.status !== 200) {
                            console.log(response.statusText);
                            return;
                        }
                        this.res_tags = response.data;
                        if(this.res_tags.length > 0) {
                            this.tag_status = 'okay';
                        } else {
                            this.tag_status = 'none';
                        }
                    });
                });
            },
            addTag : function () {
                for (var id in this.select_tag_id) {
                    var findtag = _.findIndex(this.res_tags, ['id', this.select_tag_id[id]]);
                    this.tags.splice(this.tags.length, 0, this.res_tags[findtag]);
                }

                this.tsearchkey = '';
                this.res_tags = [];
            },
            create : function () {
                if(this.task.title.length === 0) {
                    this.error = "Title can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.from.length === 0) {
                    this.error = "Starting time can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.to.length === 0) {
                    this.error = "Closing time can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.availability.length === 0) {
                    this.error = "Availability can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.type.length === 0) {
                    this.error = "Type can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.privacy.length === 0) {
                    this.error = "Privacy can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                if(this.task.location.length === 0) {
                    this.error = "Location can't be empty";
                    window.scrollTo(0, 0);
                    return;
                }
                var newFrom = this.task.from.replace('t', ' ');
                var newTo = this.task.to.replace('t', ' ');
                if(moment(newTo).isBefore(moment(newFrom))) {
                    this.error = 'Ending time must be after strting time';
                    window.scrollTo(0, 0);
                    return;
                }
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data.token;
                    var obj = {
                        title : this.task.title,
                        from: this.task.from,
                        to: this.task.to,
                        privacy: this.task.privacy,
                        availability: this.task.availability,
                        type: this.task.type,
                        location: this.task.location,
                        tags: this.tags,
                        users: this.users
                    };
                    Vue.http.post('{{url("api/v1/task")}}?token=' + token, obj)
                    .then((response) => {
                        if (response.status !== 200) {
                            console.log(response.statusText);
                            return;
                        }
                        //window.open('{{url("task")}}/' + response.data.id, '_self');
                        console.log('created');
                    });
                });
            }
        }

    });
    </script>

    @endsection
