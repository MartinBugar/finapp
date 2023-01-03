<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Add New Post Data</h5>
                <div>
                    <router-link :to="{name: 'posts'}" class="btn btn-success">Go Back</router-link>
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


            <form @submit.prevent="addPost" enctype="multipart/form-data">
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
                    <textarea class="form-control" rows="3" v-model="value" placeholder="Enter the value"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label>Dátum</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" rows="3" v-model="date" placeholder="Enter the date"/>
                </div>

                <div class="form-gorup mb-2">
                    <label>Pdf dokument</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChange">
                    <div v-if="pdf">
                        <label v-bind:src="pdfPreview" width="100" height="100"/>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Add Post</button>

            </form>

        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            name: '',
            description: '',
            value: '',
            date: '',
            pdf: '',
            strSuccess: '',
            strError: '',
            userID: '',
            pdfName: '',
            pdfPreview: null,
        }
    },
    created() {
        if (window.Laravel.user) {
            this.userID = window.Laravel.user.id;
        }
    },
    methods: {
        onChange(e) {
            this.pdf = e.target.files[0];
            this.pdfName = e.target.files[0].name;
            console.log(this.pdfName);
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.pdfPreview = reader.result;
            }.bind(this), false);

            if (this.pdf) {
                if ( /\.(pdf)$/i.test( this.pdf.name ) ) {
                    reader.readAsDataURL( this.pdf );
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
                formData.append('date', this.date);
                formData.append('userID', this.userID);
                formData.append('file', this.pdf);
                formData.append('pdfName', this.pdfName);


                this.$axios.post('/api/posts/add', formData, config)
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
