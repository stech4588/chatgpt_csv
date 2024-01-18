<template>
    <div class="register-container">
        <h2 class="text-center justify-content-center login-title">Register</h2>
        <form @submit.prevent="register" class="login-form">
            <div class="form-group my-3">
                <label for="name">USERNAME</label>
                <input v-model="form.name" type="text" id="name" class="form-control" />
            </div>
            <div class="form-group my-3">
                <label for="phone_no">PHONE</label>
                <input v-model="form.phone_no" type="text" id="phone_no" class="form-control" />
            </div>
            <div class="form-group my-3">
                <label for="email">EMAIL</label>
                <input v-model="form.email" type="email" id="email" class="form-control" />
            </div>
            <div class="form-group my-3">
                <label for="password">PASSWORD</label>
                <input v-model="form.password" type="password" id="password" class="form-control" />
            </div>
            <div class=" login-btn">
                <button type="submit" >Register</button>
            </div>
        </form>
        <div class=" text-center justify-content-center mt-3">
            <router-link to="/login" class="navbar-brand">
                <div class="cur-point" >Login</div>
            </router-link>
        </div>
    </div>
</template>

<script>
import {useHead} from "@vueuse/head";
import axios from "axios";
export default {
    name: 'Register',
    inject: ['authStore'],

    setup() {
        useHead({
            title: 'Register',
            description: 'Registration page'
        });
    },
    data() {
        return {
            form: new this.$form({
                name:'',
                phone_no:null,
                email: '',
                password: '',
            }),
        };
    },
    methods: {
        async register() {
            try {
                const response = await this.form.post('/api/register');
                await this.authStore.login(response.data.data);

                this.$toast.success('User Registered successfully', { position: 'bottom-right', duration: 3000 });
                await this.$router.push('/dashboard');
            } catch (e) {
                handleError(e,this.$toast)
            }
        },
    },
};
</script>

<style scoped>
.register-container {
    max-width: 300px;
    margin: 0 auto;
    padding: 20px;
}
</style>
