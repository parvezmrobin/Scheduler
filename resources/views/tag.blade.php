@extends('layouts.app')

@section('content')
<div class="row" id="vm" v-cloak>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary" v-for="task in tasks">
            <p style="font-size:x-large" class="panel-heading">@{{task.title}}</p>
            <div class="panel-body">
                <strong>Task Of </strong>
                <a :href="'./profile?id=' + task.user_id">@{{task.first_name + ' ' + task.last_name}}</a>
                <br>
                <strong>Starts at</strong>: @{{format(task.from)}}
                <br>
                <strong>Ends at</strong>: @{{format(task.to)}}
                <hr>
                @{{task.detail}}
            </div>
        </div>
        <h2 class="text-error" v-if="!tasks.length">No result found</h2>
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
            tag: '{{$_GET['tag']}}',
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
                Vue.http.get('{{url("api/v1/tag")}}?token=' + token + '&tag=' + this.tag)
                .then((response) => {
                    this.tasks = response.data;
                });
            });
        }
    });
</script>
@endsection
