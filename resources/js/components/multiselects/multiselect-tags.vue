<template>
  <div class="field">
    <label
      @click="$refs.multiselect.$el.focus()"
      class="label is-for-multiselect"
    >
      {{ $t('label') }}
    </label>
    <multiselect
      ref="multiselect"
      :options="options"
      :value="selected"
      :custom-label="customLabel"
      :show-labels="false"
      :show-no-options="false"
      :taggable="true"
      :multiple="true"
      :tag-placeholder="$t('newtag')"
      :block-keys="['Enter']"
      track-by="id"
      class="is-multiselect is-text"
      placeholder=""
      @open="search"
      @search-change="search"
      @input="selected => $emit('selected', selected)"
      @tag="addTag"
    >
      <template
        slot="tag"
        slot-scope="props"
      >
        <div class="tags has-addons is-multiselect-tags">
          <span class="tag is-dark">{{ $i18n.locale === 'en' ? props.option.name_en : props.option.name_lv }}</span>
          <a
            @mousedown.prevent="removeTag(props.option)"
            class="tag is-delete"
          >
          </a>
        </div>
      </template>

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

<script>
  import ErrorHandler from '../../mixins/error-handler'
  import Multiselect from 'vue-multiselect'
  import debounce from 'lodash/debounce'
  import Spinner from '../spinner.vue'
  import axios from 'axios'

  export default {
    components: {
      Multiselect,
      Spinner
    },
    mixins: [
      ErrorHandler,
    ],
    props: [
      'selected',
    ],
    data: () => ({
      options: []
    }),
    methods: {
      search: debounce(function (search) {
        this.disabled = true

        let params = {
          limit: 10
        }
        if (search) params.search = search

        axios
          .get('admin/tag', { params })
          .then(response => {
            this.disabled = false
            this.options = response.data.data
          })
          .catch(this.handleError)
      }, 500),
      addTag (tag) {
        window.open(`/lv/admin/tag?tag=${tag}`, '_blank')
      },
      removeTag (tag) {
        this.selected.splice(this.selected.findIndex(selectedTag => selectedTag.id === tag.id), 1)
      },
      customLabel (value) {
        return this.$i18n.locale === 'en' ? value.name_en : value.name_lv
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<i18n>
  {
    "en": {
      "label": "Tags",
      "newtag": "Click here to create new tag"
    },
    "lv": {
      "label": "Tēmturi",
      "newtag": "Spied šeit, lai izveidot jaunu tēmturi"
    }
  }
</i18n>
