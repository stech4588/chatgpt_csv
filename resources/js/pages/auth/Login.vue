<template>
    <div v-if="loading === false">
        <div class="login-container">
            <h2 class="text-center justify-content-center login-title">Login</h2>
            <form @submit.prevent="handleLogin" class="login-form">
                <div class="form-group my-3">
                    <label for="email" class="my-2">EMAIL</label>
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="text" id="email" name="email" class="form-control" />
                    <has-error :form="form" field="email" />
                </div>
                <div class="form-group my-3">
                    <label for="password" class="my-2">PASSWORD</label>
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password" id="password" name="password" class="form-control" />
                    <has-error :form="form" field="password" />
                </div>
                <div class=" login-btn">
                    <button type="submit" >Login</button>
                </div>
            </form>
            <div class=" text-center justify-content-center mt-3">
                <router-link to="/register" class="navbar-brand">
                    <div class="cur-point" >Create account</div>
                </router-link>
            </div>
        </div>
    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    name: 'Login',
    inject: ['authStore'],

    data() {
        return {
            form: new this.$form({
                email: '',
                password: '',
            }),
            loading: false
        };
    },

    async mounted() {
        this.$useHead({
            title: 'login',
            description: 'login page'
        });
    },

    methods: {
        async handleLogin() {
            this.loading = true;
            try {
                const response = await this.LoginApiCall();
                this.authStore.login(response.data);

                    const permissionsToCheck = ['normalUser','adminUser'];
                    const permissionResults = await checkPermissions(permissionsToCheck);
                    permissionsToCheck.forEach(permission => {
                        if (permissionResults[permission]) {
                                this.loading = false;
                                this.$router.push('/dashboard');
                        }
                    });

                this.$toast.success('User Login successfully', { position: 'bottom-right', duration: 3000 });
                // Redirect to the landing page
            } catch (e) {
                this.loading = false;
                handleError(e,this.$toast);
            }
        },

        async LoginApiCall() {
            try {
                const response = await this.form.post('/api/login');
                return response.data;
            } catch (e) {
                throw e;
            }
        }
    }
};
</script>

<style scoped>
.login-container {
    max-width: 300px;
    margin: 0 auto;
    padding: 20px;
}

</style>
