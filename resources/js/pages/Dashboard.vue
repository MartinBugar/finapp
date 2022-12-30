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
            <li class="list-group-item">Spolu : {{ sumOfValues() }} €</li>
            <li class="list-group-item">Spolu za rok 2022 : {{ sumOfValuesFrom2022() }}</li>
        </ul>
    </div>
</template>

<script>
import posts from "../components/Posts.vue";

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
            sumValues: null,
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
