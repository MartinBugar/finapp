<template>
    <div class="bg-loans">
        <div class="container card mainCardLoans">
            <div class="card cardExpenses">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h3 class="card-title"><strong>Všetky úvery užívateľa
                            {{ userName }}</strong></h3>
                        <div>
                            <button class="btn btn-success buttonNewExpense" type="button"
                                    @click="this.$router.push('/loans/add')">
                                Vytvoriť nový úver
                            </button>
                        </div>
                    </div>

                    <table class="table table-hover table-sm table-bordered table-dark">
                        <thead class="bg-dark text-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <!--                            <th width="100" class="text-center">User Id</th>-->
                            <th>Názov</th>
                            <th>Dátum</th>
                            <th>Popis</th>
                            <th>Typ transakcie</th>
                            <th>Suma</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="" v-for="(loan, index) in filteredAndSorted(loans)" :key="loan.id">
                            <td class="text-center">{{ index + 1 }}.</td>
                            <!--                            <td class="text-center">{{ expens.userID }}.</td>-->
                            <td>{{ loan.name }}</td>
                            <td>{{ formatDate(loan.date) }}</td>
                            <td>{{ loan.description }}</td>
                            <td>{{ getTypeFromId(loan.typeID) }}</td>
                            <td>{{ loan.value.toFixed(2) }} €</td>

                            <td class="text-center buttons" v-if="userId === loan.userID">
                                <router-link :to="{name:'editloan', params: {id:loan.id}}"
                                             class="btn btn-sm btn-warning">
                                    Upraviť
                                </router-link>
                                <button class="btn btn-danger btn-sm m-1" @click="deleteLoan(loan.id)">Odstrániť
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import dates from "../Dates";
import moment, {months} from "moment/moment";

import {Chart as ChartJS, ArcElement, Tooltip, Legend} from 'chart.js'
import {Pie} from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: 'App',
    components: {
        Pie
    },
    data() {
        return {
            loans: [],
            strSuccess: '',
            strError: '',
            userId: '',
            type: '',
            userName: '',
            expensesTypes: [],
        }
    },

    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/loans')
                .then(response => {
                    this.loans = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expensestypes')
                .then(response => {
                    this.expensesTypes = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
            this.userName = window.Laravel.user.name;
        }
    },

    methods: {
        getTypeFromId(typeId) {
            this.expensesTypes.forEach(item => {
                if (item.id === typeId) {
                    this.type = item.type;
                }
            })
            return this.type;
        },
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        dates() {
            return dates
        },
        filteredAndSorted(loans) {
            let sortedLoansByDate = loans.sort((a, b) => new Date(a.date) - new Date(b.date));
            return sortedLoansByDate.filter(loan => {
                if (loan.userID === this.userId) {
                    return true;
                }
            })
        },
        deleteLoan(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Naozaj chcete odstrániť tento úver?")) {
                    this.$axios.delete(`/api/loans/delete/${id}`)
                        .then(response => {
                            let i = this.loans.map(item => item.id).indexOf(id); // find index of your object
                            this.loans.splice(i, 1);
                            console.log('succes')
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;

                        })
                        .catch(function (error) {
                            console.log('error')
                            existingObj.strError = "";
                            existingObj.strSuccess = error.response.data.message;
                        });
                }
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
    .mainCardLoans {
        max-width: var(--max-width-1);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .mainCardLoans {
        max-width: var(--max-width-2);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .mainCardLoans {
        max-width: var(--max-width-3);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .mainCardLoans {
        max-width: var(--max-width-4);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .mainCardLoans {
        max-width: var(--max-width-5);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

.buttons {
    max-height: 10px;
}

.cardExpenses {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonNewExpense {
    border-radius: 18px;
}

.summary {
    border-radius: 18px;
}

.chart {
    width: 400px;
    border-radius: 18px;
    margin-bottom: 30px;
}

.expensesSum {
    color: #b40000;
}

.bg-loans {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    min-height: var(--bg-min-height);
}

</style>
