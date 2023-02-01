<template>
    <div class="bg">
        <div class="container card mainCardUserProfile">
            <div class="card cardUserProfile">
                <div class="card-body">
                    <h2>Profil užívateľa {{ name }}</h2>

                    <ul class="list-group mb-4">
                        <li class="list-group-item">User id : <strong> {{ userId }} </strong></li>
                        <li class="list-group-item">Meno : <strong> {{ name }} </strong></li>
                        <li class="list-group-item">Email : <strong> {{ email }} </strong></li>
                    </ul>
                    <router-link :to="{name:'edituserprofile', params: {id:this.userId}}"
                                 class="btn btn-sm btn-warning">
                        Upraviť
                    </router-link>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            userId: '',
        }
    },
    beforeCreate() {

    },
    created() {
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/users/edit/${this.userId}`)
                .then(response => {
                    console.log(response.data)
                    this.name = response.data['name'];
                    this.email = response.data['email'];

                })
                .catch(function (error) {
                    console.log(error);
                });
        })

    },
    methods: {},
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}

</script>

<style>
.mainCardUserProfile {
    margin-top: 10px;
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
    border-radius: 18px;
    padding-bottom: 20px;
}

.cardUserProfile {
    margin-top: 30px;
    border-radius: 18px;
}
</style>
