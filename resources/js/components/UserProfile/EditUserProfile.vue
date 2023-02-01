<template>
    <div class="container">
        <div class="card cardEditExpense">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Upraviť záznam o výdaji</h5>
                    <div>
                        <router-link :to="{name: 'posts'}" class="btn btn-success buttonEditExpens">Zoznam výdajov
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
                        <label>Názov</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                    </div>

                    <div class="form-group mb-2">
                        <label>Popis</label>
                        <textarea class="form-control" rows="3" v-model="description"
                                  placeholder="Enter post description"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Suma</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="1" v-model="value" placeholder="Enter value"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <label>Typ transakcie</label><span class="text-danger"> *</span>
                            <div class="form-group mb-2 selection">
                                <select class="form-select" v-model="this.typeId" placeholder="Select the type">
                                    <option
                                        v-for="(expensesType, key) in filteredAndSortedExpensesTypes(this.expensesTypes)"
                                        :value="expensesType.id"> {{ expensesType.type }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <router-link :to="{name: 'addexpensestypes'}" class="btn btn-success button-add-type ">
                                Vytvoriť nový typ transakcie
                            </router-link>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label>Dátum</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Upraviť výdaj</button>
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
            description: '',
            value: '',
            userId: '',
            typeId: '',
            date: '',
            strSuccess: '',
            strError: '',
            imgPreview: null,
            expensesTypes: [],
            expensesType: [],
        }
    },
    beforeCreate() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/expenses/edit/${this.$route.params.id}`)
                .then(response => {
                    this.name = response.data['name'];
                    this.description = response.data['description'];
                    this.value = response.data['value'];
                    this.typeId = response.data['typeID'];
                    this.expensesType = this.expensesTypes.filter(item => {
                        return response.data['typeID'] === item.id;
                    });
                    this.date = response.data['date'];
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
        filteredAndSortedExpensesTypes(expensestypes) {
            return expensestypes.filter(expens => {
                return expens.userID === this.userId;
            })
        },
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
                formData.append('description', this.description);
                formData.append('value', this.value);
                formData.append('typeID', this.typeId);
                formData.append('date', this.date);

                this.$axios.post(`/api/expenses/update/${this.$route.params.id}`, formData, config)
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
