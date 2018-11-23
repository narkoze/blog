import LocaleHandler from './mixins/locale-handler'
import lang from './vue-i18n-locales.generated'
import Blog from './components/blog.vue'
import VueRouter from 'vue-router'
import VueI18n from 'vue-i18n'
import routes from './routes'
import axios from 'axios'
import Vue from 'vue'
import moment from 'moment'

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: document.querySelector('html').getAttribute('lang'),
  messages: lang
})

axios.defaults.baseURL = `/${i18n.locale}/api`

routes.forEach(route => {
  route.path = `/:locale/${route.path}`

  if (route.hasOwnProperty('children')) {
    route.children.forEach(children => {
      children.path = `${route.path}/${children.path}`
    })
  }
})
routes.push({ path: '/', redirect: `/${i18n.locale}` })

Vue.use(VueRouter)
const router = new VueRouter({
  mode: 'history',
  routes,
  scrollBehavior: () => ({ x: 0, y: 0 })
})

router.beforeEach((to, from, next) => {
  let lang = i18n.locale
  if (Object.keys(i18n.messages).includes(lang)) {
    i18n.locale = lang
  }

  next()
})

Vue.prototype.$events = new Vue()

Vue.filter('dateString', value => {
  let date = moment(value)

  if (!date.isValid()) return ''

  return date.format('YYYY-MM-DD')
})
Vue.filter('highlight', (text, search) => {
  if (!text || !search) return text

  search = search.trim()
  return text.replace(new RegExp(search, 'ig'), search => `<searchlight class="searchlight">${search}</searchlight>`)
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
  watch: {
    '$route.path' (path) {
      document.title = `${i18n.locale === 'en' ? 'Blog' : 'Emuārs'}${path}`
    }
  },
  methods: {
    notify (status, message) {
      this.notifications.push({ id: Math.random(), status, message })
    }
  }
})
