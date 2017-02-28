@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="/css/select2.min.css">
@endsection

@section('content')
<div class="row col-lg-8 col-md-10 col-lg-offset-2 col-md-offset-1">
    <!-- <div class="list-group" v-for="circle in circles">
    <s-circle :circle="circle.circle" :id="circle.id"></s-circle>
</div> -->

<div class="form-horizontal">
    <div class="form-group">
        <label for="circles" class="control-label col-md-2">Select Circle</label>
        <div class="col-md-10">
            <select v-on:change="reloadMember" v-model="circle_id" class="form-control" name="circle" >
                <option :value="circle.id" v-for="circle in circles">@{{circle.circle}}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2 col-md-offset-2">
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalCreate">Create Circle </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEdit">Rename </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDelete">Delete </button>
        </div>
    </div>
    <hr>
    <div class="list-group">
        <s-member v-on:memberremove="removeMember($event)" v-for="member in members" :id="member.id" :member="member.first_name + ' ' + member.last_name"></s-member>
    </div>
    <br>
    <!-- <div class="form-group">
    <label for="users" class="control-label col-md-2">Add Member</label>
    <div class="col-md-8">
    <select id="users" class="user-select form-control" name="users">

</select>
</div>
</div> -->


<div class="form-group">
    <div class='container'>
        <div class="modal fade" id="modalCreate" role="dialog">
            <div class="modal-dialog" >
                <div class="modal-content col-md-10 col-md-offset-1">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"> &times;
                        </button>
                        <h4 class="modal-title"> Name of The Circle </h4>
                    </div>
                    <div class= "modal-body">
                        <div class="col-md-9">
                            <input id="CircleName" type="text" v-model="circle" class="form-control" name="CircleName">
                        </div>
                        <div class="col-md-3">
                            <button type="button" style="float:right" v-on:click="createCircle" class="btn btn-success" data-dismiss="modal">Create </button>
                        </div>
                        <br/>
                        <br/>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" role="dialog">
            <div class="modal-dialog" >
                <div class="modal-content col-md-10 col-md-offset-1">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"> &times;
                        </button>
                        <h4 class="modal-title"> Name of The Circle </h4>
                    </div>
                    <div class= "modal-body">
                        <div class="col-md-9">
                            <input id="CircleName" type="text" v-model="circle" class="form-control" name="CircleName">
                        </div>
                        <div class="col-md-3">
                            <button type="button" style="float:right" v-on:click="editCircle" class="btn btn-info" data-dismiss="modal">Edit </button>
                        </div>
                        <br>
                        <br>
                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDelete" role="dialog">
            <div class="modal-dialog" >
                <div class="modal-content col-md-8 col-md-offset-2">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"> &times;
                        </button>
                        <h4 class="modal-title"> Are you sure? </h4>
                    </div>
                    <div class="modal-body">
                        <button type="button" v-on:click="deleteCircle" class="btn btn-danger" style="float:right" data-dismiss="modal">Yes </button>
                        <button type="button" class="btn btn-active" data-dismiss="modal">No </button>
                    </div>

                </div>
            </div>
        </div>



    </div>

</div>
</div>
@endsection

@section('script')
<script src="/js/select2.min.js" charset="utf-8"></script>
<script type="text/javascript">
var member= {
    props:['id','member'],
    template:'<div class="list-group-item list-group-item-info">\
    @{{member}}\
    <a href="#" v-on:click="removeMember" style="float:right" class="btn btn-sm btn-danger">Delete</a>\
    </div>',
    methods: {
        removeMember: function () {
            this.$emit('memberremove', this.id)
        }

    }
}

var app = new Vue({
    el : '#app',
    data: {
        circles : [],
        circle : '',
        members: [],
        circle_id: '',
        users : [],
        user: ''
    },
    watch: {
        circle_id:function(val) {
            var index= _.findIndex(this.circles,function(o) {
                return o.id==val;
            });
            this.circle = this.circles[index].circle;
        }
    },
    components:{
        's-member':member
    },
    methods:{
        searchChanged : function () {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var toekn = response.data['token'];
                var url = '{{url("api/v1/circle/circle")}}' + '?token=' + token + '&user=' + this.user;
                Vue.http.get(url)
                .then((response) => {
                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }

                    this.users = response.data;
                })
            });
        },
        deleteCircle: function () {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data['token'];
                var url = '{{url("api/v1/circle/circle")}}' + '?token=' + token + "&circle_id=" + this.circle_id;
                Vue.http.delete(url)
                .then((response) => {
                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }

                    var i= _.findIndex(this.circles,function(o) {
                        return o.id==this.circle_id;
                    })
                    this.circles.splice(i,1);
                })
            })

        },
        createCircle: function(){
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response)=>{
                var t= response.data['token'];
                var url='{{url("api/v1/circle/create")}}' + '?token='+t+ "&circle="+this.circle;
                Vue.http.post(url)
                .then((response)=>{

                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }
                    this.circles.push(response.data);
                })

            })
        },
        reloadMember: function () {

            Vue.http.get('{{url("api/v1/token")}}')
            .then((response)=>{
                var t= response.data['token'];
                var url='{{url("api/v1/circle/members")}}'+ '?token='+ t+ "&circle_id="+ this.circle_id;
                Vue.http.get(url)
                .then((response)=>{

                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }
                    console.log(response.data);
                    this.members=response.data;
                })

            })
        },
        editCircle: function(){
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response)=>{
                var t= response.data['token'];
                var url='{{url("api/v1/circle/rename")}}'+ '?token='+ t+ '&circle_id='+ this.circle_id+ '&circle='+this.circle;
                Vue.http.put(url)
                .then((response)=>{

                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }
                    var val=this.circle_id
                    var i= _.findIndex(this.circles,function(o) {
                        return o.id==val;
                    })

                    Vue.set(this.circles,i,response.data)
                })

            })
        },
        removeMember: function (val) {
            console.log(val);

            Vue.http.get('{{url('api/v1/token')}}')
            .then((response) => {
                var token = response.data['token'];
                var url = '{{url("api/v1/circle/member")}}' + '?token=' + token + "&user_id=" + val +"&circle_id="+this.circle_id;
                Vue.http.delete(url)
                .then((response) => {
                    if(response.status != 200){
                        alert(response.statusText);
                        return;
                    }

                    var i = _.findIndex(this.members,function(o) {
                        return o.id==val;
                    });

                    this.members.splice(i, 1);
                })
            })
        }
    },

    events: {
        memberremove: function (val) {
            console.log(val);


        }
    },

    mounted(){
        Vue.http.get('{{url('api/v1/token')}}')
        .then((obj)=>{
            var t= obj.data['token'];
            Vue.http.get('{{url('api/v1/circle/circles')}}'+'?token='+t)
            .then((param)=>{
                if(param.status != 200){
                    alert(param.statusText);
                    return;
                }
                this.circles= param.data;
                this.circle_id = this.circles[0].id;
                this.reloadMember();
            })
        });

    }

})

$(".user-select").select2({
    data : app.users
})

$('.select2-search__field').attr('v-on:input', 'searchChanged')
$('.select2-search__field').attr('v-model', 'user')
</script>

@endsection
