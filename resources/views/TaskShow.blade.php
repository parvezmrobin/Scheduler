@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="panel panel-default col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2" style="border:none" v-if="task!=null">
            <p class="panel-heading text-centre" style="font-size:xx-large">@{{task.title}}</p>
            <div class="panel-body">
                <p style="font-size:x-large">@{{task.detail}}</p>

                    <ul class="list-group col-md-8 col-md-offset-2">
                        <li class="list-group-item list-group-item-info"><strong>Starts at:</strong> @{{task.from}}</li>
                        <li class="list-group-item list-group-item-info"><strong>Ends at :</strong> @{{task.to}}</li>
                    </ul>
                    <ul class="list-group col-md-8 col-md-offset-2">
                        <li class="list-group-item list-group-item-danger">Availability: @{{task.availability}}</li>
                        <li class="list-group-item list-group-item-danger">Privacy: @{{task.privacy}}</li>
                        <li class="list-group-item list-group-item-danger" v-if="task.type!=null">Type: @{{task.type}}</li>
                    </ul>


            </div>
        </div>
    </div>

@endsection
@section('script')
<script type="text/javascript">
var app=new Vue({
    el:'#app',
    data:{
        task: null
    },
    mounted(){
        var url=window.location.href;
        var i= url.lastIndexOf("/");
        var id= url.substring(i+1);
        Vue.http.get('{{url('api/v1/token')}}')
        .then((response)=>{
            var token= response.data['token']
            Vue.http.get('{{url('api/v1/task')}}'+'?token='+token+'&id='+id)
            .then((response)=>{
                if(response.status!=200){
                    console.log(response.status);
                    return;
                }
                this.task=response.data;

            })
        })
    }
})
</script>
@endsection
