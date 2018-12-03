<template>
  <div>
    <transition
      name="fade"
      appear
    >
      <div class="card">
        <div class="card-content">
          <h1 class="title">
            {{ $t('title') }}
            <spinner v-if="disabled"></spinner>
          </h1>

          <div class="columns">
            <div class="column">
              <div class="card dashboard">
                <header class="card-header">
                  <p class="card-header-title">
                    {{ $t('users') }}
                  </p>
                </header>
                <div class="card-content has-text-centered">
                  <spinner
                    v-if="gettingUserChart"
                    class="is-large"
                  >
                  </spinner>
                  <h1
                    v-else
                    class="title is-1 has-text-weight-bold"
                  >
                    <a @click="getUserChart(true)">
                      {{ counts.users_count }}
                    </a>
                  </h1>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="card dashboard">
                <header class="card-header">
                  <p class="card-header-title">
                    {{ $t('posts') }}
                  </p>
                </header>
                <div class="card-content has-text-centered">
                  <spinner
                    v-if="gettingPostChart"
                    class="is-large"
                  >
                  </spinner>
                  <h1
                    v-else
                    class="title is-1 has-text-weight-bold"
                  >
                    <a @click="getPostChart(true)">
                      {{ counts.posts_count }}/{{ counts.drafts_count }}
                    </a>
                  </h1>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="card dashboard">
                <header class="card-header">
                  <p class="card-header-title">
                    {{ $t('comments') }}
                  </p>
                </header>
                <div class="card-content has-text-centered">
                  <spinner
                    v-if="gettingCommentChart"
                    class="is-large"
                  >
                  </spinner>
                  <h1
                    v-else
                    class="title is-1 has-text-weight-bold"
                  >
                    <a @click="getCommentChart(true)">
                      {{ counts.comments_count }}
                    </a>
                  </h1>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="card dashboard">
                <header class="card-header">
                  <p class="card-header-title">
                    {{ $t('tags') }}
                  </p>
                </header>
                <div class="card-content has-text-centered">
                  <spinner
                    v-if="gettingTagChart"
                    class="is-large"
                  >
                  </spinner>
                  <h1
                    v-else
                    class="title is-1 has-text-weight-bold"
                  >
                    <a @click="getTagChart(true)">
                      {{ counts.tags_count }}
                    </a>
                  </h1>
                </div>
              </div>
            </div>
          </div>

          <div
            id="chart"
            class="chart"
          >
            <div
              v-if="chart.loading"
              class=" has-text-centered"
            >
              <spinner class="is-large"></spinner>
            </div>

            <canvas
              v-show="!chart.loading"
              ref="chart"
            >
            </canvas>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  import ErrorHandler from '../../../mixins/error-handler'
  import QueryHandler from '../../../mixins/query-handler'
  import Spinner from '../../spinner.vue'
  import Chart from 'chart.js'
  import moment from 'moment'
  import axios from 'axios'

  export default {
    components: {
      Spinner
    },
    mixins: [
      ErrorHandler,
      QueryHandler,
    ],
    data: () => ({
      counts: [],
      chart: {
        name: null,
        ctx: null,
        loading: false
      },
      gettingUserChart: false,
      gettingPostChart: false,
      gettingCommentChart: false,
      gettingTagChart: false
    }),
    created () {
      this.getCounts()
    },
    mounted () {
      this.getPostChart()

      this.$events.$on('locale-changed', () => {
        switch (this.chart.name) {
          case 'users': this.getUserChart(); break
          case 'posts': this.getPostChart(); break
          case 'comments': this.getCommentChart(); break
          case 'tags': this.getTagChart(); break
        }
      })
    },
    methods: {
      getCounts () {
        this.disabled = true

        axios
          .get('admin/dashboard/counts')
          .then(response => {
            this.disabled = false
            this.counts = response.data
          })
          .catch(this.handleError)
      },
      getUserChart (scroll = false) {
        this.disabled = this.gettingUserChart = true
        this.createChart('users', 'bar')

        scroll && this.scrollToChart()

        axios
          .get('admin/dashboard/users')
          .then(response => {
            if (this.$route.name !== 'admin-dashboard') return
            this.disabled = this.chart.loading = this.gettingUserChart = false

            let currentMonth = moment().month() + 1
            let currentYear = moment().year()
            response.data.months.forEach(month => {
              this.chart.ctx.data.labels.push([
                month <= currentMonth ? currentYear : currentYear - 1,
                this.$t(month)
              ])
            })

            this.chart.ctx.data.datasets.push({
              data: response.data.counts,
              backgroundColor: 'hsl(171, 100%, 41%)'
            })

            this.chart.ctx.options.legend.display = false
            this.chart.ctx.options.scales = {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }]
            }

            this.chart.ctx.update()
          })
          .catch(error => {
            this.chart.loading = this.gettingUserChart = false
            this.handleError(error)
          })
      },
      getPostChart (scroll = false) {
        this.disabled = this.gettingPostChart = true
        this.createChart('posts', 'line')

        scroll && this.scrollToChart()

        axios
          .get('admin/dashboard/posts')
          .then(response => {
            if (this.$route.name !== 'admin-dashboard') return
            this.disabled = this.chart.loading = this.gettingPostChart = false

            let currentMonth = moment().month() + 1
            let currentYear = moment().year()
            response.data.months.forEach(month => {
              this.chart.ctx.data.labels.push([
                month <= currentMonth ? currentYear : currentYear - 1,
                this.$t(month)
              ])
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t('postlabel'),
              data: response.data.posts,
              backgroundColor: 'hsl(171, 100%, 41%)',
              borderColor: 'hsl(171, 100%, 41%)',
              fill: false
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t('draftlabel'),
              data: response.data.drafts,
              backgroundColor: 'hsl(0, 0%, 86%)',
              borderColor: 'hsl(0, 0%, 86%)',
              fill: false
            })
            this.chart.ctx.options.scales = {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }]
            }
            this.chart.ctx.options.tooltips = {
              mode: 'nearest',
              intersect: false
            }
            this.chart.ctx.update()
          })
          .catch(error => {
            this.chart.loading = this.gettingPostChart = false
            this.handleError(error)
          })
      },
      getCommentChart (scroll = false) {
        this.disabled = this.gettingCommentChart = true
        this.createChart('comments', 'pie')

        scroll && this.scrollToChart()

        axios
          .get('admin/dashboard/comments')
          .then(response => {
            if (this.$route.name !== 'admin-dashboard') return
            this.disabled = this.chart.loading = this.gettingCommentChart = false

            this.chart.ctx.data.labels = response.data.posts
            this.chart.ctx.data.datasets.push({
              data: response.data.counts,
              backgroundColor: [
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
                'hsl(141, 71%, 48%)',
                'hsl(171, 100%, 41%)',
                'hsl(204, 86%, 53%)',
                'hsl(217, 71%, 53%)',
                'hsl(271, 100%, 71%)',
                'hsl(348, 100%, 61%)',
                'hsl(14, 100%, 53%)',
                'hsl(48, 100%, 67%)',
              ]
            })
            this.chart.ctx.update()
          })
          .catch(error => {
            this.chart.loading = this.gettingCommentChart = false
            this.handleError(error)
          })
      },
      getTagChart (scroll = false) {
        this.disabled = this.gettingTagChart = true
        this.createChart('tags', 'horizontalBar')

        scroll && this.scrollToChart()

        axios
          .get('admin/dashboard/tags')
          .then(response => {
            if (this.$route.name !== 'admin-dashboard') return
            this.disabled = this.chart.loading = this.gettingTagChart = false

            this.chart.ctx.data.labels = response.data.tags

            let posts = []
            let drafts = []
            response.data.counts.forEach(counts => {
              posts.push(counts[0])
              drafts.push(counts[1])
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t('postlabel'),
              data: posts,
              backgroundColor: 'hsl(171, 100%, 41%)',
              borderColor: 'hsl(171, 100%, 41%)'
            })
            this.chart.ctx.data.datasets.push({
              label: this.$t('draftlabel'),
              data: drafts,
              backgroundColor: 'hsl(0, 0%, 86%)',
              borderColor: 'hsl(0, 0%, 86%)'
            })

            this.chart.ctx.options.tooltips = {
              mode: 'index',
              intersect: false
            }

            this.chart.ctx.options.scales = {
              xAxes: [{
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  stepSize: 1
                }
              }],
              yAxes: [{
                stacked: true
              }]
            }

            this.chart.ctx.update()
          })
          .catch(error => {
            this.chart.loading = this.gettingTagChart = false
            this.handleError(error)
          })
      },
      createChart (name, type) {
        this.chart.loading = true
        this.chart.name = name

        if (this.chart.ctx) this.chart.ctx.destroy()

        this.$nextTick(() => {
          this.chart.ctx = new Chart(this.$refs.chart.getContext('2d'), {
            type: type,
            options: {
              title: {
                display: true,
                text: [
                  this.$t(name),
                  this.$t(`${name}.description`),
                ]
              },
              responsive: true,
              maintainAspectRatio: false
            }
          })
        })
      },
      scrollToChart () {
        this.$nextTick(() => {
          window.scroll({
            top: document.getElementById('chart').getBoundingClientRect().top + window.scrollY - 52,
            behavior: 'smooth'
          })
        })
      }
    },
    beforeDestroy () {
      this.$events.$off('locale-changed')
      this.chart.ctx.destroy()
    }
  }
</script>

<style lang="scss">
  .chart {
    position: relative;
    width: 100%;
    height: 60vh;
  }
</style>

<i18n>
  {
    "en": {
      "title": "Dashboard",
      "users": "Users",
      "users.description": "How many users are registered in each month",
      "posts": "Posts / Drafts",
      "posts.description": "How many posts and drafts in each month",
      "comments.description": "Top 10 posts with the most comments",
      "comments": "Comments",
      "tags": "Tags",
      "tags.description": "Top 10 tags with the most posts",
      "1": "January",
      "2": "February",
      "3": "March",
      "4": "April",
      "5": "May",
      "6": "June",
      "7": "July",
      "8": "August",
      "9": "September",
      "10": "October",
      "11": "November",
      "12": "December",
      "postlabel": "Posts",
      "draftlabel": "Drafts"
    },
    "lv": {
      "title": "Informācija",
      "users": "Lietotāji",
      "users.description": "Cik lietotāji reģistrēti katrā mēnesī",
      "posts": "Ziņas / Melnraksti",
      "posts.description": "Cik ziņas un melnrakstu katrā mēnesī",
      "comments": "Komentāri",
      "comments.description": "Top 10 ziņas ar visvairāk komentāriem",
      "tags": "Tēmturi",
      "tags.description": "Top 10 tēmturi ar visvairāk ziņām",
      "1": "Janvāris",
      "2": "Februāris",
      "3": "Marts",
      "4": "Aprīlis",
      "5": "Maijs",
      "6": "Jūnijs",
      "7": "Jūlijs",
      "8": "Augusts",
      "9": "Septembris",
      "10": "Oktobris",
      "11": "Novembris",
      "12": "Decembris",
      "postlabel": "Ziņas",
      "draftlabel": "Melnraksti"
    }
  }
</i18n>
