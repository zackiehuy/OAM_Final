<template>
<div>
    <form class="login" @submit.prevent="login">
        <h1>Sign in</h1>
        <label>User name</label>
        <input required v-model="email" type="text" placeholder="Snoopy" />
        <label>Password</label>
        <input required v-model="password" type="password" placeholder="Password" />
        <hr />
        <button type="submit">Login</button>
    </form>
</div>
</template>

<style>
.login {
    display: flex;
    flex-direction: column;
    width: 300px;
    padding: 10px;
}
</style>

<script>
import axios from "axios";
import Vue from "vue";
export default {

    name: "login",
    data() {
        return {
            email: "",
            password: ""
        };
    },
    created() {
        axios.get('/sanctum/csrf-cookie');
    },
    methods: {
        login() {
                axios.post('api/login', {
                    email: this.email,
                    password: this.password
                }).then(response => {
                    this.$store.commit("setUser", response.data);
                    this.$router.push('/');

                }).catch(error => {
            });

        }
    },
};
</script>
