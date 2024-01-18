<template>
  <div v-if="loading === false">
    <div v-if="roleView === true">
      <h1>
        Role Detail {{ $route.params.id }}
      </h1>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-4 boldText">
              ID
            </th>
            <th class="col-4">
              {{ roleDetails.id }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="boldText">
              Title
            </td>
            <td>{{ roleDetails.name }}</td>
          </tr>
          <tr>
            <td class="boldText">
              Permissions
            </td>
            <td>
              <!-- Loop through each permission object in roleDetails.permissions -->
              <div v-for="(perm, index) in roleDetails.permissions" :key="index">
                <!-- Display the name of the permission object -->
                {{ perm.name }}
                <!-- If this is not the last permission object, display a comma and space -->
                <span v-if="index !== roleDetails.permissions.length - 1">, </span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <button @click="$router.push({ name: 'role' })" class="btn black-bg">
        {{ 'Back' }}
      </button>
      <button v-if="roleUpdate" @click="$router.push({ path: `/updaterole/${roleDetails.id}` })" class="btn linked-icon">
        {{ 'Edit' }}
      </button>
      <button v-if="roleDelete" class="btn linked-icon" @click="deleteRole(roleDetails.id)">
        Delete
      </button>
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
  name: 'View',
    inject: ['authStore'],

  data() {
    return {
      roleDetails: [],
      rolePermissions: [],
      roleUpdate: false,
      roleDelete: false,
      roleView: false,
      loading: true
    }
  },

  async mounted() {
    if (this.authStore.user === null) {
      this.loading = false
    } else {


      const permissionsToCheck = ['roleView', 'roleDelete', 'roleUpdate'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'roleView') {
            this.loading = false;
          }
        }
      });

      try {
        await this.$axios
          .get(`/api/role/${this.$route.params.id}`)
          .then(response => {
            this.roleDetails = response.data.data
          })
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  },
  methods: {
    async deleteRole(id) {
      const permission = await this.showConfirmationDialog('Are you sure you want to delete this Role?')
      if (permission) {
        try {
          await this.$axios
            .delete(`/api/role/${id}`)
            .then(response => {
              if (response.status === 200) {
                  this.$toast.success( response.data.message, { position: 'top-right', duration: 3000 })
                  // Redirect home.
                this.$router.push('/role')
              }
            })
        } catch (e) {
            handleError(e,this.$toast);
        }
      }

    }
  }
}
</script>

<style scoped></style>
