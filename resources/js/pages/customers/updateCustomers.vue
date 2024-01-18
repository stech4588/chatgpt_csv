<template>
    <div v-if="loading === false">
        <div v-if="customersUpdate === true">
            <h1>
                Update User {{ $route.params.id }}
            </h1>
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <form @submit.prevent="updateCustomers" @keydown="form.onKeydown($event)">
                        <!-- Title -->
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                                       type="text" name="name" required>
                                <has-error :form="form" field="name" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control"
                                       type="text" name="email" required>
                                <has-error :form="form" field="email" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-3 col-form-label text-md-end">{{ ('Phone no.') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.phone_no" :class="{ 'is-invalid': form.errors.has('phone_no') }" class="form-control"
                                       type="text" name="phone_no" required>
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
                                <!-- Update Button -->
                                <button class="btn grey-bg" :disabled="form.busy">
                                    {{ 'Update' }}
                                </button>
                                <button @click="$router.push({ name: 'customers' })" class="btn black-bg ms-1">
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
    name: 'Update',
    inject: ['authStore'],

    data() { return {
        form: new this.$form({
            name: '',
            email: '',
            phone_no: '',
            role_id: 0,
        }),
        customersUpdate: false,
        loading: true,
        roles: [],
    }
    },

    async mounted() {
        if (this.authStore.user === null) {
            this.loading = false
        } else {
            try {
                const permissionsToCheck = ['customersUpdate'];
                const permissionResults = await checkPermissions(permissionsToCheck);
                permissionsToCheck.forEach(permission => {
                    if (permissionResults[permission]) {
                        this[permission] = true;
                        if (permission === 'customersUpdate') {
                            this.loading = false;
                        }
                    }
                });

                await this.$axios
                    .get(`/api/customer/${this.$route.params.id}/edit`)
                    .then(response => {
                        this.form.name = response.data.data[0].name
                        this.form.email = response.data.data[0].email
                        this.form.phone_no = response.data.data[0].phone_no
                        this.form.role_id = response.data.data[0].role_id
                        this.roles = response.data.data[1]

                    })

            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    },

    methods: {
        async updateCustomers() {
            try {
                const { data } = await this.form.post(`/api/updateCustomer/${this.$route.params.id}`)
                if (data.status === 200) {
                    this.$toast.success('Customers updated successfully.', { position: 'top-right', duration: 3000 })
                    this.$router.push('/customers')
                }
            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    }
}
</script>

<style scoped></style>
