<template>
    <div class="main-layout">
        <Navbar />
        <div class="container-fluid">
            <div class="row flex-nowrap">
                <div class="col-auto px-0 ">
                    <div id="sidebar"  class="border-end">
                        <div id="sidebar-nav" v-if="authStore.isAuthenticated" class="list-group border-0 rounded-0 text-sm-start min-vh-100">
                            <router-link :to="{ name: 'dashboard' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                                <h5 class="sidebar-title" :class="{ 'active': $route.name === 'dashboard' }">
                                    <font-awesome-icon icon="home" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Home</span>
                                </h5>
                            </router-link>
                          <router-link v-if="roleView" :to="{ name: 'role' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                              <h5 class="sidebar-title" :class="{ 'active': $route.name === 'role' }">
                                  <font-awesome-icon icon="user" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Role</span>
                              </h5>
                          </router-link>
                            <router-link v-if="customersView" :to="{ name: 'customers' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                              <h5 class="sidebar-title" :class="{ 'active': $route.name === 'customers' }">
                                  <font-awesome-icon icon="users" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Users</span>
                              </h5>
                          </router-link>
                            <router-link v-if="template1" :to="{ name: 'template1' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                              <h5 class="sidebar-title" :class="{ 'active': $route.name === 'template1' }">
                                  <font-awesome-icon icon="users" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Template1</span>
                              </h5>
                          </router-link>
                            <router-link v-if="template2" :to="{ name: 'template2' }" class="list-group-item border-end-0 d-inline-block text-truncate" data-bs-parent="#sidebar">
                              <h5 class="sidebar-title" :class="{ 'active': $route.name === 'template2' }">
                                  <font-awesome-icon icon="users" fixed-width />
                                    <span class="sidebar-label" :class="{ 'hidden': isSidebarCollapsed }">Template2</span>
                              </h5>
                          </router-link>
                        </div>
                    </div>
                </div>
                <main class="col ps-md-2 pt-2 content " >
                    <a href="#" @click="toggleSidebar"   class="border rounded-3 p-1 text-decoration-none"><font-awesome-icon icon="bars" fixed-width /></a>
                    <child />
                </main>
            </div>
        </div>

    </div>
</template>
<script>

export default {
    inject: ['authStore'],
    name: 'MainLayout',
    data(){
        return {
            roleView:false,
            customersView: false,
            template1: false,
            template2: false,
            isSidebarCollapsed: false,
        }
    },
    async mounted() {
        if (this.authStore.user !== null) {

            const permissionsToCheck = [
                'roleView',
                'customersView',
                'template1',
                'template2',
            ];
            const permissionResults = await checkPermissions(permissionsToCheck);
            permissionsToCheck.forEach(permission => {
                if (permissionResults[permission]) {
                    this[permission] = true;

                }
            });
        }
    },
    methods: {
        toggleSidebar() {
            this.isSidebarCollapsed = !this.isSidebarCollapsed;
        },
    },

}
</script>

<style scoped>
.hidden {
    display: none;
}
</style>
