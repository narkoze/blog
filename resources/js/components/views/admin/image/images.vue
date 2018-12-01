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
            <router-link
              :to="{ name: 'admin-image-upload' }"
              class="button is-primary"
            >
              <span>
                <i class="fas fa-plus"></i>
                {{ $t('image.upload') }}
              </span>
            </router-link>
          </div>

          <div class="columns">
            <div class="column is-3"></div>

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
                        column="name"
                        :sort="params.sortBy"
                        :direction="params.sortDirection"
                        :disabled="sorting"
                        @changed="sort"
                      >
                        {{ $t('name') }}
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
                        column="updated_at"
                        :sort="params.sortBy"
                        :direction="params.sortDirection"
                        :disabled="sorting"
                        @changed="sort"
                      >
                        {{ $t('updated_at') }}
                      </sortdirection>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="image in images"
                      :key="image.id"
                      class="has-hoverable-actions"
                    >
                      <td>
                        <router-link
                          :to="{
                            name: 'admin-image-edit',
                            params: {
                              id: image.id,
                              image: image
                            }
                          }"
                        >
                          <img
                            :src="image.images.small"
                            class="image is-64x64 is-pulled-left has-margin-half"
                            draggable="false"
                          >
                          <b v-html="$options.filters.highlight(image.name, params.search)"></b>
                        </router-link>

                        <div class="hoverable-actions">
                          <router-link
                            :to="{
                              name: 'admin-image-edit',
                              params: {
                                id: image.id,
                                image: image
                              }
                            }"
                          >
                            <small>{{ $t('edit') }}</small>
                          </router-link>
                        </div>
                      </td>

                      <td>
                        <a>
                          {{ image.author.name }}
                        </a>
                      </td>

                      <td>
                        <a>
                          {{ image.updated_at | dateString }}
                        </a>
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
      images: []
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
        if (this.$route.name === 'admin-images') {
          this.get(this.$route.query)
        }
      }
    },
    methods: {
      get (query = null, page = 1) {
        this.disabled = true
        this.params.page = page

        axios
          .get('admin/image', { params: query || this.params })
          .then(response => {
            this.disabled = this.sorting = false
            this.images = response.data.data
            this.handlePage(response.data.meta, response.data.links)
            this.handleQuery(response.data.params, 'admin-images')
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
      "title": "Images",
      "image.upload": "Upload images",
      "search": "Search",
      "name": "Image",
      "author": "Author",
      "updated_at": "Updated",
      "edit": "Edit"
    },
    "lv": {
      "title": "Attēli",
      "image.upload": "Augšupielādēt attēlus",
      "search": "Meklēt",
      "name": "Attēls",
      "author": "Autors",
      "updated_at": "Atjaunots",
      "edit": "Labot"
    }
  }
</i18n>