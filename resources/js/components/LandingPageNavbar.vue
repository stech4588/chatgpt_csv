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

        try {
            await this.$axios
                .get('/api/categorylisting')
                .then(response => {
                    this.categories = response.data.data
                })
        } catch (e) {
            handleError(e,this.$toast);
        }

        const menu = document.querySelector(".menu");
        const menuMain = menu.querySelector(".menu-main");
        const goBack = menu.querySelector(".go-back");
        const menuTrigger = document.querySelector(".mobile-menu-trigger");
        const closeMenu = menu.querySelector(".mobile-menu-close");
        let subMenu;

        menuMain.addEventListener("click", (e) =>{
            if(!menu.classList.contains("active")){
                return;
            }
            if(e.target.closest(".menu-item-has-children")){
                const hasChildren = e.target.closest(".menu-item-has-children");
                showSubMenu(hasChildren);
            }
        });
        goBack.addEventListener("click",() =>{
            hideSubMenu();
        })
        menuTrigger.addEventListener("click",() =>{
            toggleMenu();
        })
        closeMenu.addEventListener("click",() =>{
            toggleMenu();
        })
        document.querySelector(".menu-overlay").addEventListener("click",() =>{
            toggleMenu();
        })
        function toggleMenu(){
            menu.classList.toggle("active");
            document.querySelector(".menu-overlay").classList.toggle("active");
        }
        function showSubMenu(hasChildren){
             subMenu = hasChildren.querySelector(".sub-menu");
            if (subMenu) {
                subMenu.classList.add("active");
                subMenu.style.animation = "slideLeft 0.5s ease forwards";
                const menuTitle = hasChildren.querySelector("i").parentNode.childNodes[0].textContent;
                menu.querySelector(".current-menu-title").innerHTML = menuTitle;
                menu.querySelector(".mobile-menu-head").classList.add("active");
            }
        }
        function  hideSubMenu(){
            subMenu.style.animation = "slideRight 0.5s ease forwards";
            setTimeout(() =>{
                subMenu.classList.remove("active");
            },300);
            menu.querySelector(".current-menu-title").innerHTML="";
            menu.querySelector(".mobile-menu-head").classList.remove("active");
        }
        window.onresize = function(){
            if(this.innerWidth >991){
                if(menu.classList.contains("active")){
                    toggleMenu();
                }

            }
        }
        this.$router.beforeEach((to, from, next) => {
            // Check if the new route is a collection page
            if (to.name === "collection") {
                // Close the mobile menu
                document.querySelector(".menu").classList.remove("active");
                document.querySelector(".menu-overlay").classList.remove("active");

            }
            this.closeSearchPopup();
            // Continue with the route change
            next();
        });
    },
    methods: {
        changeRoute(id) {
            this.closeSearchPopup();
            this.$router.push(`/product-detail/${id}`)
        },
        // Function to handle search
        async performSearch() {

            try {
                await this.$axios
                    .get(`/api/productslisting?page=${this.currentPage}&view=${this.imagesPerPage}&search=${this.searchInput}`)
                    .then(response => {
                        this.searchResults = response.data.data.data
                    })
            } catch (e) {
                handleError(e,this.$toast);
            }
        },
        openSearchPopup() {
            this.isSearchPopupOpen = true;
        },
        closeSearchPopup() {
            this.isSearchPopupOpen = false;
            this.searchInput = ""; // Clear search input when closing the popup
            this.searchResults = []; // Clear search results when closing the popup
        },
        handleError(e,toast) {
            if (e.response && e.response.status === 429) {
                toast.error('Too many login attempts. Please try again later.', { position: 'bottom-right', duration: 3000 });
            } else if (e.response && e.response.status === 422) {
                toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
            } else if (e.response && e.response.status === 403) {
                toast.error(e.response.data.message, { position: 'bottom-right', duration: 3000 });
            } else {
                toast.error('An error occurred. Please try again later.', { position: 'bottom-right', duration: 3000 });
            }
        },
        async logout() {
            localStorage.removeItem('cart');
            localStorage.removeItem('productDetails');
            await this.LogoutApiCall();
            this.authStore.logout()
            await this.$router.push('/')

        },
        async LogoutApiCall() {
            try {
                const response = await this.$axios.post('/api/logout');
                return response.data;
            } catch (e) {
                throw e;
            }
        },
    },
}
</script>

<style scoped>



</style>
