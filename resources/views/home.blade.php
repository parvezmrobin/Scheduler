@extends('layouts.app')

@section('content')
<div class="row" v-cloak>
    <div class="col-md-8">
        <a v-for="task in tasks" :href="makeUrl(task)" style="text-decoration:none; color:inherit">
            <div :class="chooseClass(task)" >
                <p class="panel-heading text-centre" style="font-size:xx-large" >@{{task.title}}</p>
                <div class="panel-body">

                    <strong style="font-size:large">@{{formatDateTime(task.from)}}</strong>
                    <br>
                    @{{task.detail}}
                </div>
            </div>
        </a>
    </div>

    <div class="form-group col-md-4">
        <label for="TrendingTags" class="control-label col-md-4">Trending tags</label>
        <div class="col-md-8 list-group">
            <a class="list-group-item" href="#" v-for="tag in tags" >@{{tag.tag}} <span class="badge">@{{tag.count}}</span></a>
        </div>
    </div>


</div>
@endsection

@section('script')
<script src="js/moment.min.js" charset="utf-8"></script>
<script type="text/javascript">
/* eslint-disable indent */
/* global Vue, moment */
var app = new Vue({
    el: '#app',
    data: {
        tasks: [],
        tags: []
    },
    methods: {
        formatDateTime: function (dateTime) {
            var time = moment(dateTime);

            if (time < moment()) {
                return 'Started ' + time.fromNow();
            }
            return 'Starts ' + time.fromNow();
        },
        makeUrl: function (task) {
            return '../task/' + task.id;
        },
        chooseClass: function (task) {
            var now = moment();
            if (moment(task.from) <= now && moment(task.to) > now) {
                return 'panel panel-info';
            }
            return 'panel panel-default';
        }
    },
    mounted () {
        Vue.http.get("{{url('api/v1/token')}}")
        .then((obj) => {
            var token = obj.data['token'];
            var url = '{{url("api/v1/home/tasks")}}' + '?token=' + token;
            Vue.http.get(url)
            .then((response) => {
                if (response.status !== 200) {
                    console.log(response.statusText);
                    return;
                }
                this.tasks = response.data;
            });
            url = '{{url("api/v1/home/trending")}}?token=' + token;
            Vue.http.get(url)
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
