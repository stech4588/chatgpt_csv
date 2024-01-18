<template>
    <div>
        <ul class="pagination">
            <!-- Previous Page -->
            <li v-if="currentPage > 1">
                <button @click="changePage(currentPage - 1)">Previous</button>
            </li>

            <!-- Page Numbers -->
            <li v-for="link in visiblePaginationLinks" :key="link" class="pagination-item">
                <button
                    :class="{ 'active-pagination-button': currentPage === link }"
                    @click="changePage(link)"
                >
                    {{ link }}
                </button>
            </li>

            <!-- Next Page -->
            <li v-if="currentPage < totalPages">
                <button @click="changePage(currentPage + 1)">Next</button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name:"pagination",
    props: {
        currentPage: Number,
        totalPages: Number,
        visiblePaginationLinks: Array,
        changePage: Function,
    },
};
</script>

<style scoped>
/* Add your pagination styles here */
</style>
