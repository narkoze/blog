<template>
  <div>
    <section
      v-if="disabled"
      class="section has-text-centered"
    >
      <spinner
        class="is-large"
        color="white"
      >
      </spinner>
    </section>

    <transition-group
      v-else
      class="section"
      name="posts"
      tag="section"
      appear
    >
      <div
        v-for="post in posts"
        :key="post.id"
        :id="`id-${post.id}`"
        class="card is-medium has-margin-bottom"
      >
        <header class="card-header">
          <div class="card-header-post">
            <h1 class="subtitle is-2">
              <a
                @click="to({
                  name: 'post',
                  params: {
                    id: post.id,
                    post: post
                  }
                })"
                class="has-text-grey-dark"
              >
                {{ $i18n.locale === 'en' ? post.title_en : post.title_lv }}
              </a>
            </h1>
          </div>
        </header>

        <div class="card-content">
          <div class="content">
            <div class="content-post">
              <posts-content
                :template="getContent(post)"
                @to="to({
                  name: 'post',
                  params: {
                    id: post.id,
                    post: post
                  }
                })"
              >
              </posts-content>

              <div v-if="hasPagebreak($i18n.locale === 'en' ? post.content_en : post.content_lv)">
                <a @click="to({
                  name: 'post',
                  params: {
                    id: post.id,
                    post: post,
                    scrollToPagebreak: true
                  }
                })">
                  {{ $t('pagebrake') }}
                </a>
              </div>

              <div class="tags has-margin-top">
                <span
                  v-for="tag in post.tags"
                  :key="tag.id"
                  class="tag is-dark"
                >
                  {{ $i18n.locale === 'en' ? tag.name_en : tag.name_lv }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <footer class="card-footer">
          <div class="card-footer-post">
            <p>
              <i class="far fa-calendar-alt"></i>
              {{ post.published_at | dateString }}

              &nbsp;&nbsp;

              <i class="fas fa-user"></i>
              {{ post.author.name }}

              &nbsp;&nbsp;

              <span class="is-nowrap">
                <i class="fas fa-comments"></i>
                <a
                  v-if="post.comments.length"
                  @click="to({
                    name: 'post',
                    params: {
                      id: post.id,
                      post: post,
                      scrollToComments: true
                    }
                  })"
                >
                  {{ $t('comments', { count: post.comments.length }) }}
                </a>
                <span v-else>
                  {{ $t('comments', { count: 0 }) }}
                </span>
              </span>

              &nbsp;&nbsp;

              <router-link
                v-if="$root.user && $root.user.role.id !== 2"
                :to="{
                  name: 'admin-post-edit',
                  params: {
                    id: post.id,
                    post: post
                  }
                }"
              >
                <span class="is-nowrap">
                  <i class="fas fa-pen"></i>
                  {{ $t('edit') }}
                </span>
              </router-link>
            </p>
          </div>
        </footer>
      </div>

      <div
        class="card is-medium"
        key=""
      >
        <div class="card-content">
          <pagination
            :paginator="paginator"
            :changing="pageChanging"
            @changed="changePage"
          >
          </pagination>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
  import QueryHandler from '../../../mixins/query-handler'
  import ErrorHandler from '../../../mixins/error-handler'
  import PageHandler from '../../../mixins/page-handler'
  import PostsContent from './posts-content.vue'
  import Pagination from '../../pagination.vue'
  import Spinner from '../../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      PostsContent,
      Pagination,
      Spinner
    },
    mixins: [
      ErrorHandler,
      QueryHandler,
      PageHandler
    ],
    data: () => ({
      posts: [],
      params: {}
    }),
    beforeRouteEnter (to, from, next) {
      next(vm => {
        if (Object.keys(to.query).length) {
          vm.get(to.query)
          vm.$router.replace({
            query: to.query,
            hash: to.hash
          })
        } else {
          vm.get()
        }
      })
    },
    created () {
      window.onpopstate = () => {
        if (this.$route.name === 'posts') {
          this.get(this.$route.query)
        }
      }
    },
    methods: {
      get (query = null, page = 1) {
        this.disabled = true
        this.params.page = query ? query.page : page

        axios
          .get('post', { params: query || this.params })
          .then(response => {
            this.disabled = false

            this.posts = response.data.data
            this.handlePage(response.data.meta, response.data.links)
            this.handleQuery(response.data.params, 'posts')

            if (this.$route.hash) {
              setTimeout(() => {
                window.scroll({
                  top: document.getElementById(this.$route.hash.substr(1)).getBoundingClientRect().top + window.scrollY - 62,
                  behavior: 'smooth'
                })
              }, 100)
            }
          })
          .catch(error => {
            this.pageChanging = false
            this.handleError(error)
          })
      },
      to (route) {
        this.$router.replace({
          path: this.$route.path,
          query: this.$route.query,
          hash: `#id-${route.params.post.id}`
        })
        this.$router.push(route)
      },
      changePage (page) {
        this.pageChanging = true
        this.get(null, page)
        this.$router.replace({
          hash: null,
          query: this.$route.query
        })
      },
      hasPagebreak: (postContent) => postContent.includes('<!-- pagebreak -->'),
      pagebrake: (postContent) => postContent.split('<!-- pagebreak -->')[0],
      getContent (post) {
        let dom = new DOMParser()
        let document = dom.parseFromString(this.pagebrake(this.$i18n.locale === 'en' ? post.content_en : post.content_lv), 'text/html')
        let images = Array.from(document.getElementsByTagName('img'))
        images.forEach((image, i) => {
          image.setAttribute('v-on:click', '$parent.to()')
          image.classList.add('is-pointer')

          let imgContainer = document.createElement('span')
          imgContainer.classList.add('image-container-post')
          image.insertAdjacentElement('beforebegin', imgContainer)
          imgContainer.appendChild(image)
        })

        return document.body.innerHTML
      }
    },
    beforeDestroy () {
      window.onpopstate = null
    }
  }
</script>

<style>
  .posts-enter-active, .posts-leave-active {
    transition: opacity 1s;
  }
  .posts-enter, .posts-leave-to {
    opacity: 0;
  }
</style>

<i18n>
  {
    "en": {
      "edit": "Edit",
      "pagebrake": "Keep reading...",
      "comments": "{count} Comments"
    },
    "lv": {
      "edit": "Labot",
      "pagebrake": "Turpini lasīt...",
      "comments": "{count} Komentāri"
    }
  }
</i18n>
