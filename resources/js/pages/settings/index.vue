<template>
    <div>

        <div >
            <div class="row ">
                <div class="col-md-12">
                    <div class="settings-tabs">
                        <header class="header">
                            <h1 class="fs-3">Profile Setting</h1>
                        </header>
                        <ul class="nav nav-tabs">
                            <li v-for="tab in tabs" :key="tab.route" class="nav-item">
                                <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">{{ tab.name }}</router-link>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <router-view />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        tabs() {
            return [
                {
                    icon: 'user',
                    name: 'Profile',
                    route: 'settings.profile'
                },
                {
                    icon: 'lock',
                    name: 'Password',
                    route: 'settings.password'
                }
            ]
        }
    }
}
</script>
