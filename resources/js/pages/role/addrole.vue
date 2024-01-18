<template>
  <div v-if="loading === false">
    <div v-if="roleAdd === true">
      <h1>
        Add Role
      </h1>
      <div class="row">
        <div class="col-lg-12 m-auto">
            <form @submit.prevent="addInRole" @keydown="form.onKeydown($event)">
              <!-- Title -->
              <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                <div class="col-md-7">
                  <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                         type="text" name="name" required
                  >
                  <has-error :form="form" field="name" />
                </div>
              </div>
                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label text-md-end">{{ ('description') }}</label>
                    <div class="col-md-7">
                        <input v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }" class="form-control"
                               type="text" name="description" required
                        >
                        <has-error :form="form" field="description" />
                    </div>
                </div>
              <!-- Permissions -->
              <div class="mb-3 row">
                <h5 class="col-md-3 text-md-end">
                  {{ ('Select Permissions') }}
                </h5>
                <div class="col-md-9">
                  <div class="row">
                    <div v-for="(permissionList, category) in permissions" :key="category" class="col-md-4 mb-2">
                      <h5 class="text-capitalize">{{ category }}</h5>
                      <div v-for="entry in permissionList" :key="entry.id">
                        <div class="form-check">
                          <input :id="'permission_' + entry.id" v-model="form.permission_id" class="form-check-input"
                            type="checkbox" :value="entry.id">
                          <label class="form-check-label" :for="'permission_' + entry.id">{{ entry.name }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <has-error :form="form" field="permission_id" />
                </div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-7 offset-md-3 d-flex">
                  <button class="btn grey-bg" :disabled="form.busy">
                    {{ 'Add' }}
                  </button>
                  <button @click="$router.push({ name: 'role' })" class="btn black-bg ms-1">
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

  data() {
        return {
            form: new this.$form({
                name: '',
                description: '',
                permission_id: []
            }),
            permissions: {},
            categories: [],
            addedSuccessful: false,
            loading: true,
            roleAdd: false
}
  },

  async mounted () {
    if (this.authStore.user === null) {
      this.loading = true
    } else {

      const permissionsToCheck = ['roleAdd'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'roleAdd') {
            this.loading = false;
          }
        }
      });
      try {
        const response = await this.$axios.get(`/api/permission?groupBy=true`)

        this.permissions = response.data.data
        this.categories = Object.keys(this.permissions)
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  },

  methods: {
    async addInRole () {
      try {
        const { data } = await this.form.post('/api/role')

        if (data.status === 200) {
          this.$toast.success( data.message, { position: 'top-right', duration: 3000 })
          this.$router.push('/role')
        }
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  }
}
</script>

<style scoped></style>
