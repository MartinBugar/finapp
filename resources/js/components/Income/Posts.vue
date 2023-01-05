<template>
        <div class="container">
            <div class="card">
                <div class="card-body ">
                    <div class="d-flex justify-content-between pb-2 mb-2">
                        <h3 class="card-title"><strong>Prijem uzivatela {{ userName }} za mesiac
                            {{ dates().at(month).name }} {{ year }}</strong></h3>
                        <div>
                            <button class="btn btn-success" type="button" @click="this.$router.push('/posts/add')">New
                                Post
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-2 selection">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <label>Mesiac</label><span class="text-danger"> </span>
                                <select class="form-select" v-model="month">
                                    <option value="0">Januar</option>
                                    <option value="1">Febrar</option>
                                    <option value="2">Marec</option>
                                    <option value="3">April</option>
                                    <option value="4">Maj</option>
                                    <option value="5">Jun</option>
                                    <option value="6">Jul</option>
                                    <option value="7">August</option>
                                    <option value="8">September</option>
                                    <option value="9">Oktober</option>
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
                            <th>Suma</th>
                            <th class="text-center">Pdf</th>
                            <th class="text-center" width="200">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="" v-for="(post, index) in filteredAndSorted(posts, month)" :key="post.id">
                            <td class="text-center">{{ index + 1 }}.</td>
                            <td class="text-center">{{ post.userID }}.</td>
                            <td>{{ post.name }}</td>
                            <td>{{ formatDate(post.date) }}</td>
                            <td>{{ post.description }}</td>
                            <td>{{ post.value }} €</td>
                            <td class="text-center">
                                <a href="#" v-if="post.pdf" @click="downloadWithAxios(post)">{{ post.pdfName }}</a>
                            </td>

                            <td class="text-center buttons" v-if="userID === post.userID">

                                <router-link :to="{name:'editpost', params: {id:post.id}}"
                                             class="btn btn-sm btn-warning">
                                    Edit
                                </router-link>
                                <button class="btn btn-danger btn-sm m-1" @click="deletePost(post.id)">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
</template>

<script>
import Dates from "../Dates";
import dates from "../Dates";
import moment from 'moment'

export default {
    data() {
        return {
            posts: [],
            strSuccess: '',
            strError: '',
            userID: '',
            userName: '',
            month: new Date(Date.now()).getMonth(),
            year: new Date(Date.now()).getFullYear(),
            url: 'pdf/',
            pdfName: '',
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
        if (window.Laravel.user) {
            this.userID = window.Laravel.user.id;
            this.userName = window.Laravel.user.name;
        }
    },
    methods: {
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
            console.log(link)
            link.click()
        },
        formatDate(value) {
            return moment(value).format('DD.MM.YYYY');
        },
        dates() {
            return dates
        },

        filteredAndSorted(posts, month) {
            return this.posts.filter(post => {
                let postMonth = new Date(post.date);
                if (postMonth.getFullYear().toString() === this.year.toString()) {
                    if (postMonth.getMonth().toString() === month.toString()) {
                        return post.userID === this.userID;
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

<style>
.buttons {
    max-height: 10px;
}


.card {
    margin-top: 30px;
}

.row {
    width: 600px;
}


</style>
