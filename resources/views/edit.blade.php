@extends('layouts.app')
@section('style')
    <style media="screen">
    .tag-item{
        border: 1px solid;
        border-radius: 5px;
        border-color: inherit;
        background-color: skyblue;
        padding: 5px;
        margin: 5px;
    }
    </style>
@endsection
@section('content')
    <div class="row" v-cloak id="vm">
        <div class= "form-horizontal col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
            <div class="form-group">
                <label for="title" class="control-label col-md-2">Title</label>
                <div class="col-md-10">
                    <input id="title" type="text" name="title" class="form-control" placeholder="Write the title" v-model="task.title" required>
                </div>
            </div>
            <div class="form-group">
                <label for="detail" class="control-label col-md-2">Detail</label>
                <div class="col-md-10">
                    <textarea class="form-control" name="detail" rows="8"  placeholder="Write the detail" required v-model="task.detail"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="control-label col-md-2">Starts at </label>
                <div class="col-md-10">
                    <input id="from" type="datetime-local" name="from" class="form-control" v-model="task.from" required>
                </div>
            </div>
            <div class="form-group">
                <label for="to" class="control-label col-md-2">Ends at</label>
                <div class="col-md-10">
                    <input id="to" type="datetime-local" name="to" class="form-control" v-model="task.to" required>
                </div>
            </div>
            <div class="form-group">
                <label for="location" class="control-label col-md-2">Location</label>
                <div class="col-md-10">
                    <input id="location" type="text" name="location" class="form-control" placeholder="Location of the task" v-model="task.location" required>
                </div>
            </div>
            <div class="form-group">
                <label for="availability" class="control-label col-md-2">Availability</label>
                <div class="col-md-10">
                    <select class="form-control" name="availability" id="availability" v-model="task.availability">
                        <option value="Free">Free</option>
                        <option value="Busy">Busy</option>
                        <option value="Unavailable">Unavailable</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="privacy" class="control-label col-md-2">Privacy</label>
                <div class="col-md-10">
                    <select class="form-control" name="privacy" id="privacy" v-model="task.privacy">
                        <option value="Public">Public</option>
                        <option value="Circle">Circle</option>
                        <option value="Private">Private</option>
                    </select>
                </div>
            </div>
            <div class="form-group" v-if="task.type!=null">
                <label for="type" class="control-label col-md-2">Type</label>
                <div class="col-md-10">
                    <select class="form-control" name="type" id="type" v-model="task.type">
                        <option value="Family">Family</option>
                        <option value="Friend">Friend</option>
                        <option value="Work">Work</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label for="tags" class="control-label col-md-2">tags</label>
                <div class="col-md-10">
                    <span v-for="(tag, index) in tags" class="tag-item">
                        @{{tag.tag}}
                        <a href="#" @click="removeTag(index)">x</a>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="tag" class="control-label col-md-2">Search for Tag</label>
                <div class="col-md-10">
                    <input id="tag" type="search" v-model="searchkey" @search="onSearch" class="form-control">
                </div>

            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-2">
                    <select v-show="searchresult.length !== 0" class="form-control" multiple v-model="tagstoadd">
                        <option v-for="tag in searchresult" :value="tag.id">@{{tag.tag}}</option>
                    </select>

                </div>
                <div class="col-md-2">
                    <button type="button" @click="addTag" style="float:right" class="btn btn-info">Add Tag</button>
                </div>
                <div class="col-md-6 col-md-offset-4 alert alert-info" v-show="!(status==='okay')">
                    <span v-show="status==='none'">No Result Found</span>
                    <span v-show="status==='search'">Searching...</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-10 col-md-offset-2">
                    <button type="button" class="btn btn-success" style="float:right" @click="updateTask" >Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="/js/moment.min.js" charset="utf-8"></script>
    <script type="text/javascript">
    /* global Vue, _, moment */
    /* eslint-disable indent */
    new Vue({
        el: '#vm',
        data: {
            task: Object,
            tags: [],
            users: [],
            searchresult: [],
            searchkey: '',
            tagstoadd: [],
            status:'okay'
        },
        methods: {
            onSearch: function () {
                this.status='search';
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data.token;
                    var url = '{{url("api/v1/task/tags")}}?token=' + token + '&tag=' + this.searchkey;
                    Vue.http.get(url)
                    .then((response) => {
                        if (response.status !== 200) {
                            console.log(response.statusText);
                            return;
                        }
                        this.searchresult = response.data;
                        if (this.searchresult.length !== 0) {
                            this.status = 'okay';
                        } else {
                            this.status = 'none';
                        }
                    });
                });
            },
            addTag: function () {
                var toadd = this.tagstoadd;
                for (var i = 0; i < this.tagstoadd.length; i++) {
                    var j = _.findIndex(this.searchresult, function (o) {
                        return o.id === toadd[i];
                    });
                    this.tags.splice(this.tags.length, 0, this.searchresult[j]);
                }
                this.searchkey = '';
                this.searchresult = [];
            },
            removeTag: function (index) {
                this.tags.splice(index, 1);
            },
            updateTask: function () {
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data.token;
                    var url = '{{url("api/v1/task")}}?token=' + token;
                    var obj = {
                        id: this.task.id,
                        title: this.task.title,
                        detail: this.task.detail,
                        from: this.task.from,
                        to: this.task.to,
                        availability: this.task.availability,
                        privacy: this.task.privacy,
                        type: this.task.type,
                        location: this.task.location,
                        tags: _.mapValues(this.tags, 'id')
                    };
                    Vue.http.put(url, obj)
                    .then((response) => {
                        window.open("{{url('task')}}/" + response.data.id, '_self');
                    });
                });
            }
        },
        mounted () {
            var url = window.location.href;
            var i = url.lastIndexOf('/');
            var id = url.substring(i + 1);
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data['token'];
                Vue.http.get('{{url("api/v1/task")}}' + '?token=' + token + '&id=' + id)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.status);
                        return;
                    }
                    this.task = response.data;
                    console.log(typeof this.task.from);
                    Vue.http.get('{{url("api/v1/profile/user")}}?token=' + token + '&user_id=' + this.task.user_id)
                    .then((response) => {
                        if (response.status !== 200) {
                            console.log(response.status);
                            return;
                        }
                        this.task_user = response.data;
                    });
                    this.task.from = moment(this.task.from).format('YYYY-MM-DDTHH:mm');
                    this.task.to = moment(this.task.to).format('YYYY-MM-DDTHH:mm');
                });
                Vue.http.get('{{url("api/v1/task/taks/tags")}}?token=' + token + '&task_id=' + id)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }
                    this.tags = response.data;
                });
            });
        }
    });
    </script>
@endsection
