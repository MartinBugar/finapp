<template>
    <div class="container">
        <h1 class="aa">{{name}}</h1>
        <h1 class="aa">{{email}}</h1>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: '',
            name: '',
            email:'',
        }
    },
    beforeCreate() {
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/users/edit/${this.userId}`)
                .then(response => {
                    console.log(response.data)
                    this.name = response.data['name'];
                    this.email = response.data['email'];

                })
                .catch(function (error) {
                    console.log(error);
                });
        })
    },
    created() {


    },
    methods: {

        updateUser(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('name', this.name);


                this.$axios.post(`/api/users/update/${1}`, formData, config)
                    .then(response => {
                        existingObj.strError = "";
                        existingObj.strSuccess = response.data.success;
                    })
                    .catch(function (error) {
                        existingObj.strSuccess = "";
                        existingObj.strError = error.response.data.message;
                    });
            });
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

<style>
.cardEditExpense {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonEditExpens {
    border-radius: 18px;
}

.aa {
    margin-top: 50px;
    color: white;
}
</style>
