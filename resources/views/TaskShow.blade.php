@extends('layouts.app')
@section('style')
<style media="screen">
@media screen and (min-width: 992px) {
    div.evenly{
        display: table;
        width: 83%;
        table-layout: fixed;
    }
}
@media screen and (max-width: 992px) {
    div.evenly{
        display: table;
        width: 100%;
        table-layout: fixed;
    }
}
div.evenly span{
    display: table-cell;
    text-align: center;
}
.tag-item{
    border: 1px solid gray;
    border-radius: 5px;
    margin: 5px;
}
.tagging{
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
    <div class="row" v-cloak>
        <div class="panel panel-default col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2" style="border:none" v-if="task!=null">
            <p class="panel-heading text-centre" style="font-size:xx-large">@{{task.title}}</p>
            <div class="panel-body well">
                <div class="row">
                    <p class="alert alert-danger">
                        <strong>Created By :</strong>
                        @{{task_user.first_name + " " + task_user.last_name}}
                    </p>
                    <p style="font-size:x-large">@{{task.detail}}</p>

                    <ul class="list-group col-md-8 col-md-offset-2">
                        <li class="list-group-item list-group-item-info"><strong>Starts at:</strong> @{{humanize(task.from)}}</li>
                        <li class="list-group-item list-group-item-info"><strong>Ends at :</strong> @{{humanize(task.to)}}</li>
                    </ul>
                    <ul class="list-group col-md-8 col-md-offset-2">
                        <li class="list-group-item list-group-item-danger">Availability: @{{task.availability}}</li>
                        <li class="list-group-item list-group-item-danger">Privacy: @{{task.privacy}}</li>
                        <li class="list-group-item list-group-item-danger" v-if="task.type!=null">Type: @{{task.type}}</li>
                    </ul>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">Associated Users</label>
                        <div class="col-md-10">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="user in users">
                                    <a :href="'../profile?id=' + user.id">@{{user.first_name+ ' '+user.last_name}}</a>
                                    <span class="badge" v-show="user.is_approved!='1'">Pending</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="" class="control-label col-md-2">Tags</label>
                        <div class="col-md-10">

<<<<<<< HEAD
                        <div>
                            <a :href="'../tag?tag=' + tag.tag" class="tagging" v-for="tag in tags">@{{tag.tag}}</a>
                        </div>
=======
                            <div>
                                <span class="tagging" v-for="tag in tags">@{{tag.tag}}</span>
                            </div>
>>>>>>> 642d9f4392b15a46da02dc1f73f266e8f9d06de8

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="form-horizontal col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2" v-if="task.user_id == current_user.id">
            <div class="form-group">
                <div class="evenly col-md-offset-1">
                    <span>
                        <a :href="'../edit/' + task.id" class="btn btn-info">Edit </a>
                    </span>
                    <span>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete">Delete</button>
                    </span>
                </div>


                <div class="container">

                    <div class="modal fade" id="modalDelete" role="dialog">
                        <div class="modal-dialog" >
                            <div class="modal-content col-md-8 col-md-offset-2">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"> &times;
                                    </button>
                                    <h4 class="modal-title"> Are you sure? </h4>
                                </div>
                                <div class="modal-body">
                                    <button type="button" v-on:click="deleteTask" class="btn btn-danger" style="float:right" data-dismiss="modal">Yes </button>
                                    <button type="button" class="btn btn-active" data-dismiss="modal">No </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
@section('script')
    <script src="/js/moment.min.js" charset="utf-8"></script>
    <script type="text/javascript">
    /* eslint-disable indent */
    /* global Vue, _, moment */
    var app = new Vue({
        el: '#app',
        data: {
            task: Object,
            tags: [],
            users: [],
            searchresult: [],
            searchkey: '',
            tagstoadd: [],
            current_user: Object,
            task_user: Object
        },
        methods: {
            humanize: function (time) {
                return moment(time).format('LLLL');
            },

            deleteTask: function () {
                Vue.http.get('{{url("api/v1/token")}}')
                .then((response) => {
                    var token = response.data.token;
                    var url = '{{url("api/v1/task")}}?token=' + token + '&task_id=' + this.task.id;
                    Vue.http.delete(url)
                    .then((response) => {
                        if (response.status !== 200) {
                            console.log(response.statusText);
                            return;
                        }
                        window.open('{{url("/home")}}', '_self');
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
                Vue.http.get('{{url("api/v1/home/user")}}?token=' + token)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }
                    this.current_user = response.data;
                });
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
                Vue.http.get('{{url("api/v1/task/task/users")}}?token=' + token + '&task_id=' + id)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }
                    this.users = response.data;
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
    /* eslint-disable indent */
    </script>
@endsection
