@extends('layouts.app')

@section('content')
<div class="row">
    <div class="form-horizontal" v-for="circle in circles">

            <p>@{{circle.circle}}</p>
            <p>@{{circle.created_at}}</p>
            <p>@{{circle.updated_at}}</p>

    </div>
</div>
@endsection

@section('script')
 <script type="text/javascript">
    var app= new Vue({
        el:'#app',
        data:{
            circles:[]
        },
        mounted(){
            Vue.http.get('{{url("api/v1/token")}}')
            .then((obj)=>{
                var t= obj.data['token'];
                Vue.http.get('{{url("api/v1/circle/circles")}}' + '?token='+t)
                .then((param)=>{
                    this.circles=param.data;
                })
            })
        }
    })
 </script>
@endsection
