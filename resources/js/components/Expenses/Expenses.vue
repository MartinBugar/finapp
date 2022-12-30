<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h3 class="card-title"><strong>Vsetky výdaje uzivatela : {{ userName }} , with user id: {{ userID }}</strong></h3>
                <div>
                    <button class="btn btn-success" type="button" @click="this.$router.push('/expenses/add')">New Post
                    </button>
                </div>
            </div>

            <table class="table table-hover table-sm table-bordered table-dark">
                <thead class="bg-dark text-light">
                <tr>
                    <th width="50" class="text-center">#</th>
                    <th width="100" class="text-center">User Id</th>
                    <th>Mesiac</th>
                    <th>Dátum</th>
                    <th>Názov</th>
                    <th>Typ</th>
                    <th>Spolu</th>
                    <th class="text-center" width="200">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr class="" v-for="(expens, index) in filteredAndSorted(expenses)" :key="expens.id">
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td class="text-center">{{ expens.userID }}.</td>
                    <td>{{ expens.name }}</td>
                    <td >{{ expens.date }}</td>
                    <td>{{ expens.description }}</td>
                    <td>{{ expens.type }}</td>
                    <td>{{ expens.value }} €</td>

                    <td class="text-center buttons" v-if="userID === expens.userID">

                        <router-link :to="{name:'editexpens', params: {id:expens.id}}" class="btn btn-sm btn-warning">Edit
                        </router-link>
                        <button class="btn btn-danger btn-sm m-1" @click="deletePost(expens.id)">Delete</button>

                    </td>
                </tr>
                </tbody>
            </table>


        </div>
    </div>


</template>

<script>
export default {
    data() {
        return {
            expenses: [],
            strSuccess: '',
            strError: '',
            userID: '',
            userName: '',
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expenses')
                .then(response => {
                    this.expenses = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        if (window.Laravel.user) {
            this.userID = window.Laravel.user.id;
            this.userName = window.Laravel.user.name;
        }
    },
    methods: {
        filteredAndSorted(expenses) {
            return this.expenses.filter(expens => {
                return expens.userID === this.userID;
            })
        },
        deletePost(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/expenses/delete/${id}`)
                        .then(response => {

                            let i = this.posts.map(item => item.id).indexOf(id); // find index of your object
                            this.posts.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;

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
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}

</script>

<style>
.buttons {
    max-height: 10px;
}
</style>
