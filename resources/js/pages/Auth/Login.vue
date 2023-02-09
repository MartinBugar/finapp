<template>
    <div class="bg-login">
        <div class="container">
            <div class="row jutify-content-center login-center">
                <div class="col-md-6" style="float:none; margin:auto;">
                    <div class="card card-default cardLogin">
                        <div class="card-header"><h5>Prihlásenie</h5></div>
                        <div class="card-body">
                            <div v-if="errorMessage !== null"
                                 class="alert alert-danger alert-dismissible fade show align-items-center"
                                 role="alert">

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong>{{ this.errorMessage }}</strong>
                                <button v-if="errorMessage === 'Email nie je verifikovaný'" type="button"
                                        class="btn btn-primary btn-resend"
                                        aria-label="Resend email" @click="resendEmail()">Znova zaslať verifikačný email
                                </button>
                            </div>
                            <form>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail </label>
                                    <div class="col-md-8">
                                        <input id="email" type="email" class="form-control" v-model="email" required
                                               autofocus autocomplete="off" placeholder="napr. Jozko123@gmail.com">
                                    </div>
                                </div>
                                <div class="form-group row mt-1">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Heslo</label>
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control" v-model="password"
                                               required autocomplete="off" placeholder="Sem napíšte heslo :)">
                                    </div>
                                </div>

                                <div class="form-group row mt-1 mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-success" @click="handleSubmit">
                                            Prihlásiť
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-8 offset-md-4">
                                        <small class="text-muted">
                                            Ak ešte nemáte účet, prosím kliknite na
                                            <router-link to="/register">Registrovať</router-link>
                                        </small>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            error: null,
            errorMessage: null
        }
    },

    methods: {

        resendEmail() {
            console.log(this.email)
            this.$axios.post('email/verification-notification', {
                email: this.email,
                password: this.password
            })
                .then(response => {
                    if (response.data.success) {
                        // this.$router.go('/login')
                    } else {
                        this.errorMessage = response.data.message
                    }
                })
                .catch(function (error) {
                    console.error(error);
                });
        },

        handleSubmit(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/login', {
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                this.$router.go('/login')
                            } else {
                                this.errorMessage = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        }
    },

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('dashboard');
        }
        next();
    }
}
</script>

<style>
.cardLogin {
    margin-top: 30px;
    border-radius: 18px;
}

.bg-login {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.btn-resend {
    margin-left: 15px;
}
</style>
