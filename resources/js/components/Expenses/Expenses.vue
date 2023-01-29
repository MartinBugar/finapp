<template>
    <div class="bg">
        <div class="container card mainCardExpenses">
            <div class="card cardExpenses">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h3 class="card-title"><strong>Všetky výdaje užívateľa {{ userName }} za mesiac
                            {{ dates().at(month).name }} {{ year }}</strong></h3>
                        <div>
                            <button class="btn btn-success buttonNewExpense" type="button"
                                    @click="this.$router.push('/expenses/add')">
                                Vytvoriť nový výdaj
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-2 selection">
                        <div class="row rowEdit">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label>Mesiac</label><span class="text-danger"> </span>
                                <select class="form-select" v-model="month">
                                    <option value="0">Január</option>
                                    <option value="1">Febrár</option>
                                    <option value="2">Marec</option>
                                    <option value="3">Apríl</option>
                                    <option value="4">Máj</option>
                                    <option value="5">Jún</option>
                                    <option value="6">Júl</option>
                                    <option value="7">August</option>
                                    <option value="8">September</option>
                                    <option value="9">Október</option>
                                    <option value="10">November</option>
                                    <option value="11">December</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
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
                            <th width="100" class="text-center">User Id</th>
                            <th>Názov</th>
                            <th>Dátum</th>
                            <th>Popis</th>
                            <th>Typ transakcie</th>
                            <th>Suma</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="" v-for="(expens, index) in filteredAndSorted(expenses, month)" :key="expens.id">
                            <td class="text-center">{{ index + 1 }}.</td>
                            <td class="text-center">{{ expens.userID }}.</td>
                            <td>{{ expens.name }}</td>
                            <td>{{ formatDate(expens.date) }}</td>
                            <td>{{ expens.description }}</td>
                            <td>{{ getTypeFromId(expens.typeID) }}</td>
                            <td>{{ expens.value.toFixed(2) }} €</td>

                            <td class="text-center buttons" v-if="userId === expens.userID">
                                <router-link :to="{name:'editexpens', params: {id:expens.id}}"
                                             class="btn btn-sm btn-warning">
                                    Upraviť
                                </router-link>
                                <button class="btn btn-danger btn-sm m-1" @click="deletePost(expens.id)">Odstrániť
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>

            </div>

            <div class="row">
                <div class="col-lg-3">
                    <ul class="list-group mt-4 summary">
                        <li class="list-group-item"
                            v-for="(exptype, key) in filteredAndSortedExpensesTypes(this.expensesTypes)"
                            :value="exptype.type">
                            {{ exptype.type }}: <strong> {{ sumOfExpensesPerMonthPerType(this.month, exptype.id) }}
                            € </strong>
                        </li>

                        <li v-if="this.filteredAndSortedExpensesTypes(this.expensesTypes).length > 0"
                            class="list-group-item expensesSum">Výdavky spolu : <strong>
                            {{ sumOfExpensesPerMonth(this.month, this.year) }}
                            € </strong>
                        </li>
                    </ul>
                </div>

                <div v-if="this.filteredAndSortedExpensesTypes(this.expensesTypes).length > 0"
                     class="col-lg-9 mt-4 chart card">
                    <Pie :data="populateChartData()" :options="this.chartOptions"/>
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
            chartData: [],
            expensesChartLables: [],
            chartOptions: '',
            expenses: [],
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
            this.$axios.get('/api/expenses')
                .then(response => {
                    this.expenses = response.data;
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
        populateChartData() {
            let labels = []
            let colors = []
            let values = []
            let filteredExpensesTypes = this.filteredAndSortedExpensesTypes(this.expensesTypes);

            filteredExpensesTypes.forEach(item => {
                colors.push('#' + (Math.random() * 0xFFFFFF << 0).toString(16))
                labels.push(item.type)
                values.push(this.sumOfExpensesPerMonthPerType(this.month, item.id))
            })

            return this.chartData = {
                labels: labels,
                datasets: [
                    {
                        backgroundColor: colors,
                        data: values,
                    }
                ]
            }
        },

        filteredAndSortedExpensesTypes(expensestypes) {
            let both = [];
            this.expensesTypes.forEach(expensType => {
                this.expenses.forEach(expens => {
                    if (expens.typeID === expensType.id) {
                        let date = new Date(expens.date);
                        if (date.getMonth().toString() === this.month.toString() && date.getFullYear().toString() === this.year.toString()) {
                            both.push(expensType.type)
                        }
                    }
                })
            })

            return expensestypes.filter(expensType => {
                if (both.includes(expensType.type)) {
                    if (expensType.userID === this.userId) {
                        return true
                    }

                }
            })

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
        filteredAndSorted(expenses, month) {
            let sortedByDateExpenses = expenses.sort((a, b) => new Date(a.date) - new Date(b.date));
            return sortedByDateExpenses.filter(expens => {
                let expensDate = new Date(expens.date);
                if (expensDate.getFullYear().toString() === this.year.toString()) {
                    if (expensDate.getMonth().toString() === month.toString()) {
                        if (expens.userID === this.userId) {
                            return true;
                        }

                    }
                }
            })
        },
        deletePost(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/expenses/delete/${id}`)
                        .then(response => {
                            let i = this.expenses.map(item => item.id).indexOf(id); // find index of your object
                            this.expenses.splice(i, 1);
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

<style>
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

.rowEdit {
    width: 600px;
}

.summary {
    width: 300px;
    border-radius: 18px;
}

.chart {
    width: 400px;
    border-radius: 18px;
    margin-bottom: 30px;
}

.expensesSum {
    color: #b40000;
    /*background-color: #0b5ed7;*/
}

.mainCardExpenses {
    margin-top: 10px;
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
    border-radius: 18px;
}

</style>
