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

          <div class="columns">
            <div class="column is-3">
              <multiselect-poststate
                :label="$t('state')"
                :selected="params.state"
                @selected="state => params.state = state"
              >
              </multiselect-poststate>

              <div class="buttons">
                <a
                  @click="filtering || filter()"
                  :class="['button is-info', { 'is-loading': filtering }]"
                  :disabled="filtering"
                >
                  <span>{{ $t('filter') }}</span>
                </a>

                <a
                  @click="removingFilters || removeFilters()"
                  :class="['button is-white', { 'is-loading': removingFilters }]"
                  :disabled="removingFilters"
                >
                  <span>{{ $t('filter.remove') }}</span>
                </a>
              </div>
            </div>

            <div class="column">
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
                        :column="$i18n.locale === 'en' ? 'title_en' : 'title_lv'"
                        :sort="params.sortBy"
                        :direction="params.sortDirection"
                        :disabled="sorting"
                        @changed="sort"
                      >
                        {{ $t('posttitle') }}
                      </sortdirection>

                      <sortdirection
                        column="authors.name"
                        :sort="params.sortBy"
                        :direction="params.sortDirection"
                        :disabled="sorting"
                        @changed="sort"
                      >
                        {{ $t('author') }}
                      </sortdirection>

                      <sortdirection
                        column="dates"
                        :sort="params.sortBy"
                        :direction="params.sortDirection"
                        :disabled="sorting"
                        @changed="sort"
                      >
                        {{ $t('date') }}
                      </sortdirection>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="post in posts"
                      :key="post.id"
                      class="has-hoverable-actions"
                    >
                      <td>
                        <router-link
                          :to="{
                            name: 'admin-post-edit',
                            params: {
                              id: post.id,
                              post: post
                            }
                          }"
                        >
                          <b v-html="$options.filters.highlight($i18n.locale === 'en' ? post.title_en : post.title_lv, params.search)"></b>
                        </router-link>

                        <span v-if="!post.published_at">
                          - {{ $t('draft') }}
                        </span>

                        <div class="hoverable-actions">
                          <router-link
                            :to="{
                              name: 'admin-post-edit',
                              params: {
                                id: post.id,
                                post: post
                              }
                            }"
                          >
                            <small>{{ $t('edit') }}</small>
                          </router-link>

                          <span class="is-link-divider">|</span>

                          <router-link
                            :to="{
                              name: 'post',
                              params: {
                                id: post.id,
                                post: post
                              }
                            }"
                            target="_blank"
                          >
                            <small>{{ post.published_at ? $t('view') : $t('preview') }}</small>
                          </router-link>
                        </div>
                      </td>

                      <td>
                        <a>
                          {{ post.author.name }}
                        </a>
                      </td>

                      <td>
                        <div v-if="post.published_at">
                          {{ $t('published') }}
                          <br>
                          <a
                            :title="post.published_at"
                            class="hover"
                          >
                            {{ post.published_at | dateString }}
                          </a>
                        </div>
                        <div v-else>
                          {{ $t('saved') }}
                          <br>
                          <a
                            :title="post.updated_at"
                            class="hover"
                          >
                            {{ post.updated_at | dateString }}
                          </a>
                        </div>
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
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  import SortdirectionHandler from '../../../../mixins/sortdirection-handler'
  import MultiselectPoststate from '../../../multiselects/multiselect-poststate.vue'
  import ErrorHandler from '../../../../mixins/error-handler'
  import QueryHandler from '../../../../mixins/query-handler'
  import PageHandler from '../../../../mixins/page-handler'
  import Sortdirection from '../../../sortdirection.vue'
  import Pagination from '../../../pagination.vue'
  import Spinner from '../../../spinner.vue'
  import debounce from 'lodash/debounce'
  import axios from 'axios'

  export default {
    components: {
      MultiselectPoststate,
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
      posts: [],
      filtering: false,
      removingFilters: false
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
        if (this.$route.name === 'admin-posts') {
          this.get(this.$route.query)
        }
      }
    },
    methods: {
      get (query = null, page = 1) {
        this.disabled = true
        this.params.page = page

        axios
          .get('admin/post', { params: query || this.params })
          .then(response => {
            this.disabled = this.sorting = this.filtering = this.removingFilters = false
            this.posts = response.data.data
            this.handlePage(response.data.meta, response.data.links)
            this.handleQuery(response.data.params, 'admin-posts')
          })
          .catch(error => {
            this.sorting = this.pageChanging = this.filtering = this.removingFilters = false
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
      filter () {
        this.filtering = true
        this.get()
      },
      removeFilters () {
        this.removingFilters = true
        this.params.state = null
        this.get()
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
      "author": "Author",
      "date": "Date",
      "draft": "Draft",
      "edit": "Edit",
      "filter.remove": "Remove filters",
      "filter": "Filter",
      "posttitle": "Title",
      "preview": "Preview",
      "published": "Published",
      "saved": "Modified",
      "search": "Search",
      "state": "Status",
      "title": "Posts",
      "view": "View"
    },
    "lv": {
      "author": "Autors",
      "date": "Datums",
      "draft": "Melnraksts",
      "edit": "Labot",
      "filter.remove": "Noņemt filtrus",
      "filter": "Filtrēt",
      "posttitle": "Virsraksts",
      "preview": "Priekšskatīt",
      "published": "Publicēts",
      "saved": "Labots",
      "search": "Meklēt",
      "state": "Status",
      "title": "Ziņas",
      "view": "Skatīt"
    }
  }
</i18n>
