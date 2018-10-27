import Blog from './components/blog.vue'

window.Vue = require('vue')

const blog = new window.Vue({
  el: '#blog',
  components: {
    Blog
  }
})

export default blog
