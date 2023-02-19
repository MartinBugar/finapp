<template>
    <div class="bg-posts">
        <div class="container card mainCardPosts">
            <div class="card cardPost">
                <div class="card-body">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h3 class="card-title"><strong>Príjem uživateľa {{ userName }} za mesiac
                            {{ dates().at(month).name }} {{ year }}</strong></h3>
                        <div>
                            <button class="btn btn-success buttonNewPost" type="button"
                                    @click="this.$router.push('/posts/add')">Vytvoriť nový záznam o príjme
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col ">
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
                            <div class="col">
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
                            <!--                            <th width="100" class="text-center">User Id</th>-->
                            <th>Názov</th>
                            <th>Dátum</th>
                            <th>Popis</th>
                            <th class="text-center">Pdf</th>
                            <th>Typ transakcie</th>
                            <th>Suma</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="" v-for="(post, index) in filteredAndSortedForPost(posts, month)" :key="post.id">
                            <td class="text-center">{{ index + 1 }}.</td>
                            <!--                            <td class="text-center">{{ post.userID }}.</td>-->
                            <td>{{ post.name }}</td>
                            <td>{{ formatDate(post.date) }}</td>
                            <td>{{ post.description }}</td>
                            <td class="text-center">
                                <a href="#" v-if="post.pdf" @click="downloadWithAxios(post)">{{ post.pdfName }}</a>
                            </td>
                            <td>{{ getTypeFromId(post.typeID) }}</td>
                            <td>{{ post.value.toFixed(2) }} €</td>

                            <td class="text-center buttons" v-if="userId === post.userID">

                                <router-link :to="{name:'editpost', params: {id:post.id}}"
                                             class="btn btn-sm btn-warning">
                                    Upraviť
                                </router-link>
                                <button class="btn btn-danger btn-sm m-1" @click="deletePost(post.id)">Odstrániť
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <ul class="list-group mt-4 summary">
                        <li class="list-group-item"
                            v-for="(exptype, key) in filteredAndSortedExpensesTypes(this.expensesTypes)"
                            :value="exptype.type">
                            {{ exptype.type }}: <strong> {{ sumOfPostsPerMonthPerType(this.month, exptype.id) }}
                            € </strong>
                        </li>

                        <li v-if="this.filteredAndSortedExpensesTypes(this.expensesTypes).length > 0"
                            class="list-group-item postsSum">Príjmy spolu : <strong>
                            {{ sumOfPostsPerMonth(this.month, this.year) }}
                            € </strong>
                        </li>
                    </ul>
                </div>

                <div v-if="this.filteredAndSortedExpensesTypes(this.expensesTypes).length > 0"
                     class="col-lg-7 mt-4 chart card">
                    <Pie :data="populateChartData()" :options="this.chartOptions"/>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import dates from "../Dates";
import moment from 'moment'
import {Pie} from "vue-chartjs";
import {Chart as ChartJS, ArcElement, Tooltip, Legend} from 'chart.js'
import expensesTypes from "../ExpensesTypes/ExpensesTypes.vue";

ChartJS.register(ArcElement, Tooltip, Legend)

export default {
    name: 'App',
    components: {
        Pie
    },
    data() {
        return {
            posts: [],
            chartOptions: '',
            chartData: [],
            strSuccess: '',
            strError: '',
            type: '',
            userId: '',
            userName: '',
            month: new Date(Date.now()).getMonth(),
            year: new Date(Date.now()).getFullYear(),
            url: 'pdf/',
            pdfName: '',
            expensesTypes: [],
        }
    },
    created() {
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
            let filteredExpenses = this.filteredAndSortedExpensesTypes(this.expensesTypes);


            filteredExpenses.forEach(item => {
                colors.push('#' + (Math.random() * 0xFFFFFF << 0).toString(16))
                labels.push(item.type)
                values.push(this.sumOfPostsPerMonthPerType(this.month, item.id))
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
        sumOfPostsPerMonth(month, year) {
            let sum = 0;
            this.posts.forEach((post) => {
                let date = new Date(post.date);
                if (post.userID === this.userId) {
                    if (month.toString() === date.getMonth().toString() && year.toString() === date.getFullYear().toString()) {
                        sum = sum + post.value;
                    }
                }
            });
            return sum.toFixed(2);
        },
        sumOfPostsPerMonthPerType(month, typeID) {
            let sum = 0;
            this.posts.forEach((post) => {
                let date = new Date(post.date);
                if (post.userID === this.userId) {
                    if (month.toString() === date.getMonth().toString()) {
                        if (post.typeID === typeID) {
                            sum = sum + post.value;
                        }
                    }
                }
            });

            return sum.toFixed(2);
        },
        filteredAndSortedExpensesTypes(expensestypes) {
            let both = [];
            this.expensesTypes.forEach(expensType => {
                this.posts.forEach(post => {
                    if (post.typeID === expensType.id) {
                        let date = new Date(post.date);
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
        downloadWithAxios(post) {
            axios({
                method: 'get',
                url: this.url + post.pdf,
                responseType: 'arraybuffer'
            })
                .then(response => {
                    this.forceFileDownload(response, post)
                })
                .catch(() => console.log('error occured'))
        },

        forceFileDownload(response, post) {
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', post.pdfName)
            document.body.appendChild(link)
            link.click()
        },
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        dates() {
            return dates
        },

        filteredAndSortedForPost(posts, month) {
            let sortedByDatePosts = posts.sort((a, b) => new Date(a.date) - new Date(b.date));
            return sortedByDatePosts.filter(post => {
                let postMonth = new Date(post.date);
                if (postMonth.getFullYear().toString() === this.year.toString()) {
                    if (postMonth.getMonth().toString() === month.toString()) {
                        return post.userID === this.userId;
                    }
                }
            })
        },
        deletePost(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/posts/delete/${id}`)
                        .then(response => {

                            let i = this.posts.map(item => item.id).indexOf(id); // find index of your object
                            this.posts.splice(i, 1);
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
    .mainCardPosts {
        max-width: var(--max-width-1);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .mainCardPosts {
        max-width: var(--max-width-2);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .mainCardPosts {
        max-width: var(--max-width-3);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}
@media screen and (max-width: 1430px) {
    .mainCardPosts {
        max-width: var(--max-width-4);
        margin-top: 10px;
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)) !important;
        border-radius: 18px;
    }
}


@media screen and (max-width: 1330px) {
    .mainCardPosts {
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

.cardPost {
    margin-top: 30px;
    border-radius: 18px;
}


.buttonNewPost {
    border-radius: 18px;
}

.chart {
    width: 400px;
    border-radius: 18px;
    margin-bottom: 30px;
}

.postsSum {
    color: #06b614;
}

.bg-posts {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    min-height: var(--bg-min-height);
}

</style>
