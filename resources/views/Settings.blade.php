@extends('layouts.app')
@section('content')
<div class="row" v-cloak>
        <div class="panel panel-primary col-md-8 col-md-offset-2 form-horizontal">
            <p class="panel-heading text-centre" style="font-size:xx-large">Settings</p>
            <div class="panel-body">
                <div class="form-group">
                    <label for="availability" class="control-label col-md-4">Default Availability</label>
                    <div class="col-md-4">
                        <select class="form-control" name="availability" @change="changeAvailability" id="availability" v-model="setting.availability">
                            <option value="Free">Free</option>
                            <option value="Busy">Busy</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="privacy" class="control-label col-md-4">Default Privacy</label>
                    <div class="col-md-4">
                        <select class="form-control" name="privacy" id="privacy" @change="changePrivacy" v-model="setting.privacy">
                            <option value="Public">Public</option>
                            <option value="Circle">Circle</option>
                            <option value="Private">Private</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="type" class="control-label col-md-4">Default Type</label>
                    <div class="col-md-4">
                        <select class="form-control" name="type" id="type" @change="changeType" v-model="setting.type">
                            <option value="Family">Family</option>
                            <option value="Friend">Friend</option>
                            <option value="Work">Work</option>
                            <option value="null">None</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

</div>
@endsection

@section('script')
<script type="text/javascript">
/* global Vue */
/* eslint-disable indent */
var app = new Vue({
    el: '#app',
    data: {
        setting: {
            privacy: '',
            availability: '',
            type: ''
        }
    },
    methods: {
        changeAvailability: function () {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var availability = this.setting.availability;
                var url = '{{url("api/v1/settings")}}?token=' + token + '&availability=' + availability;
                Vue.http.put(url)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }

                    this.setting = response.data;
                });
            });
        },
        changePrivacy: function () {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var privacy = this.setting.privacy;
                var url = '{{url("api/v1/settings")}}?token=' + token + '&privacy=' + privacy;
                Vue.http.put(url)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }

                    this.setting = response.data;
                });
            });
        },
        changeType: function () {
            Vue.http.get('{{url("api/v1/token")}}')
            .then((response) => {
                var token = response.data.token;
                var type = this.setting.type;
                var url = '{{url("api/v1/settings")}}?token=' + token + '&type=' + type;
                Vue.http.put(url)
                .then((response) => {
                    if (response.status !== 200) {
                        console.log(response.statusText);
                        return;
                    }

                    this.setting = response.data;
                });
            });
        }
    },
    mounted () {
        Vue.http.get('{{url("api/v1/token")}}')
        .then((response) => {
            var token = response.data.token;
            var url = '{{url("api/v1/settings")}}?token=' + token;
            Vue.http.get(url)
            .then((response) => {
                if (response.status !== 200) {
                    console.log(response.statusText);
                    return;
                }

                this.setting = response.data;
            });
        });
    }
});
</script>
@endsection
