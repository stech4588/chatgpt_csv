<template>
    <div v-if="loading === false">
        <div v-if="customersView === true">
            <h1>
                User Detail {{ $route.params.id }}
            </h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-4 boldText">
                        ID
                    </th>
                    <th class="col-4">
                        {{ customerDetails.id }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="boldText">
                        Name
                    </td>
                    <td>{{ customerDetails.name }}</td>
                </tr>
                <tr>
                    <td class="boldText">
                        Email
                    </td>
                    <td>
                        {{customerDetails.email}}
                    </td>
                </tr>
                <tr>
                    <td class="boldText">
                        Phone no.
                    </td>
                    <td>
                        {{customerDetails.phone_no}}
                    </td>
                </tr>
                <tr>
                    <td class="boldText">
                        Role
                    </td>
                    <td>
                        {{customerDetails.role.name}}
                    </td>
                </tr>
                </tbody>
            </table>
            <button @click="$router.push({ name: 'customers' })" class="btn black-bg">
                {{ 'Back' }}
            </button>
            <button v-if="customersUpdate" @click="$router.push({ path: `/updatecustomers/${customerDetails.id}` })" class="btn linked-icon">
                {{ 'Edit' }}
            </button>
            <button v-if="customersDelete" class="btn linked-icon" @click="deleteCustomers(customerDetails.id)">
                Delete
            </button>
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
    name: 'View',
    inject: ['authStore'],

    data() {
        return {
            customerDetails: [],
            customersUpdate: false,
            customersDelete: false,
            customersView: false,
            loading: true
        }
    },

    async mounted() {
        if (this.authStore.user === null) {
            this.loading = false
        } else {


            const permissionsToCheck = ['customersView', 'customersDelete', 'customersUpdate'];
            const permissionResults = await checkPermissions(permissionsToCheck);
            permissionsToCheck.forEach(permission => {
                if (permissionResults[permission]) {
                    this[permission] = true;
                    if (permission === 'customersView') {
                        this.loading = false;
                    }
                }
            });

            try {
                await this.$axios
                    .get(`/api/customer/${this.$route.params.id}`)
                    .then(response => {
                        this.customerDetails = response.data.data
                    })
            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    },
    methods: {
        async deleteCustomers(id) {
            const permission = await this.showConfirmationDialog('Are you sure you want to delete this Customer?')
            if (permission) {
                try {
                    await this.$axios
                        .delete(`/api/customer/${id}`)
                        .then(response => {
                            if (response.status === 200) {
                                this.$toast.success( response.data.message, { position: 'top-right', duration: 3000 })
                                // Redirect home.
                                this.$router.push('/customers')
                            }
                        })
                } catch (e) {
                    handleError(e,this.$toast);
                }
            }

        }
    }
}
</script>

<style scoped></style>
