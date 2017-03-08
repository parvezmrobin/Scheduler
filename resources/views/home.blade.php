@extends('layouts.app')

@section('content')
<div class="row" v-cloak>
    <div class="form-horizontal col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
        <a v-for="task in tasks" :href="makeUrl(task)" style="text-decoration:none; color:inherit">
            <div :class="chooseClass(task)" >
                <p class="panel-heading text-centre" style="font-size:xx-large" >@{{task.title}}</p>
                <div class="panel-body">
                    <strong style="font-size:large">Starts at: @{{formatDateTime(task.from)}}</strong>
                    <br>
                    @{{task.detail}}
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@section('script')
<script src="js/moment.min.js" charset="utf-8"></script>
<script type="text/javascript">
var app = new Vue({
    el:'#app',
    data: {
        tasks:[]
    },
    methods: {
        formatDateTime: function (dateTime) {
            return moment(dateTime).fromNow();
        },
        makeUrl : function (task) {
            return '../task/' + task.id
        },
        chooseClass: function (task) {
            var now = moment()
            if(moment(task.from)<=now && moment(task.to)>now){
                return 'panel panel-info';
            }
            return 'panel panel-default';
        }
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
