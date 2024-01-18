import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';
function isAuthenticated() {
    return useAuthStore().isAuthenticated
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        //Ecommerce
        { path: '/', name: 'landingPage', component: ()=> import('../pages/dashboard.vue'),meta: { requiresAuth: true }},

        { path: '/login', name: 'login', component:  ()=> import('../pages/auth/Login.vue'),meta: { layout: 'basic' }},
        { path: '/register', name: 'register', component:  ()=> import('../pages/auth/Register.vue'),meta: { layout: 'basic' }},
        { path: '/dashboard', name: 'dashboard', component:  ()=> import('../pages/dashboard.vue'),meta: { requiresAuth: true }},
        { path: '/template1', name: 'template1', component:  ()=> import('../pages/template1.vue'),meta: { requiresAuth: true }},
        { path: '/template2', name: 'template2', component:  ()=> import('../pages/template2.vue'),meta: { requiresAuth: true }},
        // Permissions Routes
        { path: '/permission', name: 'permission', component:  ()=> import('../pages/permission/permission.vue'),meta: { requiresAuth: true } },
        { path: '/addpermission', name: 'permission.add', component:  ()=> import('../pages/permission/addpermission.vue'),meta: { requiresAuth: true } },
        { path: '/updatepermission/:id', name: 'permission.update', component:  ()=> import('../pages/permission/updatepermission.vue'),meta: { requiresAuth: true } },
        { path: '/viewpermission/:id', name: 'permission.view', component:  ()=> import('../pages/permission/viewpermission.vue'),meta: { requiresAuth: true } },
        // Role Routes
        { path: '/role', name: 'role', component:  ()=> import('../pages/role/role.vue'),meta: { requiresAuth: true } },
        { path: '/addrole', name: 'role.add', component:  ()=> import('../pages/role/addrole.vue'),meta: { requiresAuth: true } },
        { path: '/updaterole/:id', name: 'role.update', component:  ()=> import('../pages/role/updaterole.vue'),meta: { requiresAuth: true } },
        { path: '/viewrole/:id', name: 'role.view', component:  ()=> import('../pages/role/viewrole.vue'),meta: { requiresAuth: true } },
        // Customers Routes
        { path: '/customers', name: 'customers', component:  ()=> import('../pages/customers/listCustomers.vue'),meta: { requiresAuth: true } },
        { path: '/addcustomers', name: 'customers.add', component:  ()=> import('../pages/customers/addCustomers.vue'),meta: { requiresAuth: true } },
        { path: '/updatecustomers/:id', name: 'customers.update', component:  ()=> import('../pages/customers/updateCustomers.vue'),meta: { requiresAuth: true } },
        { path: '/viewcustomers/:id', name: 'customers.view', component:  ()=> import('../pages/customers/viewCustomers.vue'),meta: { requiresAuth: true } },

        {
            path: '/settings',
            component: ()=> import('../pages/settings/index.vue'),
            children: [
                { path: '', redirect: { name: 'settings.profile' } },
                { path: 'profile', name: 'settings.profile', component: ()=> import('../pages/settings/profile.vue') },
                { path: 'password', name: 'settings.password', component: ()=> import('../pages/settings/password.vue') }
            ]
            ,meta: { requiresAuth: true }
        },
        {
            path: '/:catchAll(.*)',
            redirect: (to) => {
                if (to.meta.requiresAuth && !isAuthenticated()) {
                    return { name: 'login' };
                }
            },
        },
    ],

});
router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !isAuthenticated()) {
        next({ name: 'login' });
    } else {
        next();
    }
});

export default router;
