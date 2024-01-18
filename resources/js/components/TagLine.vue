<template>
    <div class="text-slider">
        <transition name="slide-fade" mode="out-in">
            <span :key="currentIndex" class="slide">{{ currentText }}</span>
        </transition>
    </div>
</template>

<script>
export default {
    name: "TagLine",
    data(){
        return{
            texts: [
                "Slide 1 content",
                "Slide 2 content",
                "Slide 3 content"
                // Add more slide content as needed
            ],
            currentIndex: 0
        }
    },
    computed: {
        currentText() {
            return this.texts[this.currentIndex];
        }
    },
    mounted() {
        this.startAutoSlider();
    },
    methods: {
        startAutoSlider() {
            setInterval(() => {
                this.currentIndex = (this.currentIndex + 1) % this.texts.length;
            }, 5000); // Adjust the interval (in milliseconds) as needed
        }
    }

}
</script>

<style scoped>

</style>
