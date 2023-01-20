<template>
    <div class="container">
        <div class="card cardAddPost">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Vytvoriť nový záznam o príjme</h5>
                    <div>
                        <router-link :to="{name: 'posts'}" class="btn btn-success buttonAddPost">Zoznam záznamov o príjme</router-link>
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
                        <label>Názov</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                    </div>

                    <div class="form-group mb-2">
                        <label>Popis</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="3" v-model="description"
                                  placeholder="Enter post description"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Suma</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="3" v-model="value"
                                  placeholder="Enter the value"></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label>Dátum</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                            <label>Typ transakcie</label><span class="text-danger"> *</span>
                            <div class="form-group mb-2 selection">
                                <select class="form-select" v-model="this.expensesType" placeholder="Select the type">
                                    <option
                                        v-for="(expensesType, key) in filteredAndSortedExpensesTypes(this.expensesTypes)"
                                        :value="expensesType"> {{ expensesType.type }}
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
                        <label>Pdf dokument</label><span class="text-danger"> *</span>
                        <input type="file" class="form-control mb-2" v-on:change="onChange">
                        <div v-if="pdf">
                            <label v-bind:src="pdfPreview"/>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 mb-4"> Vytvoriť</button>

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
            date: '',
            expensesType: [],
            pdf: '',
            strSuccess: '',
            strError: '',
            userId: '',
            pdfName: '',
            pdfPreview: null,
            expensesTypes: [],

        }
    },
    created() {
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
        }

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/expensestypes')
                .then(response => {
                    this.expensesTypes = response.data;
                    console.log(this.expensesTypes)
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
        onChange(e) {
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
                formData.append('typeID', this.expensesType.id);
                formData.append('date', this.date);
                formData.append('userID', this.userId);
                formData.append('pdf', this.pdf);
                formData.append('pdfName', this.pdfName);


                this.$axios.post('/api/posts/add', formData, config)
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
.cardAddPost {
    margin-top: 30px;
    border-radius: 18px;
}

.buttonAddPost {
    border-radius: 18px;
}

.button-add-type {
    margin-top: 23px;
}
</style>
