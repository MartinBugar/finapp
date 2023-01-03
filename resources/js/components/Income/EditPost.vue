<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Update Post Data</h5>
                <div>
                    <router-link :to="{name: 'expenses'}" class="btn btn-success">Go Back</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strSuccess}}</strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strError}}</strong>
            </div>


            <form @submit.prevent="updatePost" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="name" placeholder="Enter post name">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
                   <textarea class="form-control" rows="3" v-model="description" placeholder="Enter post description"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label>Value</label><span class="text-danger"> *</span>
                    <textarea class="form-control" rows="1" v-model="value" placeholder="Enter value"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label>Dátum</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
                </div>

                <div class="form-gorup mb-2">
                    <label>PDF Dokument</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-model="pdf">
                </div>

                <div class="form-group mb-2">
                    <label>Dokument PDF: </label>
                    <a href="#" v-if="pdf" @click="downloadWithAxios(this.pdf, this.pdfName)">{{ pdfName }}</a>
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Update Post</button>

            </form>

        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            id:'',
            name: '',
            description: '',
            value: '',
            type: '',
            date: '',
            strSuccess: '',
            strError: '',
            pdf: '',
            pdfName: '',
            url: '/pdf/',
        }
    },

    beforeCreate() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/posts/edit/${this.$route.params.id}`)
            .then(response => {
                console.log(response)
                this.name = response.data['name'];
                this.description = response.data['description'];
                this.value = response.data['value'];
                this.date = response.data['date'];
                this.pdf = response.data['pdf'];
                this.pdfName = response.data['pdfName'];
            })
            .catch(function(error) {
                console.log(error);
            });
        })
    },
    methods: {
        downloadWithAxios(pdf, pdfName) {
            axios({
                method: 'get',
                url: this.url + pdf,
                responseType: 'arraybuffer'
            })
                .then(response => {
                    console.log(this.url + pdf)
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
                formData.append('type', this.type);
                formData.append('date', this.date);
                formData.append('pdf', this.pdf);
                formData.append('pdfName', this.pdfName);

                this.$axios.post(`/api/posts/update/${this.$route.params.id}`, formData, config)
                .then(response => {
                    existingObj.strError = "";
                    existingObj.strSuccess = response.data.success;
                })
                .catch(function(error) {
                    existingObj.strSuccess ="";
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
