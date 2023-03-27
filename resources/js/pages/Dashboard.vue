<template>
    <div class="bg-dashboard pb-4">
        <div class="container card mainCardDashboard">
            <div class="card cardDashboard">

                <div class="container mt-2">
                    <h2>Dashboard panel uživateľa {{ name }}</h2>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="container mt-1">
                            <div class="form-group mb-2 selection">
                                <label>Rok</label><span class="text-danger"> *</span>
                                <select class="form-select" @change="onChange($event)" v-model="year">
                                    <option>2021</option>
                                    <option>2022</option>
                                    <option>2023</option>
                                </select>
                            </div>

                            <table class="table table-hover table-sm table-bordered table-dark">
                                <thead class="bg-dark text-light">
                                <tr>
                                    <th width="100" class="text-center">Mesiac</th>
                                    <th class="text-center">Príjem</th>
                                    <th class="text-center">Výdaje</th>
                                    <th class="text-center">Zostatok</th>

                                </tr>
                                </thead>
                                <tbody>

                                <tr class="" v-for="(date) in dates" :key="date.id">
                                    <td>{{ date.name }}</td>
                                    <td class="text-center incomeSum">{{ sumOfPostsFromMonth(date.id) }} €</td>
                                    <td class="text-center expensesSum">
                                        {{ sumOfExpensesFromMonth(date.id) }} €
                                    </td>
                                    <td class="text-center">
                                        <div
                                            :style="{ color: this.resultOfSum(sumOfPostsFromMonth(date.id), sumOfExpensesFromMonth(date.id)) >= 0 ? '#06b614' : 'red' }">
                                            {{
                                                this.resultOfSum(sumOfPostsFromMonth(date.id), sumOfExpensesFromMonth(date.id))
                                            }} €
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <ul class="list-group mb-4">
                                <li class="list-group-item incomeSum"><strong>Príjem za rok {{ year }} :
                                    {{ sumOfIncomPerYear(year) }}
                                    € </strong></li>
                                <li class="list-group-item expensesSum"><strong>Výdaje za rok {{ year }} :
                                    {{ sumOfExpensesPerYear(year) }}
                                    € </strong></li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-4 ">

                        <ul class="list-group mt-4 right-sidebox ">
                            <font-awesome-icon :icon="['fas', 'sunrise']" />
                            <li class="list-group-item"><strong> Východ Slnka je dnes o {{getSunrise().getHours()}}:{{ getSunrise().getMinutes() }} <b-icon-sunrise class="icon"/></strong> </li>
                            <li class="list-group-item "><strong> Západ Slnka je dnes o {{getSunSet().getHours()}}:{{ getSunSet().getMinutes() }} <b-icon-sunset class="icon"/></strong></li>
                        </ul>

                        <ul class="list-group mt-4 right-sidebox ">
                            <li class="list-group-item">Aktuálna inflácia na Slovensku : <strong>{{ getLastChartData() }}%</strong></li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class=" mt-4 mb-4 chart-inflation card">
                <Line :data="this.chartData" :options="this.chartOptions" :height="300"/>
            </div>

        </div>


    </div>
</template>

<script>

import Dates from "../components/Dates";
import ChartLabelsMonths from "../components/ChartLabelsMonths";
import ChartValues from "../components/ChartData";
import {getSunrise, getSunset} from 'sunrise-sunset-js';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

import {Line, Pie} from 'vue-chartjs'
import ChartData from "../components/ChartData";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

export default {
    name: "Dashboard",
    components: {
        Line,
    },
    computed: {},
    data() {
        return {
            chartData: [],
            expensesChartLables: [],
            chartOptions: '',

            userId: null,
            name: null,
            email: null,
            posts: [],
            expenses: [],
            months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            dates: Dates,
            chartLabels: ChartLabelsMonths,
            year: new Date().getFullYear(),
            today: new Date(),
        }
    },
    created() {
        this.chartData = {
            labels: ChartLabelsMonths,
            datasets: [
                {
                    label: 'Vývoj inflácie na Slovensku v %',
                    backgroundColor: '#f80000',
                    data: ChartValues
                },
            ]
        }

        this.chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        }

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/posts')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expenses')
                .then(response => {
                    this.expenses = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
        }
    },
    methods: {
        getLastChartData() {
            let indexOfLastChild = ChartData.length - 1;
            return ChartData.at(indexOfLastChild).toFixed(2);
        },
        ChartData() {
            return ChartData
        },
        getSunrise() {
            return getSunrise(48.149482, 17.120598, this.today)
        },

        getSunSet() {
            return getSunset(48.149482, 17.120598, this.today)
        },
        getMonthFromDate(date) {
            let newDate = new Date(date);
            return newDate.getMonth();
        },
        getYearFromDate(date) {
            let newDate = new Date(date);
            return newDate.getFullYear();
        },
        sumOfValues() {
            let sum = 0;
            this.posts.forEach((value) => {
                if (value.userID === this.userId) {
                    sum = sum + value.value;
                }
            });
            return sum.toFixed(2);
        },
        sumOfIncomPerYear(year) {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear().toString() === year.toString()) {
                    sum = sum + value.value;
                }
            });
            return sum.toFixed(2);
        },
        sumOfExpenses() {
            let sum = 0;
            this.expenses.forEach((value) => {
                if (value.userID === this.userId) {
                    sum = sum + value.value;
                }
            });
            return sum;
        },
        sumOfExpensesPerYear(year) {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                if (value.userID === this.userId && date.getFullYear().toString() === year.toString()) {
                    sum = sum + value.value;
                }
            });
            return sum.toFixed(2);
        },
        sumOfExpensesFromMonth(date1) {
            let sum = 0;
            this.expenses.forEach((value) => {
                let date = new Date(value.date);
                if (date.getFullYear().toString() === this.year.toString()) {
                    if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                        sum = sum + value.value;
                    }
                }
            });
            return sum.toFixed(2);
        },

        resultOfSum(post, expense) {
            let sum = post - expense;
            return sum.toFixed(2);
        },
        sumOfPostsFromMonth(date1) {
            let sum = 0;
            this.posts.forEach((value) => {
                let date = new Date(value.date);
                if (date.getFullYear().toString() === this.year.toString()) {
                    if (value.userID === this.userId && (date.getMonth() + 1) === date1) {
                        sum = sum + value.value;
                    }
                }
            });
            return sum.toFixed(2);
        },
        filteredAndSortedPosts() {
            return this.posts.filter(post => {
                return post.userID === this.userId;
            })
        },
        filteredAndSortedExpenses() {
            return this.expenses.filter(expense => {
                return expense.userID === this.userId;
            })
        },
        onChange(event) {
            this.year = event.target.value;
        },
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
    .mainCardDashboard {
        max-width: var(--max-width-1);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .mainCardDashboard {
        max-width: var(--max-width-2);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .mainCardDashboard {
        max-width: var(--max-width-3);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .mainCardDashboard {
        max-width: var(--max-width-4);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .mainCardDashboard {
        max-width: var(--max-width-5);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

.cardDashboard {
    margin-top: 30px;
    margin-bottom: 30px;
    border-radius: 18px;
}

.bg-dashboard {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    min-height: var(--bg-min-height);
}


.incomeSum {
    color: #06b614;
}

.expensesSum {
    color: red;
}

.right-sidebox {
    padding-right: 10px;
}

.selection {
    max-width: 200px;
}

</style>
