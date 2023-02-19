<template>
    <div class="bg-edit-userprofile">
        <div class="container card cardEditUserProfil">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h3 class="nadpis">Upraviť data profilu</h3>
                    <div>
                        <router-link :to="{name: 'userprofile'}" class="btn btn-success buttonEditExpens">Späť na
                            Profil
                        </router-link>
                    </div>
                </div>

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
        if (window.Laravel.user && window.Laravel.isLoggedin) {
            this.userId = window.Laravel.user.id;

            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/expensestypes')
                    .then(response => {
                        this.expensesTypes = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
        }

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
        if (window.Laravel.isLoggedin) {
            next();
        } else {
            window.location.href = "/";
        }
    }
}

</script>

<style scoped>

@media screen and (min-width: 1630px) {
    .cardEditUserProfil {
        max-width: var(--max-width-1);
        margin-top: 40px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .cardEditUserProfil {
        max-width: var(--max-width-2);
        margin-top: 40px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .cardEditUserProfil {
        max-width: var(--max-width-3);
        margin-top: 40px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .cardEditUserProfil {
        max-width: var(--max-width-4);
        margin-top: 40px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .cardEditUserProfil {
        max-width: var(--max-width-5);
        margin-top: 40px;
        border-radius: 18px;
    }
}

.bg-edit-userprofile {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.buttonEditExpens {
    border-radius: 18px;
}
</style>
