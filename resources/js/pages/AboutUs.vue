<template>
    <div class="bg-contact-form">
        <div class="container card-aboutus">
            <p class="aboutus">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                culpa qui officia deserunt mollit anim id est laborum.
            </p>

            <p class="aboutus">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                culpa qui officia deserunt mollit anim id est laborum.
            </p>

            <p class="aboutus">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                culpa qui officia deserunt mollit anim id est laborum.
            </p>


        </div>
    </div>
</template>

<script>


export default {
    name: 'ContactUs',
    data() {
        return {}
    },
    methods: {}
}
</script>

<style scoped>

@media screen and (min-width: 1630px) {
    .card-aboutus {
        max-width: var(--max-width-1);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1630px) {
    .card-aboutus {
        max-width: var(--max-width-2);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1530px) {
    .card-aboutus {
        max-width: var(--max-width-3);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1430px) {
    .card-aboutus {
        max-width: var(--max-width-4);
        margin-top: 10px;
        border-radius: 18px;
    }
}

@media screen and (max-width: 1330px) {
    .card-aboutus {
        max-width: var(--max-width-5);
        margin-top: 10px;
        border-radius: 18px;
    }
}


.aboutus {
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
