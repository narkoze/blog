export default {
  data: () => ({
    params: {
      page: 1
    },
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
