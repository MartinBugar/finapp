<template>
    <div class="bg">
        <div class="container">

            <form ref="form" @submit.prevent="sendEmail">
                <label>Name</label>
                <input type="text" name="user_name">
                <label>Email</label>
                <input type="email" name="user_email">
                <label>Message</label>
                <textarea name="message"></textarea>
                <input type="submit" value="Send">
            </form>

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
            message: ''
        }
    },
    methods: {

        //config: https://dashboard.emailjs.com/admin
        sendEmail(e) {
            try {
                emailjs.sendForm('service_ulrki3m', 'template_do3j4ii', e.target,'sZtoDVKcdOx0Q-ksX')
                    .then(function(response) {
                        console.log('SUCCESS!', response.status, response.text);
                    }, function(error) {
                        console.log('FAILED...', error);
                    });

            } catch(error) {
                console.log({error})
            }
            // Reset form field
            this.name = ''
            this.email = ''
            this.message = ''
        },

    }
}
</script>

<style scoped>
* {box-sizing: border-box;}

.container {
    display: block;
    margin:auto;
    text-align: center;
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
    width: 50%;
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
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}
</style>
