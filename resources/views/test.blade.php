@extends('layouts.app')

@section('content')
<div class="row" v-for="task in tasks">
    <task :url="baseUrl + task.id" :title="task.title" :body="task.detail"></task>
</div>
@endsection

@section('script')
<script type="text/javascript">

var app = new Vue({
    el : '#app',
    data: {
        tasks : [],
        baseUrl: '{{url('post') . "/"}}'
    },
    mounted() {
        Vue.http.get("{{url('api/v1/token')}}")
            .then((token) => {
                Vue.http.post("{{url('api/v1/create/create') . '?token='}}" + token.data['token'],
                {
                    title: 'title vua',
                    from : '2017-2-4 3:3',
                    to : '2017-4-2 5:5',
                    location : 'vua location',
                    detail : 'vua detail',
                    privacy : 1,
                    availability : 2,
                    type : 3
                }).then((response) => {this.tasks = [response.data]})
            })
    }
})


</script>
@endsection
