<template>
    <div class="bg-expenses-types">
        <div class="container card cardExpensesTypes">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h3 class="card-title"><strong>Typy transakcií</strong>
                    </h3>
                    <div>
                        <button class="btn btn-success buttonNewExpense" type="button"
                                @click="this.$router.push('/expensestypes/add')">
                            Vytvoriť nový typ transakcie
                        </button>
                    </div>
                </div>

                <table class="table table-hover table-sm table-bordered table-dark">
                    <thead class="bg-dark text-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <!--                                <th width="100" class="text-center">id</th>-->
                        <!--                                <th width="100" class="text-center">userId</th>-->
                        <th class="text-center">Typ transakcie</th>
                        <th class="text-center" width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class=""
                        v-for="(expensType, index) in filteredAndSortedExpensesTypes(expensesTypes)"
                        :key="expensType.id">
                        <td class="text-center">{{ index + 1 }}.</td>
                        <!--                                <td>{{ expensType.id }}</td>-->
                        <!--                                <td class="text-center">{{ expensType.userID }}.</td>-->
                        <td class="text-center">{{ expensType.type }}</td>

                        <td class="text-center buttons" v-if="userId === expensType.userID">
                            <router-link
                                :to="{name:'editexpensestypes', params: {id:expensType.id}}"
                                class="btn btn-sm btn-warning">
                                Upraviť
                            </router-link>
                            <button class="btn btn-danger btn-sm m-1"
                                    @click="deleteExpensType(expensType)">
                                Odstrániť
                            </button>
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
import expenses from "../Expenses/Expenses.vue";

export default {
    data() {
        return {
            expensesTypes: [],
            strSuccess: '',
            strError: '',
            userId: '',
            userName: '',
            month: new Date(Date.now()).getMonth(),
            year: new Date(Date.now()).getFullYear(),
            expensType: '',
            expenses: [],
            posts: [],
            filteredExpenses: [],
            filteredPosts: [],
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/posts')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expenses')
                .then(response => {
                    this.expenses = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expensestypes')
                .then(response => {
                    this.expensesTypes = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
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
        filteredAndSortedExpensesTypes(expensestype) {
            return expensestype.filter(expens => {
                return expens.userID === this.userId;
            })
        },
        filteredAndSortedExpenses() {
            return this.expenses.filter(expens => {
                return expens.userID === this.userId;
            })
        },
        deleteExpensType(expensType) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                this.filteredExpenses = this.expenses.filter(expens => {
                    return expens.userID === this.userId && expens.typeID === expensType.id;
                })

                this.filteredPosts = this.posts.filter(post => {
                    return post.userID === this.userId && post.typeID === expensType.id;
                })

                if (this.filteredExpenses.length > 0) {
                    alert("Cannot delete this type, because it is used by at least one Expens")
                } else if (this.filteredPosts.length > 0) {
                    alert("Cannot delete this type, because it is used by at least one Post")
                } else if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/expensestypes/delete/${expensType.id}`)
                        .then(response => {
                            let i = this.expensesTypes.map(item => item.id).indexOf(expensType.id); // find index of your object
                            this.expensesTypes.splice(i, 1);
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

.bg-expenses-types {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    min-height: 110vh;
}

.buttons {
    max-height: 10px;
}

@media screen and (min-width: 1630px) {
    .cardExpensesTypes {
        max-width: var(--max-width-1);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .cardExpensesTypes {
        max-width: var(--max-width-2);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .cardExpensesTypes {
        max-width: var(--max-width-3);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .cardExpensesTypes {
        max-width: var(--max-width-4);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .cardExpensesTypes {
        max-width: var(--max-width-5);
        margin-top: 10px;
        border-radius: 18px;
    }
}

.buttonNewExpense {
    border-radius: 18px;
}

</style>
