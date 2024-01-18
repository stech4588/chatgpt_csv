<template>
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">

            <!-- Name -->
            <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                <div class="col-md-7">
                    <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name">
                    <has-error :form="form" field="name" />
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Email') }}</label>
                <div class="col-md-7">
                    <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
                    <has-error :form="form" field="email" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Phone no') }}</label>
                <div class="col-md-7">
                    <input v-model="form.phone_no" :class="{ 'is-invalid': form.errors.has('phone_no') }" class="form-control" type="text" name="phone_no">
                    <has-error :form="form" field="phone_no" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mb-3 row">
                <div class="col-md-9 ms-md-auto">
                    <button class="btn grey-bg" :disabled="form.busy">
                        {{ 'Update' }}
                    </button>
                </div>
            </div>
        </form>
</template>

<script>


export default {
    scrollToTop: false,

    data() {
        return {
            form: new this.$form({
            name: '',
            email: '',
            phone_no: '',
        })
        }
    },

    async created () {
        try {
            await this.$axios
                .get('/api/profile')
                .then(response => {
                    this.form.name = response.data.data.name
                    this.form.email = response.data.data.email
                    this.form.phone_no = response.data.data.phone_no
            })

        } catch (e) {
            handleError(e,this.$toast);
        }
    },

    methods: {

        async update() {
            try {
                const { data } = await this.form.put('/api/updateProfile')
                if (data.status === 200) {
                    this.$toast.success('Customers updated successfully.', { position: 'top-right', duration: 3000 })
                    this.$router.push('/dashboard')
                }
            } catch (e) {
                handleError(e,this.$toast);
            }
        },
    }
}
</script>
