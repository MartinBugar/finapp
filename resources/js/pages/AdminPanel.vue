<template>
    <div class="bg">
        <div class="container">
            <h3 class="hello"> Vitaj {{ this.role }}</h3>


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

        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'martyxAdminMojeFinancie123') {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
            this.role = window.Laravel.user.role;
        }
    },
    methods: {
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

<style>

.hello {
    color: white;
}
</style>
