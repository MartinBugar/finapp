<template>
    <div class="container">
        <div class="card cardEditExpense">
            <div class="card-body">

                <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ strSuccess }}</strong>
                </div>

                <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ strError }}</strong>
                </div>


                <form @submit.prevent="updatePost" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <label>Meno</label>
                        <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Upraviť užívateľské údaje</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: '',
            name: '',
            userId: '',
            strSuccess: '',
            strError: '',
        }
    },
    beforeCreate() {
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
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expensestypes')
                .then(response => {
                    this.expensesTypes = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });
    },
    methods: {

        updatePost(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('name', this.name);

                this.$axios.post(`/api/users/update/${this.userId}`, formData, config)
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
</style>
