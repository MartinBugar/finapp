<template>
    <div class="bg-edit-expens-type">
    <div class="container">
        <div class="card cardEditExpense">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Upraviť názov typu transakcie</h5>
                    <div>
                        <router-link :to="{name: 'expensestypes'}" class="btn btn-success buttonEditExpens">Zoznam typov
                            transakcií
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
                        <label>Názov typu transakcie</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" v-model="type" placeholder="Enter post name">
                    </div>

                    <!--                    <div class="form-group mb-2">-->
                    <!--                        <label>Description</label><span class="text-danger"> *</span>-->
                    <!--                        <textarea class="form-control" rows="3" v-model="description"-->
                    <!--                                  placeholder="Enter post description"></textarea>-->
                    <!--                    </div>-->

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Upraviť</button>
                </form>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: '',
            description: '',
            type: '',
            strSuccess: '',
            strError: '',
        }
    },

    beforeCreate() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/expensestypes/edit/${this.$route.params.id}`)
                .then(response => {
                    // this.description = response.data['description'];
                    this.type = response.data['type'];
                })
                .catch(function (error) {
                    console.log(error);
                });
        })
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
                formData.append('type', this.type);

                this.$axios.post(`/api/expensestypes/update/${this.$route.params.id}`, formData, config)
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

.bg-edit-expens-type {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    margin-top: 6vh;
    width: 100%;
    height: 100vh;
}
.cardEditExpense {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonEditExpens {
    border-radius: 18px;
}
</style>
