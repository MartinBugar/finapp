<template>
    <div class="container">
        <div>
            User Id : {{ userId }}
        </div>
        <div>
            Username : {{ name }}
        </div>
        <div>
            Email : {{ email }}
        </div>
    </div>

    <div class="container mt-4">
        <ul class="list-group">
            <li class="list-group-item">Spolu za rok 2022 : <strong> {{ sumOfValuesFrom2022() }} € </strong></li>
            <li class="list-group-item">Spolu celkom: <strong> {{ sumOfValues() }} € </strong></li>
            <li class="list-group-item">Spolu vydaje za rok 2022 : <strong> {{ sumOfExpensesFrom2022() }} € </strong>
            </li>
            <li class="list-group-item">Spolu vydaje celkom: <strong> {{ sumOfExpenses() }} € </strong></li>
        </ul>

        <table class="table table-hover table-sm table-bordered table-dark mt-4">
            <thead class="bg-dark text-light">
            <tr>
                <th width="50" class="text-center">#</th>
                <th>Mesiac</th>
                <th>Prijem</th>
                <th>Vydaje</th>
                <th>Zostatok</th>

            </tr>
            </thead>
            <tbody>
            <tr class="" v-for="(date, index) in dates" >
                <td class="text-center">{{ index + 1 }}.</td>
                <td class="text-center">{{ date}}.</td>
                <td>{{ sumOfPostsFromMonth(date) }} €</td>
                <td>{{ sumOfExpensesFromMonth(date)}} €</td>
                <td>{{sumOfPostsFromMonth(date) - sumOfExpensesFromMonth(date)}} €</td>

            </tr>
            </tbody>
        </table>

    </div>


</template>

<script>
import posts from "../components/Income/Posts.vue";
import expenses from "../components/Expenses/Expenses.vue";

export default {
    name: "Dashboard",
    computed: {
        // posts() {
        //     return posts
        // }
    },
    data() {
        return {
            userId: null,
            name: null,
            email: null,
            posts: [],
            expenses: [],
            dates : [1,2,3,4,5,6,7,8,9,10,11,12],
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
        sumOfValuesFrom2022() {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear() === 2022) {
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
        sumOfExpensesFrom2022() {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear() === 2022) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfExpensesFromMonth(date1) {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                console.log(date.getMonth() + 1)
                console.log(date1)
                if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfPostsFromMonth(date1) {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                console.log(date.getMonth() + 1)
                console.log(date1)
                if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                    sum = sum + value.value;
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
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
