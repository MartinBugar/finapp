<template>
    <div class="bg-userprofile">
        <div class="container card cardUserProfile">
                <div class="row login-center">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h2 class="nadpis">Profil užívateľa {{ name }}</h2>

                            <ul class="list-group mb-4">
                                <li class="list-group-item">User id : <strong> {{ userId }} </strong></li>
                                <li class="list-group-item">Meno : <strong> {{ name }} </strong></li>
                                <li class="list-group-item">Email : <strong> {{ email }} </strong></li>
                                <li class="list-group-item">Účet vytvorený : <strong> {{
                                        formatDate(createdAt)
                                    }} </strong></li>
                                <li class="list-group-item">Účet upravený : <strong> {{
                                        formatDate(updatedAt)
                                    }} </strong></li>
                            </ul>
                            <router-link :to="{name:'edituserprofile', params: {id:this.userId}}"
                                         class="btn btn-sm btn-warning">
                                Upraviť
                            </router-link>
                            <button class="btn btn-danger btn-sm m-1" @click="deleteUser(this.userId)">Odstrániť
                                užívateľský účet
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import moment from "moment/moment";

export default {
    data() {
        return {
            name: '',
            email: '',
            userId: '',
            createdAt: '',
            updatedAt: '',
            users: [],
        }
    },
    beforeCreate() {
        //
    },
    created() {
        if (window.Laravel.user && window.Laravel.isLoggedin) {
            this.userId = window.Laravel.user.id;

            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/users')
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });

            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/users/edit/${this.userId}`)
                    .then(response => {
                        this.name = response.data['name'];
                        this.email = response.data['email'];
                        this.createdAt = response.data['created_at'];
                        this.updatedAt = response.data['updated_at'];

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
        } else {
            window.location.href = "/";
        }
    },
    methods: {
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        deleteUser(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Chcete skutočne odstániť Váš účet?")) {
                    this.$axios.delete(`/api/users/delete/${id}`)
                        .then(response => {
                            let i = this.users.map(item => item.id).indexOf(id); // find index of your object
                            this.users.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;
                            window.location.href = "/";
                        })
                        .catch(function (error) {
                            existingObj.strError = "";
                            existingObj.strSuccess = error.response.data.message;
                        });
                }
            });
        }
    },

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            next();
        } else {
            window.location.href = "/";
        }
    }
}

</script>

<style scoped>

.bg-userprofile {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

@media screen and (min-width: 1630px) {
    .cardUserProfile {
        max-width: var(--max-width-1);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .cardUserProfile {
        max-width: var(--max-width-2);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .cardUserProfile {
        max-width: var(--max-width-3);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .cardUserProfile {
        max-width: var(--max-width-4);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1330px) {
    .cardUserProfile {
        max-width: var(--max-width-5);
        margin-top: 10px;
        border-radius: 18px;
    }
}

.nadpis {
    font-size: 2rem;
    font-family: "Bebas Neue", serif;
}
</style>
