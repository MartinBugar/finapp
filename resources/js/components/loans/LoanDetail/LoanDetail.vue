<template>
    <div class="bg-expenses">
        <div class="container card mainCardExpenses">
            <div class="card cardExpenses">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h3 class="card-title"><strong>Všetky splátky úveru za rok
                            {{ year }}</strong></h3>
                        <div>
                            <button class="btn btn-success buttonNewExpense" type="button"
                                    @click="this.$router.push(`/loans/loanDetail/${this.loanID}/add`)">
                                Pridať splátku
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-3">
                                <label>Rok</label><span class="text-danger"> *</span>
                                <select class="form-select" v-model="year">
                                    <option>2021</option>
                                    <option>2022</option>
                                    <option>2023</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <table class="table table-hover table-sm table-bordered table-dark">
                        <thead class="bg-dark text-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Názov</th>
                            <th>Dátum</th>
                            <th>Popis</th>
                            <th>Typ transakcie</th>
                            <th>Suma</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="" v-for="(loanDetail, index) in filteredAndSorted(loanDetails)"
                            :key="loanDetail.id">
                            <td class="text-center">{{ index + 1 }}.</td>
                            <td>{{ loanDetail.name }}</td>
                            <td>{{ formatDate(loanDetail.date) }}</td>
                            <td>{{ loanDetail.description }}</td>
                            <td>{{ getTypeFromId(loanDetail.typeID) }}</td>
                            <td>{{ loanDetail.value.toFixed(2) }} €</td>

                            <td class="text-center buttons" v-if="userId === loanDetail.userID">
                                <router-link :to="{name:'editloanDetail', params: {loanID:this.loanID, loanDetailId:loanDetail.id}}"
                                             class="btn btn-sm btn-warning">
                                    Upraviť
                                </router-link>
                                <button class="btn btn-danger btn-sm m-1" @click="deleteLoanDetail(loanDetail.id)">Odstrániť
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
import dates from "../../Dates";
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
            loanID: '',
            chartData: [],
            expensesChartLables: [],
            chartOptions: '',
            loanDetails: [],
            expensesChartValues: [],
            strSuccess: '',
            strError: '',
            userId: '',
            type: '',
            userName: '',
            month: new Date(Date.now()).getMonth(),
            year: new Date(Date.now()).getFullYear(),
            expensType: '',
            expensesTypes: [],
            both: [],
        }
    },

    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/loans/loanDetail/${this.$route.params.id}`)
                .then(response => {
                    this.loanID = this.$route.params.id;
                    this.loanDetails = response.data;
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

        this.chartOptions = {
            responsive: true,
            maintainAspectRatio: false
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

        sumOfExpensesPerMonthPerType(month, typeID) {
            let sum = 0;
            this.expenses.forEach((expens) => {
                let date = new Date(expens.date);
                if (expens.userID === this.userId) {
                    if (month.toString() === date.getMonth().toString()) {
                        if (expens.typeID === typeID) {
                            sum = sum + expens.value;
                        }
                    }
                }
            });

            return sum.toFixed(2);
        },
        sumOfExpensesPerMonth(month, year) {
            let sum = 0;
            this.expenses.forEach((expens) => {
                let date = new Date(expens.date);
                if (expens.userID === this.userId) {
                    if (month.toString() === date.getMonth().toString() && year.toString() === date.getFullYear().toString()) {
                        sum = sum + expens.value;
                    }
                }
            });
            return sum.toFixed(2);
        },
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        dates() {
            return dates
        },
        filteredAndSorted(loanDetails) {
            let sortedByDateLoanDetails = loanDetails.sort((a, b) => new Date(a.date) - new Date(b.date));
            return sortedByDateLoanDetails.filter(detail => {
                let expensDate = new Date(detail.date);
                if (expensDate.getFullYear().toString() === this.year.toString()) {
                    if (detail.userID === this.userId) {
                        return true;
                    }
                }
            })
        },
        deleteLoanDetail(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Naozaj chcete odstrániť túto splátku?")) {
                    this.$axios.delete(`/api/loans/loanDetail/delete/${id}`)
                        .then(response => {
                            let i = this.loanDetails.map(item => item.id).indexOf(id); // find index of your object
                            this.loanDetails.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;

                        })
                        .catch(function (error) {
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
    .mainCardExpenses {
        max-width: var(--max-width-1);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .mainCardExpenses {
        max-width: var(--max-width-2);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .mainCardExpenses {
        max-width: var(--max-width-3);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .mainCardExpenses {
        max-width: var(--max-width-4);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .mainCardExpenses {
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

.bg-expenses {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    min-height: var(--bg-min-height);
}

</style>
