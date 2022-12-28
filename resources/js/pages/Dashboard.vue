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

        <div>
            Sucet hodnot : {{ sumOfValues() }} €
        </div>
    </div>
</template>

<script>
import posts from "../components/Posts.vue";

export default {
    name: "Dashboard",
    computed: {
        posts() {
            return posts
        }
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
            this.posts.forEach((value, index) => {
                if (value.userID === this.userId) {
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
