<template>
    <div class="bg">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">mojeFinancie</a>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                        aria-expanded="false" aria-label="Toggle navigation"></button>
                <div class="navbar-nav" v-if="isLoggedIn">
                    <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                    <router-link to="/posts" class="nav-item nav-link">Príjem</router-link>
                    <router-link to="/expenses" class="nav-item nav-link">Výdaje</router-link>
                    <router-link to="/expensestypes" class="nav-item nav-link">typy</router-link>1

                    <div class="dropdown">
                        <button
                            class="btn btn-danger dropdown-toggle"
                            type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Úver {{value}}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" role="menu">
                            <li v-for="option in options" :key="option">
                                <a class="dropdown-item dropdown-loans" @click="value = option" href="javascript:void(0)">{{option}}</a>
                            </li>
                            <li>
                                <a class="dropdown-item dropdown-loans-control" >Správa uverov</a>
                            </li>
                        </ul>
                    </div>

                    <div class="logout">
                        <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
                    </div>

                </div>
                <div class="navbar-nav" v-else>
                    <router-link to="/" class="nav-item nav-link">Home</router-link>
                    <router-link to="/login" class="nav-item nav-link">Login</router-link>
                    <router-link to="/register" class="nav-item nav-link">Register</router-link>
                </div>
            </div>

        </nav>
        <router-view></router-view>
    </div>
</template>
<script>



export default {
    name: "App",
    components: {

    },
    data() {
        return {
            isLoggedIn: false,
            options: ['Auto', 'Hypoteka', 'Krsna'],
            value: ''
        }
    },
    created() {
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
.logout {
    padding-left: 50px;
}

.bg {
    width: 100%;
    height: 100vh;
    background-color: #b6b6b6;
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
</style>
