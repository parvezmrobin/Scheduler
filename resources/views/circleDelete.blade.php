@extends('layouts.app')

@section('content')
<div class="row">
    <div class="form-horizontal">

            <p>@{{circles.status}}</p>


    </div>
</div>
@endsection

@section('script')
 <script type="text/javascript">
    var app= new Vue({
        el:'#app',
        data:{
            circles:{}
        },
        mounted(){
            Vue.http.get('{{url("api/v1/token")}}')
            .then((obj)=>{
                var t= obj.data['token'];
                Vue.http.delete('{{url("api/v1/circle/circle")}}' + '?token='+t + '&circle_id='+'4' )
                .then((param)=>{
                    this.circles=param.data;
                })
            })
        }
    })
 </script>
@endsection
