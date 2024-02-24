<template>
    <div class="landing-page-main">

    </div>
</template>

<script>

import CarouselImage from '../components/CarouselImage.vue'

export default {
    name: 'LandingPage',
    components: {
        CarouselImage,
    },
    data(){
        return{
            categories: [
                { image_url: '/images/no_image.jpg', alt: 'Image 1', name: 'Image 1' },
                { image_url: '/images/no_image.jpg', alt: 'Image 1', name: 'Image 2' },
                { image_url: '/images/no_image.jpg', alt: 'Image 1', name: 'Image 3' },
                { image_url: '/images/no_image.jpg', alt: 'Image 1', name: 'Image 4' },
                // Add more image objects as needed
            ],
            collection: [],
            currentIndex: 0,
            currentYear: 2000,
            currentPage: 1,
            imagesPerPage: 3,
            showLoadMoreButton: true,
            currentSlide: 0,
            quantity: 1,
            sideImages: [
                "/images/no_image.jpg",
                "/images/no_image.jpg",
                "/images/no_image.jpg",
                "/images/no_image.jpg",
            ],
            data:{
                image_url: '/images/no_image.jpg', alt: 'Image 1', name: 'Image 1',price: 5,quantity:5,serial_no: 5
            },
            question: {
                name: "",
                email: "",
                phone: "",
                message: "",
            },
            angleIcons: {
                shippingInformation: "angle-down",
                askAQuestion: "angle-down",
            },
            loading: true,
            images: [],
            product_variant:[],
            selectedSize: null,
            selectedColor: null,
        }
    },


    async mounted() {
        setTimeout(() => {
            this.loading = false;
        }, 1500); // 2000 milliseconds (2 seconds)

    },

    methods: {

    }
}
</script>

<style scoped>
.slider-section{
    height: 70vw;
}
</style>
