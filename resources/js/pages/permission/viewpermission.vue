<template>
  <div v-if="loading === false">
    <div v-if="viewPermissionDetails === true">
      <h1>
        Permission Detail {{ $route.params.id }}
      </h1>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-4 boldText">
              ID
            </th>
            <th class="col-4">
              {{ permissionDetails.id }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="boldText">
              Name
            </td>
            <td>{{ permissionDetails.name }}</td>
          </tr>
          <tr>
            <td class="boldText">
              Description
            </td>
            <td>{{ permissionDetails.description }}</td>
          </tr>
          <tr>
            <td class="boldText">
              Category
            </td>
            <td>{{ permissionDetails.category }}</td>
          </tr>
          <tr>
            <td class="boldText">
              Created At
            </td>
            <td>{{ permissionDetails.created_at }}</td>
          </tr>
          <tr>
            <td class="boldText">
              Updated At
            </td>
            <td>{{ permissionDetails.updated_at }}</td>
          </tr>
        </tbody>
      </table>
      <button class="btn black-bg" @click="$router.push({ name: 'permission' })">
        {{ 'Back' }}
      </button>
      <button class="btn linked-icon" @click="$router.push({ path: `/updatepermission/${permissionDetails.id}` })">
        {{ 'Edit Permission' }}
      </button>
      <button class="btn linked-icon" @click="deletePermission(permissionDetails.id)">
        Delete Permission
      </button>
    </div>
    <div v-else class="d-flex justify-content-center align-items-center">
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
  name: 'View',
  data() {
    return {
      permissionDetails: [],
      viewPermissionDetails: false,
      loading: true
    }
  },

  async mounted() {
    if (this.authStore.user === null) {
      this.loading = true
    } else {

      try {
        const permissionsToCheck = ['viewPermissionDetails'];
        const permissionResults = await checkPermissions(permissionsToCheck);
        permissionsToCheck.forEach(permission => {
          if (permissionResults[permission]) {
            this[permission] = true;
            if (permission === 'viewPermissionDetails') {
              this.loading = false;
            }
          }
        });

        await this.$axios.get(`/api/permission/${this.$route.params.id}`)
          .then((response) => {
            this.permissionDetails = response.data.data.data
          })
      } catch (e) {
          handleError(e,this.$toast);
      }
    }
  },

  methods: {
    async deletePermission(id) {
      const permission = await this.showConfirmationDialog('Are you sure you want to delete this Permission?')
      if (permission) {
        try {
          await this.$axios
            .delete(`/api/permission/${id}`)
            .then(response => {
              if (response.data.status === 200) {
                // Redirect home.
                this.$router.push('/permission')
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
