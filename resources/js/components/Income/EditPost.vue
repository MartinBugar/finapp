<template>
    <div class="container">
        <div class="card cardEditPost">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Upraviť záznam o príjme</h5>
                    <div>
                        <router-link :to="{name: 'expenses'}" class="btn btn-success buttonEditPost">Zoznam záznamov o
                            príjme
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

                    <div class="form-group mb-2">
                        <label>Dátum</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
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

                    <div class="form-gorup mb-2">
                        <label>PDF Dokument</label><span class="text-danger"> *</span>
                        <input type="file" class="form-control mb-2" v-on:change="onChange">
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-2">
                                <label>Dokument PDF: </label>
                                <a class="" href="#" @click="downloadWithAxios(this.pdf, this.pdfName)">{{
                                        pdfName
                                    }}</a>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-danger" v-if="isDeleteEnabled()" @click="deletePdf()">
                                delete pdf
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Upraviť</button>

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
            deleteFlag: false,
            name: '',
            description: '',
            expensesType: [],
            value: '',
            type: '',
            typeId: '',
            date: '',
            userId: '',
            strSuccess: '',
            strError: '',
            pdf: '',
            pdfToDelete: '',
            pdfNameToDelete: '',
            pdfName: '',
            url: '/pdf/',
            expensesTypes: [],

        }
    },

    beforeCreate() {
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
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/posts/edit/${this.$route.params.id}`)
                .then(response => {

                    this.name = response.data['name'];
                    this.description = response.data['description'];
                    this.value = response.data['value'];
                    this.typeId = response.data['typeID'];
                    this.type = response.data['type'];
                    this.expensesType = this.expensesTypes.filter(item => {
                        return response.data['typeID'] === item.id;
                    });

                    this.date = response.data['date'];
                    this.pdf = response.data['pdf'];
                    this.pdfName = response.data['pdfName'];

                })
                .catch(function (error) {
                    console.log(error);
                });
        })


        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }


    },
    methods: {
        loadPdfFromAxiosDB() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/posts/edit/${this.$route.params.id}`)
                    .then(response => {
                        this.pdf = response.data['pdf'];
                        this.pdfName = response.data['pdfName'];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            })
        },
        isDeleteEnabled() {
            return this.pdf && this.pdfName;
        },
        deletePdf() {
            if (confirm("Delete?")) {
                this.deleteFlag = false;
                this.pdfToDelete = this.pdf;
                this.pdfNameToDelete = this.pdfName;
                this.pdf = null;
                this.pdfName = null;

                let reader = new FileReader();
                reader.addEventListener("load", function () {
                    this.pdfPreview = reader.result;
                }.bind(this), false);

                if (this.pdf) {
                    if (/\.(pdf)$/i.test(this.pdf.name)) {
                        reader.readAsDataURL(this.pdf);
                    }
                }
            }

        },
        filteredAndSortedExpensesTypes(expensestypes) {
            return expensestypes.filter(expens => {
                return expens.userID === this.userId;
            })
        },
        onChange(e) {
            this.deleteFlag = false;
            this.pdf = e.target.files[0];
            this.pdfName = e.target.files[0].name;
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.pdfPreview = reader.result;
            }.bind(this), false);

            if (this.pdf) {
                if (/\.(pdf)$/i.test(this.pdf.name)) {
                    reader.readAsDataURL(this.pdf);
                }
            }


        },
        downloadWithAxios(pdf, pdfName) {
            axios({
                method: 'get',
                url: this.url + pdf,
                responseType: 'arraybuffer'
            })
                .then(response => {
                    this.forceFileDownload(response, pdfName)
                })
                .catch(() => console.log('error occured'))
        },

        forceFileDownload(response, pdfName) {
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', pdfName)
            document.body.appendChild(link)
            link.click()
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

                // formData.append('type', this.expensesTypes.filter(item => {
                //     return (this.typeId === item.id)
                // }).map(a => a.type));

                formData.append('date', this.date);

                formData.append('pdf', this.pdf)
                formData.append('pdfToDelete', this.pdfToDelete)
                formData.append('pdfNameToDelete', this.pdfNameToDelete)

                if (this.pdf) {
                    formData.append('pdfName', this.pdfName);
                }


                this.pdfToDelete = null;
                this.pdfNameToDelete = null;


                this.$axios.post(`/api/posts/update/${this.$route.params.id}`, formData, config)
                    .then(response => {
                        existingObj.strError = "";
                        existingObj.strSuccess = response.data.success;
                    })
                    .catch(function (error) {
                        existingObj.strSuccess = "";
                        existingObj.strError = error.response.data.message;
                    });
            });

            this.loadPdfFromAxiosDB();


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

<style>
.cardEditPost {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonEditPost {
    border-radius: 18px;
}
</style>
