export default {
  data: () => ({
    params: {
      sortBy: null,
      sortDirection: null
    },
    sorting: false
  }),
  methods: {
    handleSortdirection (sort) {
      if (this.params.sortBy !== sort) {
        this.params.sortBy = sort
        this.params.sortDirection = 'asc'
      } else {
        this.params.sortDirection = this.params.sortDirection === 'asc' ? 'desc' : 'asc'
      }
    }
  }
}
