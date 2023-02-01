<template>
    <div class="bg">
        <div class="container card mainCardDashboard">
            <div class="card cardDashboard">


                <h1>{{ this.role }}</h1>

            </div>
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
        }
    },
    created() {
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
