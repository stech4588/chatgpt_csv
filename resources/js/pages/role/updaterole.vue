<template>
  <div v-if="loading === false">
    <div v-if="roleUpdate === true">
      <h1>
        Update Role {{ $route.params.id }}
      </h1>
      <div class="row">
        <div class="col-lg-12 m-auto">
            <form @submit.prevent="updateRole" @keydown="form.onKeydown($event)">
              <!-- Title -->
              <div class="mb-3 row">
                <label class="col-md-3 col-form-label text-md-end">{{ ('Name') }}</label>
                <div class="col-md-7">
                  <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                    type="text" name="name" required>
                  <has-error :form="form" field="name" />
                </div>
              </div>
                <div class="mb-3 row">
                    <label class="col-md-3 col-form-label text-md-end">{{ ('Description') }}</label>
                    <div class="col-md-7">
                        <input v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }" class="form-control"
                               type="text" name="description" required>
                    <has-error :form="form" field="description" />
                    </div>
                </div>
              <!-- Permissions -->
              <div class="mb-3 row">
                <h5 class="col-md-3 text-md-end">{{ ('Select Permissions') }}</h5>
                <div class="col-md-9">
                  <div class="row">
                    <div v-for="(propName, index) in propNames" :key="index" class="col-md-4 mb-2">
                      <h5 class="text-capitalize">{{ propName }}</h5>
                      <div v-for="entry in permissions[propName]" :key="entry.id">
                        <div class="form-check">
                          <input :id="'permission_' + index" v-model="form.permission_id" class="form-check-input"
                            type="checkbox" :value="entry.id">
                          <label class="form-check-label" :for="'permission_' + index">{{ entry.name }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <has-error :form="form" field="permission_id" />
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-md-7 offset-md-3 d-flex">
                  <!-- Update Button -->
                  <button class="btn grey-bg" :disabled="form.busy">
                    {{ 'Update' }}
                  </button>
                  <button @click="$router.push({ name: 'role' })" class="btn black-bg ms-1">
                    {{ 'Cancel' }}
                  </button>
                </div>
              </div>
            </form>
        </div>
      </div>
<!--      <data-display :data-object="permissions" />-->
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
  name: 'Update',
    inject: ['authStore'],

  data() { return {
      form: new this.$form({
          name: '',
          description: '',
          permission_id: []
      }),
      permissions: {},
      updatedSuccessful: false,
      roleUpdate: false,
      loading: true
  }
  },
    computed: {
        propNames() {
            return Object.keys(this.permissions)
        },

    },

  async mounted() {
    if (this.authStore.user === null) {
      this.loading = false
    } else {
      try {
      const permissionsToCheck = ['roleUpdate'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'roleUpdate') {
            this.loading = false;
          }
        }
      });

        await this.$axios
          .get(`/api/role/${this.$route.params.id}`)
          .then(response => {
            this.form.name = response.data.data.name
            this.form.description = response.data.data.description
            this.form.permission_id = response.data.data.permissions.map(permission => permission.id)
          })

          await this.$axios.get(`/api/permission?groupBy=true`)
          .then(response => {
            this.permissions = response.data.data
          })
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  },

  methods: {
    async updateRole() {
      try {
        const { data } = await this.form.put(`/api/role/${this.$route.params.id}`)
        if (data.status === 200) {
          this.$toast.success('Role updated successfully.', { position: 'top-right', duration: 3000 })
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
