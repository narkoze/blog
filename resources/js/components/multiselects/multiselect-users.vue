<template>
  <div class="field">
    <label
      @click="$refs.multiselect.$el.focus()"
      class="label is-for-multiselect"
    >
      {{ label }}
    </label>
    <multiselect
      ref="multiselect"
      class="is-multiselect is-text"
      track-by="id"
      placeholder=""
      :options="users"
      :value="selected"
      :custom-label="customLabel"
      :local-search="false"
      :show-labels="false"
      :show-no-options="false"
      @search-change="search"
      @open="search"
      @input="user => $emit('selected', user || {})"
    >
      <span slot="noResult">
        {{ $t('noResult') }}
      </span>

      <span
        slot="caret"
        class="arrow"
      >
        <spinner
          v-if="disabled"
          class="is-small"
          color="grey"
        >
        </spinner>
        <i
          v-else
          class="fas fa-angle-down fa-lg"
        >
        </i>
      </span>
    </multiselect>
  </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
  import ErrorHandler from '../../mixins/error-handler'
  import Multiselect from 'vue-multiselect'
  import debounce from 'lodash/debounce'
  import Spinner from '../spinner.vue'
  import axios from 'axios'

  export default {
    mixins: [
      ErrorHandler,
    ],
    components: {
      Multiselect,
      Spinner
    },
    props: [
      'selected',
      'label',
    ],
    data: () => ({
      users: []
    }),
    methods: {
      search: debounce(function (search) {
        this.disabled = true

        let params = {
          limit: 10
        }
        if (search) params.search = search

        axios
          .get('admin/user', { params })
          .then(response => {
            this.disabled = false
            this.users = response.data.data
          })
          .catch(this.handleError)
      }, 500),
      customLabel (user) {
        if (user.hasOwnProperty('name')) {
          return `${user.name} (${user.email})`
        }
        return ''
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "noResult": "Nothing was found"
    },
    "lv": {
      "noResult": "Nekas netika atrasts"
    }
  }
</i18n>
