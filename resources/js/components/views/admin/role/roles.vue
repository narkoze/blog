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
            <a
              @click="() => {
                [1, 3].includes($root.user.role.id) &&
                $router.push({
                  name: 'admin-role',
                  role: {
                    id: null
                  }
                })
              }"
              class="button is-primary"
              :disabled="![1, 3].includes($root.user.role.id)"
            >
              <span>
                <i class="fas fa-plus"></i>
                {{ $t('newrole') }}
              </span>
            </a>
          </div>

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
                    :column="$i18n.locale === 'en' ? 'name_en' : 'name_lv'"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                  >
                    {{ $t('name') }}
                  </sortdirection>

                  <sortdirection
                    :column="$i18n.locale === 'en' ? 'description_en' : 'description_lv'"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                  >
                    {{ $t('description') }}
                  </sortdirection>

                  <sortdirection
                    column="users_count"
                    :sort="params.sortBy"
                    :direction="params.sortDirection"
                    :disabled="sorting"
                    @changed="sort"
                    class="has-text-right"
                  >
                    {{ $t('users_count') }}
                  </sortdirection>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="role in roles"
                  :key="role.id"
                  class="has-hoverable-actions"
                >
                  <td>
                    <a
                      @click="() => {
                        [1, 3].includes($root.user.role.id) &&
                        $router.push({
                          name: 'admin-role-edit',
                          params: {
                            id: role.id,
                            role: role
                          }
                        })
                      }"
                    >
                      <b v-html="$options.filters.highlight($i18n.locale === 'en' ? role.name_en : role.name_lv, params.search)"></b>
                    </a>

                    <div class="hoverable-actions">
                      <a
                        @click="() => {
                          [1, 3].includes($root.user.role.id) &&
                          $router.push({
                            name: 'admin-role-edit',
                            params: {
                              id: role.id,
                              role: role
                            }
                          })
                        }"
                      >
                        <small>{{ $t('edit') }}</small>
                      </a>
                    </div>
                  </td>

                  <td v-html="$options.filters.highlight($i18n.locale === 'en' ? role.description_en : role.description_lv, params.search)"></td>

                  <td class="has-text-right">
                    {{ role.users_count || '' }}
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
  import SortdirectionHandler from '../../../../mixins/sortdirection-handler'
  import ErrorHandler from '../../../../mixins/error-handler'
  import QueryHandler from '../../../../mixins/query-handler'
  import PageHandler from '../../../../mixins/page-handler'
  import Sortdirection from '../../../sortdirection.vue'
  import Pagination from '../../../pagination.vue'
  import Spinner from '../../../spinner.vue'
  import debounce from 'lodash/debounce'

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
      roles: []
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
      })
    },
    created () {
      this.$events.$on('locale-changed', () => {
        this.get()
      })
    },
    mounted () {
      window.onpopstate = () => {
        if (this.$route.name === 'admin-roles') {
          this.get(this.$route.query)
        }
      }
    },
    methods: {
      get (query = null, page = 1) {
        this.disabled = true
        this.params.page = query ? query.page : page

        this.$axios
          .get('admin/role', { params: query || this.params })
          .then(response => {
            this.disabled = this.sorting = false
            this.roles = response.data.data
            this.handlePage(response.data.meta, response.data.links)
            this.handleQuery(response.data.params, 'admin-roles')
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
      }, 500)
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
      "title": "Roles",
      "search": "Search",
      "name": "Role",
      "description": "Description",
      "users_count": "User count",
      "edit": "Edit",
      "newrole": "Create role"
    },
    "lv": {
      "title": "Lomas",
      "search": "Meklēt",
      "name": "Loma",
      "description": "Apraksts",
      "users_count": "Lietotāju skaits",
      "edit": "Labot",
      "newrole": "Izveidot lomu"
    }
  }
</i18n>
