import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import Blog from './components/blog.vue'

Vue.use(VueRouter)
const router = new VueRouter({
  routes
})

const blog = new Vue({
  el: '#blog',
  router,
  components: {
    Blog
  }
})

export default blog
