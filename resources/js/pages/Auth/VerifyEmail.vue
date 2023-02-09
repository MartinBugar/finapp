<template>
    <div class="bg-contact-form">
        <div class="container">

            <p class="text-email-verify">
                Váš email bol úspešne verifikovaný.
                Teraz sa môžete prihlásiť.
            </p>

            <router-link :to="{name: 'login'}" class="btn btn-success button-add-type ">
                Prihlásenie
            </router-link>
        </div>
    </div>
</template>

<script>


export default {
    data() {
        return {
            response: '',
        }
    },

    name: "VerifyEmail",
    methods: {},
    created() {

        this.$axios.get(`/api/email/verify/${this.$route.query.id}/${this.$route.query.hash}`)
            .then(response => {
                this.response = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });

    }
}
</script>

<style scoped>
.text-email-verify {
    margin-top: 40px;
    font-family: "Bebas Neue", serif;
    font-size: 1.5rem;
}

.bg-contact-form {
    background-color: var(--bg-secondary);
    padding-top: 4vh;
    width: 100%;
    height: 100vh;
}

* {
    box-sizing: border-box;
}

.container {
    display: block;
    text-align: center;
    border-radius: 18px;
    background-color: #f2f2f2;
    padding: 20px;
    /*width: 50%;*/
}

label {
    float: left;
}

input[type=text], [type=email], textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
    resize: vertical;
}

input[type=submit] {
    background-color: #4CAF50;
    border-radius: 18px;
    color: white;
    padding: 12px 20px;
    border: none;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}
</style>
