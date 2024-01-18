<template>
  <div v-if="loading === false">
    <div v-if="permissionsView === true">
      <h1>Permissions</h1>
      <div class="container my-4">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <form @submit.prevent="SearchPermission"  @keydown="form.onKeydown($event)">
              <div class="input-group mb-3">
                  <input v-model="form.searchInput" :class="{ 'is-invalid': form.errors.has('searchInput') }"
                         placeholder="Search records..." class="form-control" type="text" name="searchInput"
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
        <router-link v-if="permissionsAdd" :to="{ name: 'permission.add' }" class="btn linked-icon">
            <font-awesome-icon icon="plus" fixed-width />
        </router-link>
      </div>
      <div v-if="currentPageData && currentPageData.length > 0">
        <table class="table table-bordered ">
          <thead class="text-white grey-bg">
            <tr>
              <th class="">
                ID
              </th>
              <th class="col-1">
                Name
              </th>
              <th class="col-2">
                Description
              </th>
              <th class="col-2">
                Category
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
              <td>{{ item.category }}</td>
              <td class="text-center">
                <router-link :to="{ path: `/viewpermission/${item.id}` }" class="btn linked-icon">
                    <font-awesome-icon icon="eye" fixed-width />
                </router-link>
                <router-link v-if="permissionsUpdate" :to="{ path: `/updatepermission/${item.id}` }" class="btn linked-icon">
                    <font-awesome-icon icon="pen" fixed-width />
                </router-link>
                <button v-if="permissionsDelete" class="btn linked-icon" @click="deletePermission(item.id)">
                    <font-awesome-icon icon="trash" fixed-width />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="d-flex justify-content-center align-items-center">
        <h3>NO RECORD FOUND</h3>
      </div>

      <!-- Pagination links -->
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
  name: 'Permission',

  scrollToTop: false,
  data () {
    return {
        form: new this.$form({
            searchInput: ''
        }),
      permissions: [],
      currentPage: 1,
      totalPages: 1,
      currentPageData: [],
      roleId: 0,
      roleDetails: [],
      permissionsUpdate: false,
      permissionsDelete: false,
      permissionsAdd: false,
      permissionsView: false,
      loading: true,
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

  async mounted () {
      this.$useHead({
          title: 'Permission',
          description: 'Permission page'
      });
    if (this.authStore.user === null) {
      this.loading = false
    } else {
        let ifPermissionViewExist = false;

      try {
      const permissionsToCheck = ['permissionsView','permissionsAdd','permissionsDelete','permissionsUpdate'];
      const permissionResults = await checkPermissions(permissionsToCheck);
      permissionsToCheck.forEach(permission => {
        if (permissionResults[permission]) {
          this[permission] = true;
          if (permission === 'permissionsView') {
            ifPermissionViewExist = true;
          }
        }
      });
      } catch (e) {
          handleError(e,this.$toast);
      }

        // Fetch the product list using the extracted method
        await this.fetchPermissionList(ifPermissionViewExist);
    }
  },

  methods: {
    async changePage (page) {
      try {
        const response = await this.$axios.get(`/api/permission?page=${page}&view=${this.view}&search=${this.form.searchInput}`)
          if (response.status === 200){
              this.currentPageData = response.data.data.data
              this.currentPage = page
          }
      } catch (e) {
          handleError(e,this.$toast);
      }
    },

      async fetchPermissionList(ifPermissionViewExist) {

          if (ifPermissionViewExist){
              try {
                  await this.$axios
                      .get(`/api/permission?view=${this.view}`)
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

    async deletePermission (id) {
      const permission = await this.showConfirmationDialog('Are you sure you want to delete this Permission?')
      if (permission) {
        try {
          const response = await this.$axios.delete(`/api/permission/${id}`)

          if (response.data.status === 200) {
              this.$toast.success('Permission deleted successfully.', { position: 'top-right', duration: 3000 });
              // Fetch and update the role list after successful delete
              await this.fetchPermissionList(true);
          }
        } catch (e) {
            handleError(e,this.$toast);
        }
      }
    },
    SearchPermission () {
        try{
      this.$axios.get('/api/permission', {
          params: {
            search: this.form.searchInput,
              view: this.view
          }
        })
        .then((response) => {
            if (response.status === 200) {
                this.currentPageData = response.data.data.data
                this.totalPages = response.data.data.last_page
            }
        })
        } catch (e) {
            handleError(e,this.$toast);
        }
    }
  }
}
</script>
