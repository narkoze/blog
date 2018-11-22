export default {
  data: () => ({
    errors: {},
    disabled: false
  }),
  methods: {
    handleError (error) {
      this.disabled = false

      switch (error.response.status) {
        case 401:
          this.errors = error.response.data

          localStorage.removeItem('token')

          this.$root.user = null
          localStorage.removeItem('user')

          this.$router.push({ name: 'home' })
          break
        case 422:
          this.errors = error.response.data.errors
          break
        default:
          this.$root.notify('error', `${error.response.status}: ${error.response.statusText}, ${error.response.data.message}`)
      }
    }
  }
}
