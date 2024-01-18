<template>
    <div id="app">
        <component :is="layout" v-if="layout" />
    </div>
</template>

<script setup>
import { ref, markRaw } from 'vue'
import { useRouter } from 'vue-router'
import {useHead} from "@vueuse/head";
import './styles.css'

useHead({
    title: 'E-Com Web',
    description: 'My E-Com Web'
});
// Load layout components dynamically.
const layouts = import.meta.globEager('../layouts/*.vue')
const layoutComponents = Object.keys(layouts).reduce((components, path) => {
    const name = path.match(/\.\/layouts\/(.*)(\.vue)$/)[1]
    components[name] = layouts[path].default
    return components
}, {})


const router = useRouter()

const layout = ref(null)
const defaultLayout = 'admin'

const setLayout = (newLayout) => {
    if (!newLayout || !layoutComponents[newLayout]) {
        newLayout = defaultLayout
    }
    layout.value = markRaw(layoutComponents[newLayout])
}

// Update layout whenever route changes
router.afterEach(to => {
    setLayout(to.meta.layout)
})
</script>

