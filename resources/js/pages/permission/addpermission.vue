<template>
  <div v-if="loading === false">
    <div v-if="permissionsAdd === true">
      <h1>
        Add Permission
      </h1>
      <div class="row">
        <div class="col-lg-10 m-auto">
            <form @submit.prevent="addInPermission" @keydown="form.onKeydown($event)">
              <!-- Name -->
              <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                <div class="col-md-7">
                  <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                    type="text" name="name" required>
                  <has-error :form="form" field="name" />
                </div>
              </div>
              <!-- Description -->
              <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Description') }}</label>
                <div class="col-md-7">
                  <input v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }"
                    class="form-control" type="text" name="description" required>
                  <has-error :form="form" field="description" />
                </div>
              </div>
              <!-- Category -->
              <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Category') }}</label>
                <div class="col-md-7">
                  <input v-model="form.category" :class="{ 'is-invalid': form.errors.has('category') }"
                    class="form-control" type="text" name="category" required>
                  <has-error :form="form" field="category" />
                </div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-7 offset-md-3 d-flex">
                  <!-- Submit Button -->
                  <button class="btn grey-bg" :disabled="form.busy">
                    {{ 'Add' }}
                  </button>
                  <button @click="$router.push({ name: 'permission' })" class="btn black-bg ms-1">
                    {{ 'Cancel' }}
                  </button>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>
    <div v-else>
      <Unauthorized />
    </div>
  </div>
  <div v-else>
    <Loader />
  </div>
</template>

<script>
export default {
    inject: ['authStore'],
  name: 'Add',

  data () {
        return {
      form: new this.$form({
          name: '',
          description: '',
          category: ''
      }),
      roleId: 0,
      permissionsAdd: false,
      loading: true
  }
  },

  async mounted() {
      this.$useHead({
          title: 'Permission',
          description: 'Add Permission page'
      });
    if (this.authStore.user === null) {
      this.loading = false
    } else {

      try {
        const permissionsToCheck = ['permissionsAdd'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'permissionsAdd') {
            this.loading = false;
          }
        }
      });
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  },

  methods: {
    async addInPermission() {
      try {
        const { data } = await this.form.post('/api/permission')
        if (data.message === 'Permission created successfully.') {
          this.$toast.success( 'Permission created successfully.',{ position: 'top-right', duration: 3000 })
          this.$router.push('/permission')
        }
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  }
}
</script>

<style scoped></style>
