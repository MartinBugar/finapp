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
                <th>Spolu</th>
                <th>Spolu za rok 2022</th>
                <th>Výdaje spolu</th>
                <th>Výdaje spolu za rok 2022</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ sumOfValues() }} €</td>
                <td>{{ sumOfValuesFrom2022() }} €</td>
                <td>{{ sumOfExpenses() }} €</td>
                <td>{{ sumOfExpensesFrom2022() }} €</td>
            </tr>
            </tbody>
        </table>

    </div>


</template>

<script>
import posts from "../components/Income/Posts.vue";

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
        filteredAndSorted(posts) {
            return this.posts.filter(post => {
                return post.userID === this.userID;
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
