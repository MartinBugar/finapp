<template>
    <div class="bg-admin-panel">
        <div class="container card mainCardAdminPanel">
            <div class="card cardAdminPanel">
                <div class="container mt-2">
                    <h2>admin panel</h2>
                </div>

                <div class="row">
                    <div class="col-8">

                        <table class="table table-hover table-sm table-bordered table-dark">
                            <thead class="bg-dark text-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <!--                            <th width="100" class="text-center">User Id</th>-->
                                <th>user ID</th>
                                <th>name</th>
                                <th>email</th>
                                <th>role</th>

                                <!--                        <th class="text-center" width="200">Actions</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="" v-for="(user, index) in this.sortedUsers()" :key="user.id">
                                <td class="text-center">{{ index + 1 }}.</td>
                                <!--                            <td class="text-center">{{ post.userID }}.</td>-->
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role }}</td>


                                <!--                            <router-link :to="{name:'editpost', params: {id:post.id}}"-->
                                <!--                                         class="btn btn-sm btn-warning">-->
                                <!--                                Upraviť-->
                                <!--                            </router-link>-->
                                <!--                            <button class="btn btn-danger btn-sm m-1" @click="deletePost(post.id)">Odstrániť-->
                                <!--                            </button>-->

                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-3">
                        <ul class="list-group">
                            <li class="list-group-item">Východ Slnka o <strong>
                                {{
                                    getSunriseWithLocation(getLatitude(), getLongitude()).getHours()
                                }}:{{
                                    getSunriseWithLocation(getLatitude(), getLongitude()).getMinutes()
                                }}</strong>
                                <b-icon-sunrise class="icon"/>
                            </li>
                            <li class="list-group-item">Západ Slnka o <strong>
                                {{
                                    getSunsetWithLocation(getLatitude(), getLongitude()).getHours()
                                }}:{{
                                    getSunsetWithLocation(getLatitude(), getLongitude()).getMinutes()
                                }}</strong>
                                <b-icon-sunset class="icon"/>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import {getSunrise, getSunset} from "sunrise-sunset-js";

export default {
    name: "AllUsers",
    computed: {},
    data() {
        return {
            userId: null,
            name: null,
            role: null,
            email: null,
            users: [],
            location: null,
            gettingLocation: false,
            errorStr: null
        }
    },
    created() {
        //do we support geolocation
        if (!("geolocation" in navigator)) {
            this.errorStr = 'Geolocation is not available.';
            return;
        }

        this.gettingLocation = true;
        // get position
        navigator.geolocation.getCurrentPosition(pos => {
            this.gettingLocation = false;
            this.location = pos;
        }, err => {
            this.gettingLocation = false;
            this.errorStr = err.message;
        })

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/users')
                .then(response => {
                    console.log(response.data)
                    this.users = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'martyxAdminMojeFinancie123') {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
            this.role = window.Laravel.user.role;
        }
    },
    methods: {
        getLatitude() {
            if (this.gettingLocation) {
                return location.coords.latitude;
            } else {
                return 48.163895213959286;
            }
        },
        getLongitude() {
            if (this.gettingLocation) {
                return location.coords.longitude;
            } else {
                return 17.120373485390648;
            }
        },
        getSunriseWithLocation(latitude, longitude) {
            return getSunrise(latitude, longitude);
        },
        getSunsetWithLocation(latitude, longitude) {
            return getSunset(latitude, longitude);
        },

        sortedUsers() {
            return this.users.sort((a, b) => a.id - b.id);
        }
    },

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'martyxAdminMojeFinancie123' && window.Laravel.user.email === 'martin1.bugar@gmail.com') {
            next();
        } else {
            window.location.href = "/";
        }
    }
}
</script>

<style scoped>

@media screen and (min-width: 1630px) {
    .mainCardAdminPanel {
        max-width: var(--max-width-1);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .mainCardAdminPanel {
        max-width: var(--max-width-2);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .mainCardAdminPanel {
        max-width: var(--max-width-3);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .mainCardAdminPanel {
        max-width: var(--max-width-4);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .mainCardAdminPanel {
        max-width: var(--max-width-5);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

.bg-admin-panel {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.cardAdminPanel {
    margin-top: 30px;
    margin-bottom: 30px;
    border-radius: 5px;
}

.container {
    margin-top: 4vh;
}

</style>
