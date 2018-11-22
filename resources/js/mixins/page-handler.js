export default {
  data: () => ({
    paginator: null,
    pageChanging: false
  }),
  methods: {
    handlePage (meta, links) {
      this.paginator = { meta, links }
      this.pageChanging = false
    }
  }
}
