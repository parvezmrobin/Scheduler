@extends('layouts.app')

@section('content')
<div class="row" id="vm">
    <div class="col-md-8 col-md-offset-2" v-cloak>
        <div style="text-align: right">
            <h2>@{{user.first_name + ' ' + user.last_name}}</h2>
            Email: <a :href="'mailto:' + user.email">@{{user.email}}</a>
        </div>
        <br><br>
        <div class="panel panel-primary" v-for="task in tasks">
            <p style="font-size:x-large" class="panel-heading">@{{task.title}}</p>
            <div class="panel-body">
                <strong>Starts at</strong>: @{{format(task.from)}}
                <br>
                <strong>Ends at</strong>: @{{format(task.to)}}
                <hr>
                @{{task.detail}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="/js/moment.min.js" charset="utf-8"></script>
<script type="text/javascript">
/* eslint-disable indent */
new Vue({
    el: '#vm',
    data: {
        id: {{$_GET['id']}},
        user: Object,
        tasks: []
    },
    methods: {
        format: (time) => {
            return moment(time).format('LLLL');
        }
    },
    mounted () {
        Vue.http.get('{{url("api/v1/token")}}')
        .then((response) => {
            var token = response.data.token;

            Vue.http.get('{{url("api/v1/profile/user")}}?token=' + token + '&user_id=' + this.id)
            .then((response) => {
                this.user = response.data;
            });

            Vue.http.get('{{url("api/v1/profile/tasks")}}?token=' + token + '&user_id=' + this.id)
            .then((response) => {
                this.tasks = response.data;
            });
        });
    }
});
</script>
@endsection
