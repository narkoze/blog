import LocaleHandler from './mixins/locale-handler'
import lang from './vue-i18n-locales.generated'
import Blog from './components/blog.vue'
import VueRouter from 'vue-router'
import VueI18n from 'vue-i18n'
import routes from './routes'
import axios from 'axios'
import Vue from 'vue'

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: document.querySelector('html').getAttribute('lang'),
  messages: lang
})

axios.defaults.baseURL = `/${i18n.locale}/api`

routes.forEach(route => {
  route.path = `/:locale/${route.path}`
})
routes.push({ path: '/', redirect: `/${i18n.locale}` })

Vue.use(VueRouter)
const router = new VueRouter({
  mode: 'history',
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !localStorage.getItem('token')) {
    next({
      name: 'home',
      params: {
        locale: i18n.locale
      }
    })
  }

  let lang = i18n.locale
  if (Object.keys(i18n.messages).includes(lang)) {
    i18n.locale = lang
  }

  next()
})

export default new Vue({
  el: '#blog',
  i18n,
  router,
  mixins: [
    LocaleHandler,
  ],
  components: {
    Blog
  },
  data: () => ({
    user: null,
    notifications: [],
    showModalSignin: false,
    showModalSignup: false,
    showModalEmailresend: false,
    showModalPasswordresetemail: false
  }),
  created () {
    let userStorage = localStorage.getItem('user')
    if (userStorage) {
      this.user = JSON.parse(userStorage)
      this.handleLocale(this.user.locale)
    }

    let tokenStorage = localStorage.getItem('token')
    if (tokenStorage) {
      let token = JSON.parse(tokenStorage)
      axios.defaults.headers.common.Authorization = `${token.token_type} ${token.access_token}`
    }
  },
  methods: {
    notify (status, message) {
      this.notifications.push({ id: Math.random(), status, message })
    }
  }
})
