<template>
<div>
    <header class="header">
        <div class="header-main">
            <div class="row v-center">
                <div class="col ">

                </div>
                <div class="col">
                    <div class="logo">
                        <a class="txt-c cur-point" @click="$router.push('/')">{{Title}}</a>
                    </div>
                </div>
                <div class="col item-right cur-point">
                    <a v-if="authStore.user && $route.name === 'userDashboard'" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout" @click="logout">
                        <font-awesome-icon icon="sign-out" fixed-width />
                    </a>
                    <a v-else-if="authStore.user" @click="$router.push({ name: 'userDashboard' })" data-bs-toggle="tooltip" data-bs-placement="top" title="Dashboard" class="ico-c" >
                        <font-awesome-icon icon="user" fixed-width />
                    </a>
                    <a v-else @click="$router.push({ name: 'login' })" data-bs-toggle="tooltip" data-bs-placement="top" title="Login" class="ico-c">
                        <font-awesome-icon icon="sign-in" fixed-width />
                    </a>
                </div>
            </div>

        </div>
    </header>
    <!-- Search popup -->
    <div v-if="isSearchPopupOpen" class="search-popup">
        <div class="search-popup-content">
            <div class="input-container ">
                <font-awesome-icon icon="search" fixed-width />
                <input
                    class="search-input"
                    v-model="searchInput"
                    @input="performSearch"
                    placeholder="Enter your search query..."
                />
                <i class="close-icon" @click="closeSearchPopup">X</i>
            </div>
            <div class="search-results">
                <div class="search-result-grid">
                    <div class="search-result-item" v-for="(result, index) in searchResults" :key="index">
                        <img :src="result.image_url" alt="Search Result" @click="changeRoute(result.id)" class="cur-point" />
                        <p class="image-name">{{ result.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
</template>

<script>
export default {
    inject: ['authStore'],
    name: "LandingPageNavbar",
    data(){
        return {
            categories: [],
            isMobileMenuOpen: false,
            isSearchPopupOpen: false,
            searchInput: "",
            searchResults: [],
            currentPage:1,
            imagesPerPage:6,
            Title: ''
        }
    },
    created() {
        this.Title = Title;

        if (this.authStore.user === null) {
            this.$router.push('/')
        }
    },
    async mounted() {
    },
    methods: {

    },
}
</script>

<style scoped>



</style>
