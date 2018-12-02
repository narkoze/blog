<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <div class="card">
        <div class="card-content">
          <h1 class="title">
            {{ $t('title') }}
            <spinner v-if="disabled"></spinner>
          </h1>

          <div class="field">
            <label class="label">{{ $t('search') }}
              <p class="control has-icons-right">
                <input
                  :value="params.search"
                  @input="search"
                  type="text"
                  class="input"
                >
                <span
                  v-if="params.search"
                  @click="() => {
                    params.search = ''
                    get()
                  }"
                  class="icon is-right is-clickable"
                >
                  <i class="fas fa-times"></i>
                </span>
              </p>
            </label>
          </div>

          <div class="field is-scrollable">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
              <thead>
                <tr>
                  <sortdirection
                    column="name"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                  >
                    {{ $t('name') }}
                  </sortdirection>

                  <sortdirection
                    column="email"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                  >
                    {{ $t('email') }}
                  </sortdirection>

                  <sortdirection
                    :column="`roles.${$i18n.locale === 'en' ? 'name_en' : 'name_lv'}`"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                  >
                    {{ $t('role') }}
                  </sortdirection>

                  <sortdirection
                    column="posts_count"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                    class="has-text-right"
                  >
                    {{ $t('posts_count') }}
                  </sortdirection>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="user in users"
                  :key="user.id"
                >
                  <td v-html="$options.filters.highlight(user.name, params.search)"></td>

                  <td
                    v-if="[1, 3].includes($root.user.role.id)"
                    v-html="$options.filters.highlight(user.email, params.search)"
                  >
                  </td>
                  <td v-else>{{ user.email }}</td>

                  <td>
                    <spinner
                      v-if="rolesLoading"
                      class="is-small"
                    >
                    </spinner>

                    <div
                      v-else
                      class="select is-small"
                    >
                      <select
                        v-model="user.role.id"
                        :disabled="user.role_changing"
                      >
                        <option
                          v-for="role in roles"
                          :key="role.id"
                          :value="role.id"
                        >
                          {{ $i18n.locale === 'en' ? role.name_en : role.name_lv }}
                        </option>
                      </select>
                    </div>
                    <a
                      v-if="user.role_id !== user.role.id"
                      @click="[1, 3].includes($root.user.role.id) && updateUser(user)"
                      :class="['button is-info is-small', { 'is-loading': user.role_changing }]"
                      :disabled="![1, 3].includes($root.user.role.id)"
                    >
                      {{ $t('save') }}
                    </a>
                  </td>

                  <td class="has-text-right">
                    {{ user.posts_count || '' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <pagination
            :paginator="paginator"
            :changing="pageChanging"
            @changed="page => {
              pageChanging = true
              get(null, page)
            }"
          >
          </pagination>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  import SortdirectionHandler from '../../../mixins/sortdirection-handler'
  import ErrorHandler from '../../../mixins/error-handler'
  import QueryHandler from '../../../mixins/query-handler'
  import PageHandler from '../../../mixins/page-handler'
  import Sortdirection from '../../sortdirection.vue'
  import Pagination from '../..//pagination.vue'
  import Spinner from '../../spinner.vue'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    components: {
      Sortdirection,
      Pagination,
      Spinner
    },
    mixins: [
      SortdirectionHandler,
      ErrorHandler,
      QueryHandler,
      PageHandler,
    ],
    data: () => ({
      users: [],
      roles: [],
      rolesLoading: false
    }),
    beforeRouteEnter (to, from, next) {
      next(vm => {
        if (Object.keys(to.query).length) {
          vm.params = to.query
          vm.$router.replace({ query: to.query })
          vm.get(to.query)
        } else {
          vm.get()
        }
        vm.getRoles()
      })
    },
    created () {
      this.$events.$on('locale-changed', () => {
        this.get()
        this.getRoles()
      })
    },
    mounted () {
      window.onpopstate = () => {
        if (this.$route.name === 'admin-users') {
          this.get(this.$route.query)
          this.getRoles()
        }
      }
    },
    methods: {
      get (query = null, page = 1) {
        this.disabled = true
        this.params.page = query ? query.page : page

        axios
          .get('admin/user', { params: query || this.params })
          .then(response => {
            this.disabled = this.sorting = false
            this.users = response.data.data
            this.users.map(user => {
              user.role_id = user.role.id
              this.$set(user, 'role_changing', false)
            })
            this.handlePage(response.data.meta, response.data.links)
            this.handleQuery(response.data.params, 'admin-users')
          })
          .catch(error => {
            this.sorting = this.pageChanging = false
            this.handleError(error)
          })
      },
      sort (by) {
        this.sorting = true
        this.handleSortdirection(by)
        this.get()
      },
      search: debounce(function (e) {
        this.params.search = e.target.value
        this.get()
      }, 500),
      getRoles () {
        this.rolesLoading = true

        axios
          .get('admin/role')
          .then(response => {
            this.rolesLoading = false
            this.roles = response.data.data
          })
          .catch(error => {
            this.rolesLoading = false
            this.handleError(error)
          })
      },
      updateUser (user) {
        user.role_changing = true

        axios
          .put(`admin/user/${user.id}`, {
            role_id: user.role.id
          })
          .then(response => {
            user.role_changing = false

            let responseUser = response.data.data
            responseUser.role_id = responseUser.role.id
            Object.assign(user, responseUser)

            this.$root.notify('success', this.$t('updateUser.success'))
          })
          .catch(error => {
            user.role_changing = false
            this.handleError(error)
          })
      }
    },
    beforeDestroy () {
      window.onpopstate = null
      this.$events.$off('locale-changed')
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "Users",
      "search": "Search",
      "name": "Name",
      "email": "E-mail",
      "posts_count": "Post count",
      "save": "Save",
      "role": "Role",
      "updateUser.success": "User successfully updated"
    },
    "lv": {
      "title": "Lietotāji",
      "search": "Meklēt",
      "name": "Vārds",
      "email": "E-pasts",
      "posts_count": "Ziņu skaits",
      "save": "Saglabāt",
      "role": "Loma",
      "updateUser.success": "Lietotājs veiksmīgi atjaunināts"
    }
  }
</i18n>
