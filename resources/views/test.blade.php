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
                Vue.http.get("{{url('api/v1/tasks') . '?token='}}" + token.data['token'])
                    .then((response) => {this.tasks = response.data})
            })
    }
})


</script>
@endsection
