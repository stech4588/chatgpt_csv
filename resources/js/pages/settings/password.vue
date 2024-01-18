<template>
        <form @submit.prevent="updatePassword" @keydown="form.onKeydown($event)">

            <!-- Password -->
            <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('New Password') }}</label>
                <div class="col-md-7">
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control"
                           type="password" name="password">
                    <has-error :form="form" field="password" />
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Confirm Password') }}</label>
                <div class="col-md-7">
                    <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
                           class="form-control" type="password" name="password_confirmation">
                    <has-error :form="form" field="password_confirmation" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mb-3 row">
                <div class="col-md-9 ms-md-auto">
                    <button class="btn grey-bg" :disabled="form.busy" type="success">
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
            password: '',
            password_confirmation: ''
        }),
    }},
    computed: {
        isPasswordConfirmed() {
            return this.form.password === this.form.password_confirmation;
        }
    },
    methods: {
        async updatePassword() {
            if (this.isPasswordConfirmed) {
                try {
                    const { data } = await this.form.put('/api/updatePassword')
                    if (data.status === 200) {
                        this.$toast.success('Customers updated successfully.', { position: 'top-right', duration: 3000 })
                        await this.$router.push('/dashboard')
                    }
                } catch (e) {
                    handleError(e,this.$toast);
                }
            } else {
                // Handle password confirmation error
                this.form.errors.add({
                    field: 'password_confirmation',
                    msg: 'Password and confirmation do not match'
                });

        }}
    }
}
</script>
