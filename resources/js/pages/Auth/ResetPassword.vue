<template>
    <div class="bg-app">
        <div class="container">
            <div class="row jutify-content-center login-center">
                <div class="col-md-6" style="float:none; margin:auto;">
                    <div class="card card-default cardLogin">
                        <div class="card-header"><h5>Create new password</h5></div>
                        <div class="card-body">

                            <div v-if="different_passwords"
                                 class="alert alert-danger alert-dismissible fade show align-items-center"
                                 role="alert">

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong> Zadane hesla sa nezhoduju</strong>
                            </div>

                            <div v-if="this.LoginVisible"
                                 class="alert alert-success alert-dismissible fade show align-items-center"
                                 role="alert">

                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong>Vaše heslo bolo zmenené, teraz sa môžete prihlásiť.</strong>
                            </div>

                            <form @submit.prevent="handleResetPassword">

                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">New
                                        Password </label>
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control" v-model="password"
                                               required
                                               autofocus autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group row mt-2">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">Password
                                        Confirmation</label>
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control"
                                               v-model="password_confirmation"
                                               required
                                               autofocus autocomplete="off">
                                    </div>
                                </div>

                                <div class="row mt-3">

                                    <div v-if="!this.LoginVisible" class="col-4 form-group">
                                        <button type="submit" class="btn btn-success">
                                            Reset password
                                        </button>
                                    </div>

                                    <div v-if="this.LoginVisible" class="col-3">
                                        <router-link :to="{name: 'login'}" class="btn btn-success ">
                                            Prihlásenie
                                        </router-link>
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
    name: "ResetPassword",
    data() {
        return {
            password: '',
            password_confirmation: '',
            different_passwords: false,
            LoginVisible: false,

            data: {
                token: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
    },
    methods: {
        handleResetPassword() {

            this.data.token = this.$route.params.token;
            this.data.email = this.$route.query.email;
            this.data.password = this.password;
            this.data.password_confirmation = this.password;

            if (this.password !== this.password_confirmation) {
                this.different_passwords = true;
            } else {
                this.$axios.post('/reset-password', this.data)
                    .then(response => {
                        if (response.status === 200) {
                            this.LoginVisible = true;
                            // window.location.href = "/login"
                        } else {
                            this.errorMessage = response.data.message
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            }
        },
    }
}
</script>

<style scoped>

</style>
