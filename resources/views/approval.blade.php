@extends('layouts.app')
@section('style')
<style media="screen">
.panel{
    border: none;
}
.btn{
    background-color: transparent;
    color: black;
}
.list-group-item{
    overflow-y: auto;
}
</style>
@endsection
@section('content')
<div class="row" id="approval" v-cloak>
    <div class="col-md-10 col-md-offset-1 form-group">
        <div class="panel panel-primary">
            <p class="panel-heading text-centre" style="font-size:xx-large">Pending Request</p>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item" v-for="task in pendings">
                        <a :href="tasklink(task.id)">@{{task.title}}</a> -
                        <a :href="'./profile?id=' + task.id">@{{task.first_name+' '+task.last_name}}</a>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group">
                                <button type="button"  class="btn btn-sm btn-success" name="button" @click="approve(task.id)" >Approve</button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  class="btn btn-sm btn-danger" name="button" @click="pdelete(task.id)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
        <div class="panel panel-primary">
            <p class="panel-heading text-centre" style="font-size:xx-large">Approved Request</p>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item" v-for="task in approveds">
                        <a :href="tasklink(task.id)">@{{task.title}}</a> -
                        <a :href="'./profile?id=' + task.id">@{{task.first_name+' '+task.last_name}}</a>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group">
                                <button type="button"  class="btn btn-sm btn-success" name="button" @click="disapprove(task.id)">Disapprove</button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  class="btn btn-sm btn-danger" name="button" @click="adelete(task.id)">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
/* global Vue, _ */
/* eslint-disable indent */
var app = new Vue({
    el: '#approval',
    data: {
        pendings: [],
        approveds: []
    },
    methods: {
        tasklink: function (id) {
            return '../task/' + id;
        },
        approve: function (id) {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var url = '{{url("api/v1/approve/approve")}}?token=' + token + '&task_id=' + id;
                Vue.http.put(url)
                .then((response) => {
                    var i = _.findIndex(this.pendings, function (o) {
                        return o.id === id;
                    });
                    this.approveds.splice(this.approveds.length, 0, this.pendings[i]);
                    this.pendings.splice(i, 1);
                });
            });
        },
        disapprove: function (id) {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var url = '{{url("api/v1/approve/disapprove")}}?token=' + token + '&task_id=' + id;
                Vue.http.put(url)
                .then((response) => {
                    var i = _.findIndex(this.approveds, function (o) {
                        return o.id === id;
                    });
                    this.pendings.splice(this.pendings.length, 0, this.approveds[i]);
                    this.approveds.splice(i, 1);
                });
            });
        },
        pdelete: function (id) {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var url = '{{url("api/v1/approve/delete")}}?token=' + token + '&task_id=' + id;
                Vue.http.delete(url)
                .then((response) => {
                    var i = _.findIndex(this.pendings, function (o) {
                        return o.id === id;
                    });
                    this.pendings.splice(i, 1);
                });
            });
        },
        adelete: function (id) {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var url = '{{url("api/v1/approve/delete")}}?token=' + token + '&task_id=' + id;
                Vue.http.delete(url)
                .then((response) => {
                    var i = _.findIndex(this.approveds, function (o) {
                        return o.id === id;
                    });
                    this.approveds.splice(i, 1);
                });
            });
        }
    },
    mounted () {
        Vue.http.get('{{url("api/v1/token")}}')
        .then((response) => {
            var token = response.data.token;
            var url = '{{url("api/v1/approve/pending")}}?token=' + token;
            Vue.http.get(url)
            .then((response) => {
                this.pendings = response.data;
            });
            url = '{{url("api/v1/approve/approved")}}?token=' + token;
            Vue.http.get(url)
            .then((response) => {
                this.approveds = response.data;
            });
        });
    }
});
</script>
@endsection
