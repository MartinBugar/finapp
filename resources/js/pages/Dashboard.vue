<template>
    <div class="container card mainCardDashboard">
        <div class="card cardDashboard">

            <div class="container mt-2">
                <h2>Dashboard panel uživateľa {{ name }} </h2>
            </div>

            <div class="container mt-1">
                <div class="form-group mb-2 selection">
                    <label>Rok</label><span class="text-danger"> *</span>
                    <select class="form-select" @change="onChange($event)" v-model="year">
                        <option>2021</option>
                        <option>2022</option>
                        <option>2023</option>
                    </select>
                </div>


                <table class="table table-hover table-sm table-bordered table-dark">
                    <thead class="bg-dark text-light">
                    <tr>
                        <th width="100" class="text-center">Mesiac</th>
                        <th class="text-center">Prijem</th>
                        <th class="text-center">Vydaje</th>
                        <th class="text-center">Zostatok</th>

                    </tr>
                    </thead>
                    <tbody>

                    <tr class="" v-for="(date) in dates" :key="date.id">
                        <td>{{ date.name }}</td>
                        <td class="text-center">{{ sumOfPostsFromMonth(date.id) }} €</td>
                        <td class="text-center">{{ sumOfExpensesFromMonth(date.id) }} €</td>
                        <td class="text-center">{{ sumOfPostsFromMonth(date.id) - sumOfExpensesFromMonth(date.id) }} €
                        </td>
                    </tr>
                    </tbody>
                </table>

                <ul class="list-group mb-4">
                    <li class="list-group-item">Príjem za rok {{ year }} : <strong> {{ sumOfIncomPerYear(year) }}
                        € </strong></li>
                    <li class="list-group-item">Výdaje za rok {{ year }} : <strong> {{ sumOfExpensesPerYear(year) }}
                        € </strong></li>
                </ul>

            </div>
        </div>
    </div>
</template>

<script>

import Dates from "../components/Dates";

export default {
    name: "Dashboard",
    computed: {},
    data() {
        return {
            userId: null,
            name: null,
            email: null,
            posts: [],
            expenses: [],
            months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            dates: Dates,
            year: new Date().getFullYear(),
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

        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
        }
    },
    methods: {
        getMonthFromDate(date) {
            let newDate = new Date(date);
            return newDate.getMonth();
        },
        getYearFromDate(date) {
            let newDate = new Date(date);
            return newDate.getFullYear();
        },
        sumOfValues() {
            let sum = 0;
            this.posts.forEach((value) => {
                if (value.userID === this.userId) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfIncomPerYear(year) {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear().toString() === year.toString()) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfExpenses() {
            let sum = 0;
            this.expenses.forEach((value) => {
                if (value.userID === this.userId) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfExpensesPerYear(year) {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear().toString() === year.toString()) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfExpensesFromMonth(date1) {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                if (date.getFullYear().toString() === this.year.toString()) {
                    if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                        sum = sum + value.value;
                    }
                }
            });
            return sum;
        },
        sumOfPostsFromMonth(date1) {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                if (date.getFullYear().toString() === this.year.toString()) {
                    if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                        sum = sum + value.value;
                    }
                }
            });
            return sum;
        },
        filteredAndSortedPosts() {
            return this.posts.filter(post => {
                return post.userID === this.userId;
            })
        },
        filteredAndSortedExpenses() {
            return this.expenses.filter(expense => {
                return expense.userID === this.userId;
            })
        },
        onChange(event) {
            this.year = event.target.value;
        },
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
