import { createApp } from 'vue';
import { createHead } from "@vueuse/head";
import App from './components/App.vue';
import router from './router/routes.js';
import axios from 'axios'
import AxiosPlugin from './plugins/axios.js';
import fontAwesomePlugin from './plugins/fontawesome.js';
import Form from 'vform';
import { createPinia } from 'pinia';
import { useAuthStore } from './store/auth.js';
import { useHead } from '@vueuse/head';
import installToast from './plugins/toast.js';
import '../../public/css/bootstrap.css'
import '../../public/js/bootstrap.js'
import Swal from 'sweetalert2'

const app = createApp(App);
const head = createHead();

app.config.productionTip = true;

// Import and register your components
import Navbar from "./components/Navbar.vue";
import LandingPageNavbar from "./components/LandingPageNavbar.vue";
import Child from "./components/Child.vue";
import Loader from "./components/Loader.vue";
import Unauthorized from "./components/Unauthorized.vue";
import VButton from './components/Button.vue'
import AppPagination from './components/AppPagination.vue'
import Footer from './components/Footer.vue'
import { HasError, AlertError, AlertSuccess } from 'vform/components/bootstrap5'
[  Navbar, LandingPageNavbar,  Child, Loader, Unauthorized, VButton, HasError, AlertError, AlertSuccess, AppPagination, Footer ].forEach(Component => {
    app.component(Component.name, Component);
});
app.component('fa', fontAwesomePlugin);

app.use(AxiosPlugin);
app.use(fontAwesomePlugin);
installToast(app);

app.use(createPinia());
// Provide the useAuthStore function globally
app.provide('authStore', useAuthStore());
useAuthStore().hydrateState();
// Use the Vue Router instance
app.use(router);
app.use(head);
app.config.globalProperties.$useHead = useHead;
app.config.globalProperties.$form = Form;


async function checkPermissions(permissionNames) {
    try {
        const response = await axios.get('/api/check-permissions', {
            params: {
                permissions: permissionNames
            }
        });
        return response.data.permissions;
    } catch (error) {
        return error;
    }
}
window.checkPermissions = checkPermissions;
window.Title = "Chat GPT";
window.priceUnit = "Rs.";

function handleError(e,toast) {
    if (e.response && e.response.status === 429) {
        toast.error('Too many login attempts. Please try again later.', { position: 'bottom-right', duration: 3000 });
    } else if (e.response && e.response.status === 422) {
       toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
    } else if (e.response && e.response.status === 403) {
       toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
    } else {
        toast.error('An error occurred. Please try again later.', { position: 'bottom-right', duration: 3000 });
    }
}
window.handleError = handleError;
function scrollToTop() {
    // Scroll to the top of the page
    window.scrollTo({
        top: 0,
        behavior: "smooth", // Optional: Add smooth scrolling behavior
    });
}
window.scrollToTop = scrollToTop;
app.config.globalProperties.showConfirmationDialog = async function (title) {
    const result = await Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel it!'
    });

    return result.isConfirmed;
};

app.mount('#app');
