<template>
    <div class="bg">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h3 class="card-title"><strong>Vsetky výdaje uzivatela : {{ userName }} za mesiac
                        {{ dates().at(month).name }} {{ year }}</strong></h3>
                    <div>
                        <button class="btn btn-success" type="button" @click="this.$router.push('/expenses/add')">New
                            Post
                        </button>
                    </div>
                </div>

                <div class="form-group mb-2 selection">
                    <label>Mesiac</label><span class="text-danger"> </span>
                    <select class="form-select" v-model="month">
                        <option value="0">Januar</option>
                        <option value="1">Febrar</option>
                        <option value="2">Marec</option>
                        <option value="3">April</option>
                        <option value="4">Maj</option>
                        <option value="5">Jun</option>
                        <option value="6">Jul</option>
                        <option value="7">August</option>
                        <option value="8">September</option>
                        <option value="9">Oktober</option>
                        <option value="10">November</option>
                        <option value="11">December</option>
                    </select>
                    <label>Rok</label><span class="text-danger"> *</span>
                    <select class="form-select" v-model="year">
                        <option>2021</option>
                        <option>2022</option>
                        <option>2023</option>
                    </select>
                </div>

                <table class="table table-hover table-sm table-bordered table-dark">
                    <thead class="bg-dark text-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th width="100" class="text-center">User Id</th>
                        <th>Názov</th>
                        <th>Dátum</th>
                        <th>Popis</th>
                        <th>Typ</th>
                        <th>Suma</th>
                        <th class="text-center" width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="" v-for="(expens, index) in filteredAndSorted(expenses, month)" :key="expens.id">
                        <td class="text-center">{{ index + 1 }}.</td>
                        <td class="text-center">{{ expens.userID }}.</td>
                        <td>{{ expens.name }}</td>
                        <td>{{ formatDate(expens.date) }}</td>
                        <td>{{ expens.description }}</td>
                        <td>{{ expens.type }}</td>
                        <td>{{ expens.value }} €</td>

                        <td class="text-center buttons" v-if="userID === expens.userID">

                            <router-link :to="{name:'editexpens', params: {id:expens.id}}"
                                         class="btn btn-sm btn-warning">
                                Edit
                            </router-link>
                            <button class="btn btn-danger btn-sm m-1" @click="deletePost(expens.id)">Delete</button>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import dates from "../Dates";
import moment from "moment/moment";

export default {
    data() {
        return {
            expenses: [],
            strSuccess: '',
            strError: '',
            userID: '',
            userName: '',
            month: new Date(Date.now()).getMonth(),
            year: new Date(Date.now()).getFullYear(),
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
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        dates() {
            return dates
        },
        filteredAndSorted(expenses, month) {
            return this.expenses.filter(expens => {
                let expenses = new Date(expens.date);
                if (expenses.getFullYear().toString() === this.year.toString()) {
                    if (expenses.getMonth().toString() === month.toString()) {
                        return expens.userID === this.userID;
                    }
                }
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
.bg {
    width: 100%;
    height: 100vh;
    background-color: #b6b6b6;
}
</style>
