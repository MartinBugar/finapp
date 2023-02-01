<template>
    <div class="bg">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark main-nav">
            <div class="container">
                <a class="navbar-brand" href="/">mojeFinancie</a>
                <div class="navbar-nav" v-if="isLoggedInAsAdmin">
                    <router-link to="/adminpanel" class="nav-item nav-link">admin panel</router-link>
                </div>
                <div class="navbar-nav" v-if="isLoggedIn">
                    <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                    <router-link to="/posts" class="nav-item nav-link">Príjem</router-link>
                    <router-link to="/expenses" class="nav-item nav-link">Výdaje</router-link>
                    <router-link to="/expensestypes" class="nav-item nav-link">Typy transakcií</router-link>

                    <!--                    <div class="dropdown">-->
                    <!--                        <button-->
                    <!--                            class="btn btn-danger dropdown-toggle"-->
                    <!--                            type="button" id="dropdownMenuButton1"-->
                    <!--                            data-bs-toggle="dropdown"-->
                    <!--                            aria-expanded="false">-->
                    <!--                            Úver {{ value }}-->
                    <!--                        </button>-->
                    <!--                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" role="menu">-->
                    <!--                            <li v-for="option in options" :key="option">-->
                    <!--                                <a class="dropdown-item dropdown-loans" @click="value = option"-->
                    <!--                                   href="javascript:void(0)">{{ option }}</a>-->
                    <!--                            </li>-->
                    <!--                            <li>-->
                    <!--                                <a class="dropdown-item dropdown-loans-control">Správa uverov</a>-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </div>-->

                    <div class="logout">
                        <button class="nav-item nav-link btn btn-primary btn-sm logout-button button-radius"
                                style="cursor: pointer;"
                                @click="logout">Odhlásenie
                        </button>
                    </div>

                </div>
                <div class="navbar-nav" v-else>
                    <!--                    <router-link to="/" class="nav-item nav-link">Home</router-link>-->
                    <router-link to="/login" class="nav-item nav-link btn btn-primary button-radius">Prihlasenie
                    </router-link>
                    <router-link to="/register" class="nav-item nav-link btn btn-primary register button-radius">
                        Registracia
                    </router-link>
                </div>
            </div>

        </nav>
        <router-view></router-view>

    </div>


    <!--    <footer class="text-center text-white" style="background-color: #0a4275;">-->
    <!--        &lt;!&ndash; Grid container &ndash;&gt;-->
    <!--        <div class="container p-4 pb-0">-->
    <!--            &lt;!&ndash; Section: CTA &ndash;&gt;-->
    <!--            <section class="">-->
    <!--                <p class="d-flex justify-content-center align-items-center">-->

    <!--&lt;!&ndash;                    <button type="button" class="btn btn-outline-light btn-rounded">&ndash;&gt;-->
    <!--&lt;!&ndash;                        Register&ndash;&gt;-->
    <!--&lt;!&ndash;                    </button>&ndash;&gt;-->
    <!--                </p>-->
    <!--            </section>-->
    <!--            &lt;!&ndash; Section: CTA &ndash;&gt;-->
    <!--        </div>-->
    <!--        &lt;!&ndash; Grid container &ndash;&gt;-->

    <!--        &lt;!&ndash; Copyright &ndash;&gt;-->
    <!--        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">-->
    <!--            © 2020 Copyright:-->
    <!--            <a class="text-white" href="https://mojefinancie.eu">mojefinancie.eu</a>-->
    <!--        </div>-->
    <!--        &lt;!&ndash; Copyright &ndash;&gt;-->
    <!--    </footer>-->


</template>
<script>


export default {
    name: "App",
    components: {},
    data() {
        return {
            isLoggedIn: false,
            isLoggedInAsAdmin: false,
            options: ['Auto', 'Hypoteka', 'Krsna'],
            value: '',

        }
    },
    beforeCreate() {

    },
    created() {
        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'ADMIN') {
            this.isLoggedInAsAdmin = true
        }
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
    },
    methods: {
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>

<style>
.bg {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.logout {
    padding-left: 50px;
}

.dropdown-loans-control {
    background-color: #b40000;
}

.dropdown-loans-control:hover {
    background-color: #238500;
    cursor: pointer;
}

.dropdown-loans:hover {
    background-color: #238500;
}

.register {
    margin-left: 15px;
}

.button-radius {
    border-radius: 18px;
    margin-top: 2px;
    padding: 5px;
    /*padding-top: 5px;*/
}

.main-nav {

    min-height: 60px;
    width: 100vw;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 2;
}


</style>
