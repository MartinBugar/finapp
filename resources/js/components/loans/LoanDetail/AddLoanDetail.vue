<template>
    <div class="bg-addloan">
        <div class="container card cardAddLoan">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Vytvoriť novú splátku {{ this.loanID }} </h5>
                    <div>
                        <button class="btn btn-success buttonNewExpense" type="button"
                                @click="this.$router.push(`/loans/loanDetail/${this.loanID}`)">
                            Späť na prehľad
                        </button>
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


                <form @submit.prevent="addLoan" enctype="multipart/form-data">
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
                        <textarea class="form-control" rows="1" v-model="value"
                                  placeholder="Enter the value"></textarea>
                    </div>

                    <div class="row ">
                        <div class="col-lg-2">
                            <label>Typ transakcie</label><span class="text-danger"> *</span>
                            <div class="form-group mb-2 selection">
                                <select class="form-select" v-model="this.expensesType"
                                        placeholder="Select the type">
                                    <option
                                        v-for="(expensesType, key) in filteredAndSortedExpensesTypes(this.expensesTypes)"
                                        :value="expensesType"> {{ expensesType.type }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 ">
                            <router-link :to="{name: 'addexpensestypes'}" class="btn btn-success ">
                                Vytvoriť nový typ transakcie
                            </router-link>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label>Dátum</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" rows="3" v-model="date"
                               placeholder="Enter the date"/>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Pridať splátku</button>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loanID: '',
            name: '',
            description: '',
            value: '',
            date: '',
            strSuccess: '',
            strError: '',
            userId: '',
            expensesTypes: [],
            expensesType: [],
        }
    },
    created() {
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }

        this.loanID = this.$route.params.id;

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
        filteredAndSortedExpensesTypes(expensestype) {
            return expensestype.filter(expensType => {
                return expensType.userID === this.userId;
            })
        },
        addLoan(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();

                formData.append('name', this.name);
                formData.append('loanID', this.loanID);
                formData.append('description', this.description);
                formData.append('value', this.value);
                formData.append('typeID', this.expensesType.id);

                formData.append('date', this.date);
                formData.append('userID', this.userId);


                this.$axios.post('/api/loans/loanDetail/add', formData, config)
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

<style scoped>

@media screen and (min-width: 1630px) {
    .cardAddLoan {
        max-width: var(--max-width-1);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .cardAddLoan {
        max-width: var(--max-width-2);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .cardAddLoan {
        max-width: var(--max-width-3);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .cardAddLoan {
        max-width: var(--max-width-4);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .cardAddLoan {
        max-width: var(--max-width-5);
        margin-top: 10px;
        border-radius: 18px;
    }
}

.bg-addloan {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

.buttonAddLoan {
    border-radius: 18px;
}
</style>
