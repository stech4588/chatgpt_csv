<template>
    <div v-if="loading === false">
        <div v-if="customersAdd === true">
            <h1>
                Add User
            </h1>
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <form @submit.prevent="addInRole" @keydown="form.onKeydown($event)">
                        <!-- Title -->
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                                       type="text" name="name" required
                                >
                                <has-error :form="form" field="name" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control"
                                       type="text" name="email" required
                                >
                                <has-error :form="form" field="email" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control"
                                       type="text" name="password" required
                                >
                                <has-error :form="form" field="password" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Phone no.') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.phone_no" :class="{ 'is-invalid': form.errors.has('phone_no') }" class="form-control"
                                       type="text" name="phone_no" required
                                >
                                <has-error :form="form" field="phone_no" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Role') }}</label>
                            <div class="col-md-7">
                                <select v-model="form.role_id" class="form-control" name="role_id" required>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <button class="btn grey-bg" :disabled="form.busy">
                                    {{ 'Add' }}
                                </button>
                                <button @click="$router.push({ name: 'role' })" class="btn black-bg ms-1">
                                    {{ 'Cancel' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-else>
            <Unauthorized />
        </div>
    </div>
    <div v-else>
        <Loader />
    </div>
</template>

<script>

export default {
    inject: ['authStore'],
    name: 'Add',

    data() {
        return {
            form: new this.$form({
                name: '',
                email: '',
                password: '',
                phone_no: '',
                role_id: 2
            }),
            roles: [],
            addedSuccessful: false,
            loading: true,
            customersAdd: false
        }
    },

    async mounted () {
        if (this.authStore.user === null) {
            this.loading = true
        } else {

            const permissionsToCheck = ['customersAdd'];
            const permissionResults = await checkPermissions(permissionsToCheck);
            permissionsToCheck.forEach(permission => {
                if (permissionResults[permission]) {
                    this[permission] = true;
                    if (permission === 'customersAdd') {
                        this.loading = false;
                    }
                }
            });
            try {
                const response = await this.$axios.get('/api/customer/create')

                this.roles = response.data.data
            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    },

    methods: {
        async addInRole () {
            try {
                const { data } = await this.form.post('/api/customer')

                if (data.status === 200) {
                    this.$toast.success( data.message, { position: 'top-right', duration: 3000 })
                    await this.$router.push('/customers')
                }
            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    }
}
</script>

<style scoped></style>
