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
        <div class="card is-medium">
          <header class="card-header">
            <div class="card-header-post">
              <h1 class="subtitle is-2 has-text-grey-dark">
                {{ $i18n.locale === 'en' ? post.title_en : post.title_lv }}
              </h1>
            </div>
          </header>

          <div class="card-content">
            <div class="content">
              <div class="content-post">
                <div
                  v-html="$i18n.locale === 'en' ? post.content_en : post.content_lv"
                  ref="html"
                >
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
      </transition>
    </section>

    <transition
      v-if="!disabled"
      name="fade"
      appear
    >
      <section
        id="comments"
        class="section is-paddingless-top"
      >
        <div class="card is-medium has-margin-bottom">
          <header class="card-header">
            <div class="card-header-post">
              <h1 class="subtitle is-4 has-text-grey-dark">
                {{ $t('comments') }}

                <span v-if="commentsLoading">
                  (<spinner></spinner>)
                </span>
              </h1>
            </div>
          </header>

          <div class="card-content">
            <div class="content">
              <article
                v-if="comments.length"
                v-for="comment in comments"
                :key="comment.id"
                class="media has-hoverable-actions"
              >
                <figure class="media-left">
                  <div class="media-image-container">
                    <img
                      v-if="comment.author.image"
                      @click="() => {
                        selectedUser = comment.author
                        showPhotoswipe = true
                      }"
                      :src="comment.author.image && `${comment.author.images.small}`"
                    >
                    <i
                      v-else
                      class="fas fa-user has-text-grey-lighter fa-4x has-text-centered"
                    >
                    </i>
                  </div>
                </figure>
                <div class="media-content">
                  <div class="content">
                    <p>
                      <strong>{{ comment.author.name }}</strong>

                      <span
                        v-if="$root.user && ($root.user.id === comment.author.id || [1,3].includes($root.user.role.id))"
                        class="is-pulled-right hoverable-actions"
                      >
                        <a
                          @click="setComment(comment)"
                        >
                          <small>{{ $t('updateComment') }}</small>
                        </a>
                        <span class="is-link-divider">|</span>
                        <a
                          @click="destroyingComment || (showModalConfirm = comment.id)"
                        >
                          <small>{{ $t('destroyComment') }}</small>
                        </a>
                      </span>

                      <br>
                      {{ comment.comment }}

                      <br>
                      <small>{{ comment.updated_at }}</small>
                      <small v-if="comment.is_updated">
                        <i>{{ $t('comment.is_updated') }}</i>
                      </small>
                    </p>
                  </div>
                </div>
              </article>

              <article
                v-if="$root.user"
                class="media"
              >
                <figure class="media-left">
                  <div class="media-image-container">
                    <img
                      v-if="$root.user && $root.user.image"
                      @click="() => {
                        selectedUser = $root.user
                        showPhotoswipe = true
                      }"
                      :src="$root.user.image && `${$root.user.images.small}`"
                    >
                    <i
                      v-else
                      class="fas fa-user has-text-grey-lighter fa-4x has-text-centered"
                    >
                    </i>
                  </div>
                </figure>

                <div class="media-content">
                  <div class="field">
                    <p class="control">
                      <textarea
                        v-model="comment.comment"
                        ref="comment"
                        :class="['textarea', { 'is-danger': errors.comment }]"
                        :placeholder="$t('writeComment')"
                        :disabled="creatingComment"
                      >
                      </textarea>
                    </p>
                    <p v-if="errors.comment" class="help is-danger">{{ errors.comment.join() }}</p>
                  </div>
                  <div class="field">
                    <p class="control">
                      <a
                        @click="createComment"
                        :class="['button is-info', { 'is-loading': creatingComment }]"
                        :disabled="creatingComment"
                      >
                        {{ comment.id ? $t('updateComment') : $t('createComment') }}
                      </a>

                      <a
                        v-if="comment.id"
                        @click="cancelEditComment"
                        class="button"
                      >
                        {{ $t('cancelUpdate') }}
                      </a>
                    </p>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    </transition>

    <modal-confirm
      v-if="showModalConfirm"
      @confirmed="() => {
        destroyComment(showModalConfirm)
        showModalConfirm = false
      }"
      @close="showModalConfirm = false"
    >
      {{ $t('destroyComment.confirm') }}
    </modal-confirm>

    <photoswipe
      v-if="showPhotoswipe"
      :items="[{
        title: selectedUser.name,
        src: selectedUser.images.original,
        w: selectedUser.image.width,
        h: selectedUser.image.height
      }]"
      @close="showPhotoswipe = false"
    >
    </photoswipe>
  </div>
</template>

<script>
  import ModalConfirm from '../../modals/modal-confirm.vue'
  import ErrorHandler from '../../../mixins/error-handler'
  import Photoswipe from '../../photoswipe.vue'
  import Spinner from '../../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      ModalConfirm,
      Photoswipe,
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    data: function () {
      return {
        post: this.$route.params.post || {},
        comments: [],
        comment: {},
        commentComment: null,
        commentsLoading: false,
        creatingComment: false,
        destroyingComment: false,
        showModalConfirm: false,
        showPhotoswipe: false,
        selectedUser: {}
      }
    },
    created () {
      if (this.post.id === undefined && this.$route.params.id) {
        this.get()
      }
      this.getComments()
    },
    mounted () {
      if (this.$route.params.scrollToPagebreak) {
        this.scrollToPagebreak()
      }
      if (this.$route.params.scrollToComments) {
        this.scrollToComments()
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
      getComments () {
        this.commentsLoading = true

        axios
          .get(`post/${this.$route.params.id}/comment`)
          .then(response => {
            this.commentsLoading = false
            this.comments = response.data.data
          })
          .catch(error => {
            this.commentsLoading = false
            this.handleError(error)
          })
      },
      createComment () {
        this.creatingComment = true
        this.errors = {}

        let method = this.comment.id ? 'put' : 'post'
        let route = this.comment.id
          ? `post/${this.post.id}/comment/${this.comment.id}`
          : `post/${this.post.id}/comment`

        axios[method](route, {
          ...this.comment
        })
          .then(() => {
            this.creatingComment = false
            this.comment = {}
            this.getComments()
          })
          .catch(error => {
            this.creatingComment = false
            this.handleError(error)
          })
      },
      setComment (comment) {
        this.comment.comment = this.commentComment
        this.commentComment = comment.comment
        this.comment = comment
        this.$refs.comment.focus()
      },
      cancelEditComment () {
        this.comment.comment = this.commentComment
        this.comment = {}
        this.$refs.comment.focus()
      },
      destroyComment (commentId) {
        this.destroyingComment = true

        axios
          .delete(`post/${this.post.id}/comment/${commentId}`)
          .then(() => {
            this.destroyingComment = false
            this.getComments()
          })
          .catch(this.handleError)
      },
      scrollToPagebreak () {
        this.$nextTick(() => {
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
        })
      },
      scrollToComments () {
        this.$nextTick(() => {
          window.scroll({
            top: document.getElementById('comments').getBoundingClientRect().top + window.scrollY - 52,
            behavior: 'smooth'
          })
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
      "edit": "Edit",
      "comments": "Comments",
      "createComment": "Post",
      "writeComment": "Write comment here",
      "updateComment": "Edit",
      "destroyComment": "Delete",
      "destroyComment.confirm": "Delete comment?",
      "cancelUpdate": "Cancel",
      "comment.is_updated": "Modified"
    },
    "lv": {
      "edit": "Labot",
      "comments": "Komentāri",
      "createComment": "Publicēt",
      "writeComment": "Raksti komentāru šeit",
      "updateComment": "Labot",
      "destroyComment": "Dzēst",
      "destroyComment.confirm": "Dzēst komentāru?",
      "cancelUpdate": "Atcelt",
      "comment.is_updated": "Labots"
    }
  }
</i18n>
