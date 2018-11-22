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

    <section
      v-else
      class="section"
    >
      <transition
        name="fade"
        appear
      >
        <div class="card is-medium has-margin-bottom">
          <header class="card-header">
            <div class="card-header-post">
              <h1 class="subtitle is-2 has-text-grey-dark">
                {{ $i18n.locale === 'en' ? post.title_en : post.title_lv }}
              </h1>
            </div>
          </header>

          <div class="card-content">
            <div class="content">
              <div
                v-html="$i18n.locale === 'en' ? post.content_en : post.content_lv"
                ref="html"
              >
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

                <router-link
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
      </transition>
    </section>
  </div>
</template>

<script>
  import ErrorHandler from '../../../mixins/error-handler'
  import Spinner from '../../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        post: this.$route.params.post || {}
      }
    },
    created () {
      if (this.post.id === undefined && this.$route.params.id) {
        this.get()
      }
    },
    mounted () {
      if (this.$route.params.scrollToPagebreak) {
        this.scrollToPagebreak()
      }
    },
    methods: {
      get () {
        this.disabled = true

        axios
          .get(`post/${this.$route.params.id}`)
          .then(response => {
            this.disabled = false
            this.post = response.data.data
          })
          .catch(this.handleError)
      },
      scrollToPagebreak () {
        this.$nextTick(() => {
          setTimeout(() => {
            for (let node of this.$refs.html.childNodes) {
              if (node.nodeType === 8 && node.nodeValue === ' pagebreak ') {
                window.scroll({
                  top: node.previousElementSibling.getBoundingClientRect().top + window.scrollY - 52,
                  behavior: 'smooth'
                })

                let i = document.createElement('i')
                i.setAttribute('class', 'fas fa-hand-point-right fa-lg has-text-danger blink')
                node.parentNode.insertBefore(i, node)
                setTimeout(() => {
                  node.parentNode.removeChild(i)
                }, 1500)
                break
              }
            }
          }, 100)
        })
      }
    }
  }
</script>

<style>
  .blink {
    position: absolute;
    margin-left: -20px;
    margin-top: -10px;

      -webkit-animation: blink .3s step-end infinite;
         -moz-animation: blink .3s step-end infinite;
          -ms-animation: blink .3s step-end infinite;
           -o-animation: blink .3s step-end infinite;
              animation: blink .3s step-end infinite;
  }
@-webkit-keyframes blink { 50% { visibility: hidden; }}
        @keyframes blink { 50% { visibility: hidden; }}
</style>

<i18n>
  {
    "en": {
      "edit": "Edit"
    },
    "lv": {
      "edit": "Labot"
    }
  }
</i18n>
