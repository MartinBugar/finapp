<template>
    <div class="bg-app">

        <div class="sidebar" v-if="isSideBarVisible()">
            <div class="admin-panel" v-if="isLoggedInAsAdmin">
                <router-link to="/adminpanel" class="nav-item nav-link">admin panel</router-link>
            </div>
            <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
            <router-link to="/posts" class="nav-item nav-link">Príjem</router-link>
            <router-link to="/expenses" class="nav-item nav-link">Výdaje</router-link>
            <router-link to="/expensestypes" class="nav-item nav-link">Typy transakcií</router-link>
        </div>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark main-nav">
            <div class="container">
                <a class="navbar-brand" href="/">mojeFinancie</a>

                <div class="navbar-nav" v-if="isLoggedIn">
                    <router-link to="/aboutus" class="nav-item nav-link">O nás</router-link>
                    <router-link to="/contactus" class="nav-item nav-link contactus">Kontakt</router-link>

                    <!--                   <div class="dropdown">-->
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
                        <!--                        <button class="nav-item nav-link btn btn-primary btn-sm logout-button button-radius"-->
                        <!--                                style="cursor: pointer;"-->
                        <!--                                @click="logout">Odhlásenie-->
                        <!--                        </button>-->
                        <div class="dropdown">
                            <button
                                class="nav-item nav-link btn btn-primary btn-sm logout-button button-radius dropdown-toggle"
                                type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ this.name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" role="menu">
                                <li v-for="option in userOptions" :key="option">
                                    <a class="dropdown-item dropdown-loans" @click="onClick(option)"
                                       href="javascript:void(0)">{{ option }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="navbar-nav" v-else>
                    <!--                    <router-link to="/" class="nav-item nav-link">Home</router-link>-->

                    <router-link to="/aboutus" class="nav-item nav-link">O nás</router-link>
                    <router-link to="/contactus" class="nav-item nav-link contactus">Kontakt</router-link>

                    <router-link to="/login" class="nav-item nav-link btn btn-primary button-radius">Prihlásenie
                    </router-link>
                    <router-link to="/register" class="nav-item nav-link btn btn-primary register button-radius">
                        Registrácia
                    </router-link>
                </div>
            </div>

        </nav>


        <router-view></router-view>


        <footer class="text-center text-white footer">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: CTA -->
                <section class="">
                    <p class="d-flex justify-content-center align-items-center">

                        <!--                    <button type="button" class="btn btn-outline-light btn-rounded">-->
                        <!--                        Register-->
                        <!--                    </button>-->
                    </p>
                </section>
                <!-- Section: CTA -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2022 Copyright:
                <a class="text-white" href="https://mojefinancie.eu">mojefinancie.eu</a>
            </div>
            <!-- Copyright -->
        </footer>


    </div>
</template>
<script>


export default {
    name: "App",
    components: {},
    data() {
        return {
            name: '',
            isLoggedIn: false,
            isLoggedInAsAdmin: false,
            options: ['Auto', 'Hypoteka', 'Krsna'],
            userOptions: ['Profil', 'Odhlasiť'],
            value: '',

        }
    },
    beforeCreate() {

    },
    created() {
        if (window.Laravel.isLoggedin && window.Laravel.user.role === 'martyxAdminMojeFinancie123' && window.Laravel.user.email === 'martin1.bugar@gmail.com') {
            this.isLoggedInAsAdmin = true
            this.name = window.Laravel.user.name;
        }
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
            this.name = window.Laravel.user.name;
        }
    },
    methods: {

        isSideBarVisible() {

            if (this.isLoggedIn) {
                return true;
            } else {
                return false;
            }
        },
        onClick(option) {
            if (option === 'Odhlasiť') {
                this.logout();
            }

            if (option === "Profil") {
                window.location.href = "/userprofile";
            }
        },

        logout() {
            // e.preventDefault()
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

<style scoped>

.admin-panel {
    margin-bottom: 40px;
}

.contactus {
    margin-right: 30px;
}


.bg-app {
    background-color: var(--bg-secondary);
    padding-top: 6vh;
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
    max-height: 60px;
    width: 100vw;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 2;
}

.sidebar {
    padding: 0;
    /*padding-top: 6vh;*/
    margin: 0;
    width: 150px;
    background-color: #1a1e21;
    position: fixed;
    height: 100vh;
    overflow: auto;
    z-index: 2;
}

/*Sidebar links */
.sidebar a {
    display: block;
    color: #ffffff;
    /*padding: 16px;*/
    margin: 10px;
    text-decoration: none;
    border-bottom: white;
}


.router-link-exact-active {
    background: #07a2b4;
    border-radius: 10px;
}

/* Links on mouse-over */
.sidebar a:hover:not(.active) {
    background-color: #0ce6ff;
    color: white;
    border-radius: 10px;
}

/* Page content. The value of the margin-left property should match the value of the sidebar's width property */
div.content {
    margin-left: 200px;
    padding: 1px 16px;
    padding-top: 6vh;
    height: 100vh;
}

/* On screens that are less than 700px wide, make the sidebar into a topbar */
@media screen and (max-width: 700px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .sidebar a {
        float: left;
    }

    div.content {
        margin-left: 0;
    }
}

/* On screens that are less than 400px, display the bar vertically, instead of horizontally */
@media screen and (max-width: 400px) {
    .sidebar a {
        text-align: center;
        float: none;
    }
}

footer {
    background-color: #0a4275;
    position: relative;
    z-index: 2;
}


</style>
