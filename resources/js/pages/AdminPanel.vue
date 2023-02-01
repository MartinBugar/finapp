<template>
    <div class="bg">
        <div class="container">
            <h3> Vitajte {{ this.role }}</h3>


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
                <tr class="" v-for="(user, index) in this.users" :key="user.id">
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
    </div>
</template>

<script>


export default {
    name: "Dashboard",
    computed: {},
    data() {
        return {
            userId: null,
            name: null,
            role: null,
            email: null,
            users: [],
        }
    },
    created() {
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

        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
            this.role = window.Laravel.user.role;
        }
    },
    methods: {},

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'ADMIN') {
            next();
        } else {
            window.location.href = "/";
        }
    }
}
</script>

<style>

.mainCardDashboard {
    margin-top: 10px;
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
    border-radius: 18px;
}

.cardDashboard {
    margin-top: 30px;
    margin-bottom: 30px;
    border-radius: 18px;
}

/*.bg-main-page-top {*/
/*    background: linear-gradient(90deg, #3A92C1 0%, #3A92C1 24%, #3A92C1 34%, #fff 100%);*/
/*    width: 100%;*/
/*    height: 400px;*/
/*}*/

</style>
