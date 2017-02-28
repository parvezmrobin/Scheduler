@extends ('layouts.app')
@section('content')
    <div class="row">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="privacy" class="control-label col-md-4">Privacy</label>
                <div class="col-md-8">
                    <select class="form-control" style="max-width:280px" >
                        <option v-for="privacy in privacies" :value="privacy.id">@{{privacy.privacy}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')
    <script type="text/javascript">
        var app=new Vue({
            el:"#app",
            data:{
                privacies:[]
            },
            mounted(){
                Vue.http.get('{{url("api/v1/token")}}')
                .then((obj)=>{
                    var t= obj.data['token']
                    Vue.http.get("{{url('api/v1/create/privacies')}}" + '?token='+t)
                    .then((param)=>{
                        this.privacies=param.data
                    })
                })
            }
        })
    </script>
@endsection
