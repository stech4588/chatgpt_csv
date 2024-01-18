import { defineStore } from 'pinia';
import Cookies from 'js-cookie';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        isAuthenticated: false,
    }),
    actions: {
        login(user) {
            this.user = user;
            this.isAuthenticated = true;
            this.persistState();
        },
        logout() {
            this.user = null;
            this.isAuthenticated = false;
            this.persistState();
        },
        // Method to persist the state as an HttpOnly cookie
        persistState() {
            const authState = JSON.stringify(this.$state);

            // Use js-cookie to set the cookie
            Cookies.set('stech-chatgpt', authState, { expires:  1 / 24  });
        },
        // Method to load the state from an HttpOnly cookie on app start
        hydrateState() {
            const authState = Cookies.get('stech-chatgpt');
            if (authState) {
                this.$patch(JSON.parse(authState));
            }
        },
        // Method to get the authentication token
        getToken() {
            return this.user ? this.user.token : null;
        },
    },
});
