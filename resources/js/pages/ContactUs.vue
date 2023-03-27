<template>
    <div class="bg-app">
        <div class="container contact-form-card">
            <div class="row">
                <div class="col-lg-4">
                    <p class="text-contact">
                        Ak máte nejaké otázky <br>
                        alebo dotazy, prípadne <br>
                        návrhy na zlepšenie, <br>
                        neváhajte nás kontaktovať.
                    </p>
                </div>
                <div class="col-lg-8">
                    <form ref="form" @submit.prevent="sendEmail">
                        <label>Vaše meno</label>
                        <input type="text" name="user_name">
                        <label>Váš email</label>
                        <input type="email" name="user_email">
                        <label>Správa pre nás</label>
                        <textarea name="message"></textarea>
                        <div class="">
                            <label>Potvrdte ze nie ste robot </label>
                            <input type="checkbox" v-model="notARobot">
                        </div>
                        <input type="submit" value="Odoslať">

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import emailjs from 'emailjs-com';

export default {
    name: 'ContactUs',
    data() {
        return {
            name: '',
            email: '',
            message: '',
            notARobot: false,
        }
    },
    methods: {

        //config: https://dashboard.emailjs.com/admin
        sendEmail(e) {
            if (this.notARobot === null || this.notARobot === false) {
                alert("Prosim potvrdte ze nie ste robot")
            } else {
                try {
                    emailjs.sendForm('service_ulrki3m', 'template_do3j4ii', e.target, 'sZtoDVKcdOx0Q-ksX')
                        .then(function (response) {
                            console.log('SUCCESS!', response.status, response.text);
                        }, function (error) {
                            console.log('FAILED...', error);
                        });

                } catch (error) {
                    console.log({error})
                }
                // Reset form field
                this.name = ''
                this.email = ''
                this.message = ''

            }


        },

    }
}
</script>

<style scoped>

@media screen and (min-width: 1630px) {
    .contact-form-card {
        max-width: var(--max-width-1);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .contact-form-card {
        max-width: var(--max-width-2);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .contact-form-card {
        max-width: var(--max-width-3);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .contact-form-card {
        max-width: var(--max-width-4);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1270px) {
    .contact-form-card {
        max-width: var(--max-width-5);
        margin-top: 10px;
        border-radius: 18px;
    }
}

.text-contact {
    margin-top: 40px;
    font-family: "Bebas Neue", serif;
    font-size: 1.3rem;
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
