<template>
    <div class="bg-register">
        <div class="container">
            <div class="row jutify-content-center">
                <div class="col-md-6" style="float:none; margin:auto;">
                    <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        <strong>{{ error }}</strong>
                    </div>

                    <div class="card card-default cardRegister">
                        <div class="card-header"><h5>Registrácia nového užívateľa</h5></div>
                        <div class="card-body">
                            <form>

                                <div class="form-group row">
                                    <label for="name" class="col-sm-4 col-form-label text-md-right">Meno</label>
                                    <div class="col-md-8">
                                        <input id="name" type="text" class="form-control" v-model="name" required
                                               autofocus autocomplete="off" placeholder="napr. Jozko123">
                                    </div>
                                </div>

                                <div class="form-group row mt-1">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail</label>
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
                                            Register
                                        </button>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-8 offset-md-4">
                                        <small class="text-muted">
                                            Ak už máte účet vytvorený, prosím kliknite na
                                            <router-link to="/login">Prihlásenie</router-link>
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
            name: "",
            email: "",
            password: "",
            role: '',
            error: null
        }
    },

    methods: {
        handleSubmit(e) {
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        role: 'USER'
                    })
                        .then(response => {
                            if (response.data.success) {
                                window.location.href = "/checkEmail"
                            } else {
                                this.error = response.data.message
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
.cardRegister {
    margin-top: 30px;
    border-radius: 18px;
}

.bg-register {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}
</style>
