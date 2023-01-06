<template>
    <div class="container">
        <div class="card cardAddExpens">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Pridat novy výdaj </h5>
                    <div>
                        <router-link :to="{name: 'expenses'}" class="btn btn-success buttonAddExpense">Go Back</router-link>
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
                        <label>Name</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                    </div>

                    <div class="form-group mb-2">
                        <label>Description</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="3" v-model="description"
                                  placeholder="Enter post description"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Value</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="3" v-model="value"
                                  placeholder="Enter the value"></textarea>
                    </div>

                    <div class="form-group mb-2 selection">
                        <label>Type</label><span class="text-danger"> *</span>
                        <select class="form-select" v-model="type" placeholder="Select the type">
                            <option>Jedlo a potraviny</option>
                            <option>Povinne platby</option>
                            <option>Zvieratka</option>
                            <option>Domacnost</option>
                            <option>Martin</option>
                            <option>Simonka</option>
                        </select>

                    </div>

                    <div class="form-group mb-2">
                        <label>Dátum</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Add Post</button>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            description: '',
            value: '',
            type: '',
            date: '',
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

                formData.append('name', this.name);
                formData.append('description', this.description);
                formData.append('value', this.value);
                formData.append('type', this.type);
                formData.append('date', this.date);
                formData.append('userID', this.userID);


                this.$axios.post('/api/expenses/add', formData, config)
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
.selection {
    max-width: 200px;
}

.cardAddExpens {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonAddExpense {
    border-radius: 18px;
}
</style>
