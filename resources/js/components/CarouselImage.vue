<template>
    <div class="carousel-container">
        <Carousel  :autoplay="5000" :wrap-around="true" v-if="banners">
            <Slide v-for="(slide, index) in banners" :key="index">
                <div class="carousel__item">
                    <img :src="slide.image_url" :alt="`Slide ${index + 1}`" class="carousel__image"  />
                </div>
            </Slide>

            <template #addons>
                <Pagination />
            </template>
        </Carousel>
    </div>
</template>

<script>
import { Carousel, Pagination, Slide } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

export default {
    name: 'CarouselImage',
    data() {
        return {
            banners: [
                // Add more slide content as needed
            ],
            windowWidth: window.innerWidth, // Initial window width
        };
    },
    components: {
        Carousel,
        Slide,
        Pagination,
    },
    async created() {
        await this.getBanners()
    },


    methods: {
        handleResize() {
            this.windowWidth = window.innerWidth;
        },
        async getBanners() {
            try {
                await this.$axios
                    .get('/api/bannerlisting?pageName=HomePage')
                    .then(response => {
                        this.banners = response.data.data
                    })
            } catch (e) {
                handleError(e,this.$toast);
            }
        }
    },
};
</script>
<style scoped>


/* Customize other styles as needed */
</style>
