<template>
  <div v-if="loading === false">
    <div v-if="roleView === true">
      <h1>Roles</h1>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <form @submit.prevent="SearchRole" @keydown="form.onKeydown($event)">
              <div class="input-group mb-3">
                <input v-model="form.searchInput" :class="{ 'is-invalid': form.errors.has('searchInput') }"
                       placeholder="Search Records By TITLE..." class="form-control" type="text" name="searchInput"
                >
                <has-error :form="form" field="searchInput" />
                <div class="input-group-append">
                  <button class="btn grey-bg ms-2" :disabled="form.busy">
                    {{ 'Search' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="text-end my-3">
        <router-link v-if="roleAdd" :to="{ name: 'role.add' }" class="btn linked-icon">
            <font-awesome-icon icon="plus" fixed-width />
        </router-link>
      </div>

      <div v-if="currentPageData.length > 0">
        <table class="table table-bordered ">
          <thead class="text-white grey-bg">
            <tr>
              <th class="">
                ID
              </th>
              <th class="col-1">
                Title
              </th>
                <th class="col-2">
                    Description
                </th>
              <th class="col-2">
                Permissions
              </th>
              <th class="col-8 text-center">
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in currentPageData" :key="index">
              <td>{{ item.id }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.description }}</td>
              <td>
                <div class="d-flex flex-wrap">
                  <div v-for="(perm, index) in item.permissions" :key="index">
                    {{ perm.name }}
                    <span v-if="index !== item.permissions.length - 1">, </span>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <router-link :to="{ path: `/viewrole/${item.id}` }" class="btn linked-icon">
                    <font-awesome-icon icon="eye" fixed-width />
                </router-link>
                <router-link v-if="roleUpdate " :to="{ path: `/updaterole/${item.id}` }" class="btn linked-icon">
                    <font-awesome-icon icon="pen" fixed-width />
                </router-link>
                <button v-if="roleDelete " class="btn linked-icon" @click="deleteRole(item.id)">
                    <font-awesome-icon icon="trash" fixed-width />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div>
            <pagination
                :currentPage="currentPage"
                :totalPages="totalPages"
                :visiblePaginationLinks="visiblePaginationLinks"
                :changePage="changePage"
            ></pagination>
        </div>
      </div>
      <div v-else class="d-flex justify-content-center align-items-center">
        <h3>NO RECORD FOUND</h3>
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
  name: 'Role',
  scrollToTop: false,
    inject: ['authStore'],

  data () {
    return {
      form: new this.$form({
        searchInput: ''
      }),
      currentPage: 1,
      totalPages: 1,
      currentPageData: [],
      roleId: 0,
      roleUpdate: false,
      roleAdd: false,
      roleDelete: false,
      role: false,
      loading: true,
      roleView: false,
        view: 5
    }
  },

  computed: {
    visiblePaginationLinks () {
      // Limit the number of visible pagination links to a reasonable number (e.g., 5)
      const maxVisibleLinks = 5
      const halfVisibleLinks = Math.floor(maxVisibleLinks / 2)
      let startPage = Math.max(1, this.currentPage - halfVisibleLinks)
      let endPage = Math.min(this.totalPages, startPage + maxVisibleLinks - 1)

      if (this.totalPages >= maxVisibleLinks && endPage === this.totalPages) {
        startPage = Math.max(1, endPage - maxVisibleLinks + 1)
      }

      const links = []
      for (let i = startPage; i <= endPage; i++) {
        links.push(i)
      }
      return links
    },

  },

  async mounted() {

    if (this.authStore.user === null) {
      this.loading = false;
    } else {
let ifPermissionViewExist = false;
        try {
      const permissionsToCheck = ['roleAdd', 'roleUpdate', 'roleView', 'roleDelete'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'roleView') {
              ifPermissionViewExist = true;
          }
        }
      });
    } catch (e) {
          handleError(e,this.$toast);
      }

        // Fetch the product list using the extracted method
        await this.fetchRoleList(ifPermissionViewExist);
    }
  },

  methods: {
    async changePage (page) {
        try {
            const response = await this.$axios.get(`/api/role?page=${page}&view=${this.view}&search=${this.form.searchInput}`)
            this.currentPage = response.data.data.current_page
            this.currentPageData = response.data.data.data
        } catch (e) {
            handleError(e,this.$toast);
        }
    },

      async fetchRoleList(ifPermissionViewExist) {

          if (ifPermissionViewExist){
              try {
                  await this.$axios
                      .get(`/api/role?view=${this.view}`)
                      .then(response => {
                          this.currentPageData = response.data.data.data
                          this.totalPages = response.data.data.last_page

                      })
              } catch (e) {
                  handleError(e,this.$toast);
              }
              this.loading = false;
          } else{
              this.loading = false;
          }
      },

    async deleteRole(id) {
      const permission = await this.showConfirmationDialog('Are you sure you want to delete this Role?')
      if (permission) {
        try {
          await this.$axios
            .delete(`/api/role/${id}`)
            .then(response => {
              if(response.data.status === 200){
                  this.$toast.success( response.data.message, { position: 'top-right', duration: 3000 })
                  // Fetch and update the role list after successful delete
                  this.fetchRoleList(true);
              }
            })
        } catch (e) {
            handleError(e,this.$toast);
        }
      }
    },

    async SearchRole () {
        try {
            await this.$axios
                .get('/api/role', {
                    params: {
                        search: this.form.searchInput,
                        view: this.view
                    }
                })
                .then(response => {
                    this.currentPageData = response.data.data.data
                    this.totalPages = response.data.data.last_page
                })
        } catch (e) {
            handleError(e,this.$toast);
        }
    }
  }
}
</script>

<style scoped></style>
