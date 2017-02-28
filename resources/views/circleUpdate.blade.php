@extends('layouts.app')

@section('content')
<div class="row">
    <div class="form-horizontal">

            <p>@{{circles.circle}}</p>
            <p>@{{circles.created_at}}</p>
            <p>@{{circles.updated_at}}</p>

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
                Vue.http.put('{{url("api/v1/circle/rename")}}' + '?token='+t + '&circle_id='+'18'+'&circle='+'poi')
                .then((param)=>{
                    this.circles=param.data;
                })
            })
        }
    })
 </script>
@endsection
