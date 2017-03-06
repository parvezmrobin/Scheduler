@extends('layouts.app')

@section('content')
<div class="row">
    <div class="form-horizontal col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
        <a v-for="task in tasks" href="../task" style="text-decoration:none; color:inherit">
            <div class="panel panel-default" >
                <p class="panel-heading text-centre" style="font-size:xx-large" >@{{task.title}}</p>
                <div class="panel-body">
                    <strong style="font-size:large">Starts at: @{{task.from}}</strong>
                    <br>
                    @{{task.detail}}
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
var app = new Vue({
    el:'#app',
    data: {
        tasks:[]
    },
    mounted(){
        Vue.http.get('{{url('api/v1/token')}}')
        .then((obj)=>{
            var token= obj.data['token']
            var url= '{{url('api/v1/home/tasks')}}'+ '?token=' + token
            Vue.http.get(url)
            .then((response)=>{
                if(response.status!=200)
                {
                    console.log(response.statusText);
                    return;
                }
                this.tasks=response.data;

            })
        })
    }
})
</script>
@endsection
