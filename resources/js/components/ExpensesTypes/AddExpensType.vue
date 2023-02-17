<template>
    <div class="bg-add-expenstype">
        <div class="container">
            <div class="card cardAddExpens">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h5 class="card-title">Pridať nový typ transakcie </h5>
                        <div>
                            <router-link :to="{name: 'expensestypes'}" class="btn btn-success buttonAddExpense">Zoznam
                                typov
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


                    <form @submit.prevent="addPost" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label>Názov typu transakcie</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" v-model="type" placeholder="Názov typu transakcie">
                        </div>

                        <!--                    <div class="form-group mb-2">-->
                        <!--                        <label>Description</label><span class="text-danger"> *</span>-->
                        <!--                        <textarea class="form-control" rows="3" v-model="description"-->
                        <!--                                  placeholder="Enter post description"></textarea>-->
                        <!--                    </div>-->

                        <button type="submit" class="btn btn-primary mt-4 mb-4"> Vytvoriť typ transakcie</button>

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
            type: '',
            description: '',
            strSuccess: '',
            strError: '',
            userID: '',

        }
    },
    created() {
        if (window.Laravel.user) {
            this.userID = window.Laravel.user.id;
        }
    },
    methods: {
        addPost(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                // formData.append('description', this.description);
                formData.append('type', this.type);
                formData.append('userID', this.userID);


                this.$axios.post('/api/expensestypes/add', formData, config)
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

.bg-add-expenstype {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.cardAddExpens {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonAddExpense {
    border-radius: 18px;
}
</style>
