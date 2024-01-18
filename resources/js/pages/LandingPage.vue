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
    computed: {
        selectedVariant() {
            // Find the selected variant based on the selected color and size
            return this.product_variant.find(
                (variant) =>
                    variant.size.name === this.selectedSize &&
                    variant.color === this.selectedColor
            );
        },
        availableColors() {
            if (!this.selectedSize) {
                // If no size is selected, return an empty array
                return [];
            }

            // Filter product variants by selected size
            const filteredVariants = this.product_variant.filter(
                (variant) => variant.size.name === this.selectedSize
            );

            // Extract unique colors from filtered variants
            return [...new Set(filteredVariants.map((variant) => variant.color))];
        },
    },

    async mounted() {
        setTimeout(() => {
            this.loading = false;
        }, 1500); // 2000 milliseconds (2 seconds)
        this.currentYear = new Date().getFullYear()

            await this.getCategories();
            await this.getProducts();
        if (this.product_variant.length > 0) {
            // Select the first size and color from the variants
            this.selectedSize = this.product_variant[0].size.name;
            this.selectedColor = this.product_variant[0].color;
        }

        const modal = document.getElementById('productModal');
        const closeModal = document.getElementById('closeModal');

        function openModal() {
            modal.style.display = 'block';
        }

        function closeModalFunction() {
            modal.style.display = 'none';
        }

        const quickPreviewButtons = document.querySelectorAll('.quick-preview-button');

        quickPreviewButtons.forEach(button => {
            button.addEventListener('click', openModal);
        });

        closeModal.addEventListener('click', closeModalFunction);

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModalFunction();
            }
        });
    },

    methods: {

        async getProducts() {
            try {
                const response = await this.$axios.get(`/api/productslisting?page=${this.currentPage}&view=${this.imagesPerPage}`);

                    this.collection = [...this.collection, ...response.data.data.data];
                    if (response.data.data.current_page === response.data.data.last_page) {
                        this.showLoadMoreButton = false; // No more images to load, hide the button
                }

            } catch (e){

            }

        },
        async getCategories() {
            try {
                await this.$axios
                    .get(`/api/categorylisting?parent=true`)
                    .then(response => {
                        this.categories =  response.data.data
                    })
            } catch (e) {
                handleError(e,this.$toast);
            }
        },
        selectSize(item) {
            this.selectedSize = item.size.name;
            this.selectedColor = null; // Reset selected color when a new size is chosen
        },
        selectColor(item) {
            this.selectedColor = item;
        },
        async loadMoreImages() {
            // Increment the current page
            this.currentPage++;

            // Fetch more images from the backend
            await this.getProducts();

            // Scroll to the newly loaded images (optional)
            this.scrollToNewImages();
        },
        scrollToNewImages() {
            // You can use JavaScript to scroll to the newly loaded images for a better user experience
            // For example, you can use the `scrollIntoView` method.
            const lastImageElement = document.querySelector(".collection-image:last-child");
            if (lastImageElement) {
                lastImageElement.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        },
        toggleCollapse(section) {
            if (this.angleIcons[section] === "angle-down") {
                this.angleIcons[section] = "angle-up";
            } else {
                this.angleIcons[section] = "angle-down";
            }
        },
        selectImage(index) {
            this.currentSlide = index;
        },
        increaseQuantity() {
            if (this.quantity < 100) {
                this.quantity++;
            }
        },
        decreaseQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.sideImages.length) % this.sideImages.length;
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.sideImages.length;
        },
        addToCart() {
            // Create a product object with relevant details
            const productToAdd = {
                id: this.data.id, // Use a unique identifier for the product
                serial_no: this.data.serial_no, // Use a unique identifier for the product
                name: this.data.name,
                sale: this.data.sale_id ? this.data.sale : null,
                size: this.selectedSize,
                color: this.selectedColor,
                price: this.selectedVariant.price,
                quantity: this.quantity,
                image_url: this.data.image_url,
                variant_id: this.selectedVariant.id,
                // Add any other relevant product details here
            };

            // Retrieve the current cart data from local storage or create an empty array if it doesn't exist
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            // Check if the product with the same id already exists in the cart
            const existingProductIndex = cart.findIndex((item) => item.id === productToAdd.id && item.variant_id === productToAdd.variant_id);

            if (existingProductIndex !== -1) {
                // If the product exists in the cart, update its quantity
                cart[existingProductIndex].quantity += productToAdd.quantity;
            } else {
                // If the product is not in the cart, add it
                cart.push(productToAdd);
            }

            // Store the updated cart data in local storage
            localStorage.setItem("cart", JSON.stringify(cart));

            // Optionally, you can display a confirmation message to the user
            this.$toast.success("Product added to cart!", { position: 'bottom-right', duration: 3000 });

            // You can also redirect the user to the cart page or perform any other desired action
            this.$router.push("/cart");
        },

        openModal(product) {
            // Set the current product data for the modal
            this.data = product;
            this.images = product.side_image_urls;
            this.product_variant = product.product_variant
            // Show the modal
            const modal = document.getElementById('productModal');
            if (modal) {
                modal.style.display = 'block';
            }
        },

    }
}
</script>

<style scoped>
.slider-section{
    height: 70vw;
}
</style>
