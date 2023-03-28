<template>
    <div class="bg-app">
        <div class="container">
            <div class="row jutify-content-center login-center">
                <div class="col-md-6" style="float:none; margin:auto;">
                    <div class="card card-default cardLogin">
                        <div class="card-header"><h5>Email</h5></div>
                        <div class="card-body">

                            <form>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail </label>
                                    <div class="col-md-8">
                                        <input id="email" type="email" class="form-control" v-model="email" required
                                               autofocus autocomplete="off" placeholder="napr. Jozko123@gmail.com">
                                    </div>
                                </div>

                                <div class="form-group row mt-1 mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-success" @click="handleSubmitResetPassword">
                                            Reset Hesla
                                        </button>
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
    name: "ForgotPassword",
    data() {
        return{
            email : '',
        }
    },
    methods: {
        handleSubmitResetPassword() {
            console.log(this.email)
            this.$axios.post('/forgot-password', {
                email: this.email,
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
    }
}
</script>

<style scoped>

</style>
