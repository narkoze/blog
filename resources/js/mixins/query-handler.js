export default {
  data: () => ({
    replaceRoute: true
  }),
  methods: {
    handleQuery (params) {
      let paramsWithValues = {}
      Object.keys(params).forEach(key => {
        if (params[key] !== null) {
          paramsWithValues[key] = params[key]
        }
      })

      this.params = Object.assign({}, params, paramsWithValues)

      if (this.replaceRoute) {
        this.replaceRoute = false

        this.$router.replace({
          query: paramsWithValues,
          hash: this.$route.hash
        })
      } else {
        this.$router.push({
          query: paramsWithValues,
          hash: this.$route.hash
        })
      }
    }
  }
}
