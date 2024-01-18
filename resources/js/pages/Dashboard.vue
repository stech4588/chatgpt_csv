<template>
    <div class="dashboard"  >

    </div>

</template>

<script>

export default {
    inject: ['authStore'],
    name: "Dashboard",
    data () {
        return {
            selectedDateRange: 'all',
            selectedDateRangeCustomers: 'all',
            salesOverview: {},
            totalCustomers: 0,
            newCustomers: 0,
            totalProducts: [],
            adminUser: null
        }
    },
    async mounted() {

        try {

        } catch (e) {
            handleError(e,this.$toast)
        }
        try {
            if (this.authStore.user === null) {
                this.adminUser = false
            } else {
                const permissionsToCheck = ['adminUser'];
                const permissionResults = await checkPermissions(permissionsToCheck);
                permissionsToCheck.forEach(permission => {
                    if (permissionResults[permission]) {
                        if (permission === 'adminUser') {
                            this.adminUser = true;
                        }
                    }else{
                        this.adminUser = false
                    }
                });
            }
        }
        catch (e) {
                handleError(e, this.$toast)
            }

    },
    methods: {
    }
}
</script>

<style scoped>

</style>
