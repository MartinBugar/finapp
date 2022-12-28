<template>
    <div class="container">
        <div>
            User Id : {{ userId }}
        </div>
        <div>
            Username : {{ name }}
        </div>
        <div>
            Email : {{ email }}
        </div>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    data() {
        return {
            userId: null,
            name: null,
            email: null,
        }
    },
    created() {
        if (window.Laravel.user) {
            this.userId = window.Laravel.user.id;
            this.name = window.Laravel.user.name;
            this.email = window.Laravel.user.email;
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
